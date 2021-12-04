<?php


namespace ProjectZero4\RiotApi\Endpoints;

use ProjectZero4\RiotApi\Exceptions\RateLimitException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ProjectZero4\RiotApi\Models\Summoner as SummonerModel;

/**
 * Class Summoner
 * @package ProjectZero4\RiotApi\Endpoints
 */
class Game extends Endpoint
{
    /**
     * @var string
     */
    const CURRENT_VERSION = 'v5';

    /**
     * @var string
     */
    const ENDPOINT = 'lol/match/v5/matches';

    /**
     * @var int
     */
    protected int $cacheTime = -1;

    /**
     * @param string $matchId
     * @return array{metadata: array, info: array}
     */
    public function byGameId(string $matchId): array
    {
        return $this->sendRequest($this->buildUrl($matchId));
    }

    /**
     * @param SummonerModel $summoner
     * @param array{startTime: int, endTime: int, queue: int, type: string, start: int, count: int} $query
     * @return array
     * @throws GuzzleException|RateLimitException
     */
    public function listBySummoner(SummonerModel $summoner, array $query = []): array
    {
        return $this->sendRequest($this->buildUrl("by-puuid/$summoner->puuid/ids"), $query);
    }

    public function timelineByGame()
    {
        //todo: this
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getRegion(): string
    {
        return match ($this->region) {
            'euw1' => 'europe',
            default => throw new Exception("$this->region is not currently supported or is invalid!"),
        };

    }
}
