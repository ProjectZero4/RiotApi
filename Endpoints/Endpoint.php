<?php


namespace ProjectZero4\RiotApi\Endpoints;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use ProjectZero4\RiotApi\Client;
use Psr\Http\Message\ResponseInterface;

abstract class Endpoint
{
    const BASE_URL = "https://{region}.api.riotgames.com";

    protected string $region;

    protected Client $client;

    protected string $version;

    protected int $cacheTime = 300;

    public function __construct(Client $client, string $region)
    {
        $this->client = $client;
        $this->region = $region;
        if(defined(static::class . "::CURRENT_VERSION")) {
            $this->version = static::CURRENT_VERSION;
        }
    }


    protected function sendRequest(string $url): array
    {
//        if(!$this->canMakeRequest($url)) {
//            throw new \Exception("Rate Limit Exceeded", 413);
//        }
        $response = $this->client->get($url);
        return json_decode($response->getBody()->getContents(), true);
//        [$shortAppLimit, $longAppLimit] = explode(',', $response->getHeader('X-App-Rate-Limit')[0]);
//        [$shortMethodLimit, $longMethodLimit] = explode(',', $response->getHeader('X-Method-Rate-Limit')[0]);
//        [$shortAppCurrent, $longAppCurrent] = explode(',', $response->getHeader('X-App-Rate-Limit-Count')[0]);
//        [$shortMethodCurrent, $longMethodCurrent] = explode(',', $response->getHeader('X-Method-Rate-Limit-Count')[0]);
//
//        Cache::add("rateLimit." . static::class, [
//            'app' => [
//                'current' => [
//                    'short' => $shortAppCurrent,
//                    'long' => $longAppCurrent,
//                ],
//                'max' => [
//                    'short' => $shortAppLimit,
//                    'long' => $longAppLimit,
//                ],
//            ],
//            'method' => [
//                'current' => [
//                    'short' => $shortMethodLimit,
//                    'long' => $longMethodLimit,
//                ],
//                'max' => [
//                    'short' => $shortMethodCurrent,
//                    'long' => $longMethodCurrent,
//                ],
//            ],
//        ]);


        dd($response->getBody()->getContents(), $response->getHeaders());

    }

    protected function buildUrl(string $url = null, ?string $version = null): string
    {
        $version = $version ?? $this->version;
        return str_replace('{region}', $this->region, static::BASE_URL . DIRECTORY_SEPARATOR . str_replace('{version}', $version, static::ENDPOINT)) . DIRECTORY_SEPARATOR . $url;
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

    protected function isOutdated(Collection $collection): bool
    {
        $renewCache = false;
        if ($collection->isEmpty()) {
            $renewCache = true;
        }
        foreach ($collection as $cacheable) {
            if ($cacheable->isOutdated($this->cacheTime)) {
                $renewCache = true;
                break;
            }
        }
        return $renewCache;
    }
}
