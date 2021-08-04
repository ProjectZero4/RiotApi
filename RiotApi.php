<?php


namespace ProjectZero4\RiotApi;


use ProjectZero4\RiotApi\Endpoints\Summoner;

/**
 * Class RiotApi
 * @package ProjectZero4\RiotApi
 */
class RiotApi
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var Summoner
     */
    protected Summoner $summoner;

    /**
     * RiotApi constructor.
     */
    public function __construct(string $region)
    {
        $this->client = new Client();
        $this->summoner = new Summoner($this->client, $region);
    }

    public function summonerByAccountId(string $accountId)
    {
        return $this->summoner->byAccountId($accountId);
    }

    public function summonerBySummonerName(string $summonerName)
    {
        return $this->summoner->bySummonerName($summonerName);
    }

    public function summonerByPuuid(string $puuid)
    {
        return $this->summoner->byPuuid($puuid);
    }

    public function summonerBySummonerId(string $summonerId)
    {
        return $this->summoner->bySummonerId($summonerId);
    }
}
