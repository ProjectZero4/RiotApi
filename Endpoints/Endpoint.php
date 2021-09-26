<?php


namespace ProjectZero4\RiotApi\Endpoints;


use App\packages\ProjectZero4\RiotApi\Exceptions\RateLimitException;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use ProjectZero4\RiotApi\Client;

abstract class Endpoint
{
    const BASE_URL = "https://{region}.api.riotgames.com";
    const ENDPOINT = "/";

    protected string $region;

    protected Client $client;

    protected string $version;

    protected int $cacheTime = 300;

    private int $waitTime = 0;

    public function __construct(Client $client, string $region)
    {
        $this->client = $client;
        $this->region = $region;
        if (defined(static::class . "::CURRENT_VERSION")) {
            $this->version = static::CURRENT_VERSION;
        }
    }

    /**
     * @param string $url
     * @param array $query
     * @param int $depth
     * @return array
     * @throws GuzzleException|Exception
     */
    protected function sendRequest(string $url, array $query = [], int $depth = 0): array
    {

        if (!$this->canMakeRequest()) {
            if ($depth == env("RIOT_GAMES_API_DEPTH_LIMIT", 5)) {
                $exception =  new RateLimitException("Recursion depth ($depth) exceeded for Endpoint " . static::class);
                $exception->waitTime = $this->waitTime;
                throw $exception;
            }
            if ($depth > 1) {
                dd($depth, env("RIOT_GAMES_API_DEPTH_LIMIT", 5));
            }
            Log::info("Wait time: $this->waitTime");
//            sleep($this->waitTime);
            return $this->sendRequest($url, $query, ++$depth);
        }

        $response = $this->client->get($url, $query);
        $this->storeRateLimits($response->getHeaders());
        $body = $response->getBody()->getContents();
        return json_decode($body, true);
    }

    #[ArrayShape(['short' => "array", 'long' => "array"])]
    protected function parseLimits(string $limitHeader, string $countHeader): array
    {
        if (str_contains($limitHeader, ',')) {
            [$limitShort, $limitLong] = explode(',', $limitHeader);
            [$limitLong, $intervalLong] = explode(':', $limitLong);
            [$countShort, $countLong] = explode(',', $countHeader);
            [$countLong] = explode(':', $countLong);
            $long = [
                'count' => $countLong,
                'limit' => $limitLong,
                'interval' => $intervalLong,
            ];

        } else {
            $limitShort = $limitHeader;
            $countShort = $countHeader;
        }
        [$limitShort, $intervalShort] = explode(':', $limitShort);
        [$countShort] = explode(':', $countShort);
        $limits =  [
            'short' => [
                'count' => $countShort,
                'limit' => $limitShort,
                'interval' => $intervalShort,
            ],
        ];
        if (isset($long)) {
            $limits['long'] = $long;
        }

        return $limits;
    }

    protected function storeRateLimits(array $headers)
    {
        $rateLimits = [];
        $rateLimitGroupHeaders = [
            'app' => [
                "X-App-Rate-Limit",
                "X-App-Rate-Limit-Count",
            ],
            'method' => [
                "X-Method-Rate-Limit",
                "X-Method-Rate-Limit-Count",
            ],
        ];

        foreach ($rateLimitGroupHeaders as $group => $rateLimitHeaders) {
            [$limitHeader, $countHeader] = $rateLimitHeaders;
            $rateLimits[$group] = $this->parseLimits(reset($headers[$limitHeader]), reset($headers[$countHeader]));
        }

        $this->setRateLimits($rateLimits);
    }



    public function getRateLimits(): array
    {
        return array_merge(
            [Cache::tags(['riot-api-method'])->get(static::ENDPOINT, [])],
            [Cache::tags(['riot-api-app'])->get('short', [])],
            [Cache::tags(['riot-api-app'])->get('long', [])],
        );
    }

    public function setRateLimits(array $rateLimits)
    {
        $oldShort = Cache::tags(['riot-api-app'])->get('short');
        $oldLong = Cache::tags(['riot-api-app'])->get('long');
        $oldMethod = Cache::tags(['riot-api-method'])->get(static::ENDPOINT);
        $now = Carbon::now();
        $nowString = Carbon::now()->format('Y-m-d H:i:s');
        $short = $rateLimits['app']['short'];
        $long = $rateLimits['app']['long'];
        $method = $rateLimits['method']['short'];
        if ($oldShort && Carbon::parse($oldShort['created_at'])->addSeconds($oldShort['interval'])->greaterThan($now)) {
            $short['created_at'] = $oldShort['created_at'];
        } else {
            $short['created_at'] = $nowString;
        }
//        dd($oldLong);
        if ($oldLong && Carbon::parse($oldLong['created_at'])->addSeconds($oldLong['interval'])->greaterThan($now)) {
            $long['created_at'] = $oldLong['created_at'];
        } else {
            $long['created_at'] = $nowString;
        }


        if ($oldMethod && Carbon::parse($oldMethod['created_at'])->addSeconds($oldMethod['interval'])->greaterThan($now)) {
            $method['created_at'] = $oldMethod['created_at'];
        } else {
            $method['created_at'] = $nowString;
        }
        Cache::tags(["riot-api-app"])->put('short', $short, $now->diffInSeconds(Carbon::parse($short['created_at']), false) + $short['interval']);
        Cache::tags(["riot-api-app"])->put('long', $long, $now->diffInSeconds(Carbon::parse($long['created_at']), false) + $long['interval']);
        Cache::tags(["riot-api-method"])->put(static::ENDPOINT, $method, $now->diffInSeconds(Carbon::parse($method['created_at']), false) + $method['interval']);
    }

    protected function canMakeRequest(): bool
    {
        $limits = $this->getRateLimits();
        foreach ($limits as $limit) {
            if (empty($limit)) {
                continue;
            }
            if ($this->rateLimitExceeded($limit['limit'], $limit['count'], $limit['interval'], env("RIOT_GAME_API_BUFFER", 1))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $total
     * @param int $current
     * @param int $interval
     * @param int $buffer
     * @return bool
     */
    protected function rateLimitExceeded(int $total, int $current, int $interval,  int $buffer): bool
    {
        Log::info("Total: $total, Current: $current, Buffer: $buffer, Interval: $interval");
        if ($total <= ($current + $buffer)) {
            $this->waitTime = $interval;
            return true;
        }

        return false;
    }


    protected function buildUrl(string $url = null, ?string $version = null): string
    {
        $version = $version ?? $this->version;
        return str_replace('{region}', $this->getRegion(), static::BASE_URL . DIRECTORY_SEPARATOR . str_replace('{version}', $version, static::ENDPOINT)) . DIRECTORY_SEPARATOR . $url;
    }

    protected function getCacheTime(): int
    {
        return 3600;
    }

    /**
     * @param string $version
     * Overrides the current version of the API to use with the specified version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    protected function getRegion(): string
    {
        return $this->region;
    }

}
