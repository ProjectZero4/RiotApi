<?php


namespace ProjectZero4\RiotApi\Endpoints;


use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
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
    protected function sendRequest(string $url, array $query = [], $depth = 0): array
    {
        if (!$this->canMakeRequest()) {
            if ($depth === env("RIOT_GAMES_API_DEPTH_LIMIT", 5)) {
                throw new Exception("Recursion depth ($depth) exceeded for Endpoint " . static::class);
            }
            sleep($this->waitTime);
            return $this->sendRequest($url, $query, ++$depth);
        }

        $response = $this->client->get($url, $query);
        $this->storeRateLimits($response->getHeaders());
        $body = $response->getBody()->getContents();
        return json_decode($body, true);
    }

    protected function storeRateLimits(array $headers)
    {
        $limits = [];
        $rateLimitHeaders = [
            "x-app-rate-limit",
            "x-app-rate-limit-count",
            "x-method-rate-limit",
            "x-method-rate-limit-count",
        ];

        $typeRegex = "/^x-(\w+)-rate.*-(\w+)$/";
        foreach ($headers as $headerName => $header) {
            $headerName = strtolower($headerName);
            if (!in_array($headerName, $rateLimitHeaders)) {
                continue;
            }

            if (preg_match($typeRegex, $headerName, $matches) === false) {
                continue;
            }
            $type = $matches[1];
            $subType = $matches[2];

            foreach ($this->parseLimits($header) as $interval => $limit) {
                $limits[$type][$interval][$subType] = $limit;
            }

        }
        Cache::put(static::ENDPOINT, $limits['method']);
        Cache::put("RIOT_API_APP_LIMITS", $limits['app']);
    }

    protected function canMakeRequest(): bool
    {
        $limits = array_merge(Cache::get(static::ENDPOINT, []), Cache::get('RIOT_API_APP_LIMITS', []));
        if (!$limits) {
            return true;
        }

        foreach ($limits as $interval => $limit) {
            if ($this->rateLimitExceeded($limit['limit'], $limit['count'], $interval, env("RIOT_GAME_API_BUFFER", 1))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $total
     * @param array $current
     * @param int $buffer
     */
    protected function rateLimitExceeded(int $total, int $current, int $interval,  int $buffer): bool
    {
        if ($total <= ($current + $buffer)) {
            $this->waitTime = $interval;
            return true;
        }

        return false;
    }

    protected function parseLimits(array $headers): array
    {
        $limits = [];

        foreach ($headers as $header) {
            foreach (explode(',', $header) as $limitInterval)
            {
                $limitInterval = explode(':', $limitInterval);
                $limit         = (int)$limitInterval[0];
                $interval      = (int)$limitInterval[1];

                $limits[$interval] = $limit;
            }

        }
        return $limits;
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
