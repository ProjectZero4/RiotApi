<?php

namespace ProjectZero4\RiotApi\Endpoints;

use GuzzleHttp\Exception\GuzzleException;
use ProjectZero4\RiotApi\Exceptions\RateLimitException;

class Spectator extends Endpoint
{
    /**
     * @var string
     */
    const CURRENT_VERSION = 'v4';

    /**
     * @var string
     */
    const ENDPOINT = 'lol/spectator/{version}';

    /**
     * @var int
     */
    protected int $cacheTime = -1;

    /**
     * @param string $summonerId
     * @return array
     * @throws GuzzleException
     * @throws RateLimitException
     */
    public function bySummonerId(string $summonerId): array
    {
        return $this->sendRequest($this->buildUrl("active-games/by-summoner/{$summonerId}"));
    }

    public function featuredGames(): array
    {
        return $this->sendRequest($this->buildUrl("featured-games"));
    }
}