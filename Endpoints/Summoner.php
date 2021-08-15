<?php


namespace ProjectZero4\RiotApi\Endpoints;

use ProjectZero4\RiotApi\Models\Summoner as SummonerModel;

/**
 * Class Summoner
 * @package ProjectZero4\RiotApi\Endpoints
 */
class Summoner extends Endpoint
{
    /**
     * @var string
     */
    const CURRENT_VERSION = 'v4';

    /**
     * @var string
     */
    const ENDPOINT = 'lol/summoner/{version}/summoners';

    /**
     * @var int
     */
    protected int $cacheTime = 120;

    /**
     * @param string $accountId
     * @return array{id: string, accountId: string, puuid: string, name: string, profileIconId: int, revisionDate: int, summonerLevel: int}
     */
    public function byAccountId(string $accountId): array
    {
        return $this->sendRequest($this->buildUrl("by-account/{$accountId}"));
    }

    /**
     * @param string $summonerName
     * @return array{id: string, accountId: string, puuid: string, name: string, profileIconId: int, revisionDate: int, summonerLevel: int}
     */
    public function bySummonerName(string $summonerName): array
    {
        $nameKey = SummonerModel::convertSummonerNameToKey($summonerName);
        return $this->sendRequest($this->buildUrl("by-name/{$nameKey}"));
    }

    /**
     * @param string $puuid
     * @return array{id: string, accountId: string, puuid: string, name: string, profileIconId: int, revisionDate: int, summonerLevel: int}
     */
    public function byPuuid(string $puuid): array
    {
        return $this->sendRequest($this->buildUrl("by-puuid/{$puuid}"));
    }

    /**
     * @param string $summonerId
     * @return array{id: string, accountId: string, puuid: string, name: string, profileIconId: int, revisionDate: int, summonerLevel: int}
     */
    public function bySummonerId(string $summonerId): array
    {
        return $this->sendRequest($this->buildUrl($summonerId));
    }
}
