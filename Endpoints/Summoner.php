<?php


namespace ProjectZero4\RiotApi\Endpoints;

use ProjectZero4\RiotApi\Models\Summoner as SummonerModel;

class Summoner extends Endpoint
{
    const CURRENT_VERSION = 'v4';

    const ENDPOINT = 'lol/summoner/{version}/summoners';

    protected int $cacheTime = 120;

    public function byAccountId(string $accountId): SummonerModel
    {
        $summonerModel = $this->getModelFromCache("accountId", $accountId);
        if(!$summonerModel->isOutdated($this->cacheTime)) {
            return $summonerModel;
        }
        $response = $this->sendRequest($this->buildUrl("by-account/{$accountId}"));
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    public function bySummonerName(string $summonerName): SummonerModel
    {
        $nameKey = SummonerModel::convertSummonerNameToKey($summonerName);
        $summonerModel = $this->getModelFromCache("nameKey", $nameKey);
        if(!$summonerModel->isOutdated($this->cacheTime)) {
            return $summonerModel;
        }
        $response = $this->sendRequest($this->buildUrl("by-name/{$nameKey}"));
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    public function byPuuid(string $puuid): SummonerModel
    {
        $summonerModel = $this->getModelFromCache("puuid", $puuid);
        if(!$summonerModel->isOutdated($this->cacheTime)) {
            return $summonerModel;
        }
        $response = $this->sendRequest($this->buildUrl("by-puuid/{$puuid}"));
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    public function bySummonerId(string $summonerId): SummonerModel
    {
        $summonerModel = $this->getModelFromCache("id", $summonerId);
        if(!$summonerModel->isOutdated($this->cacheTime)) {
            return $summonerModel;
        }
        $response = $this->sendRequest($this->buildUrl($summonerId));
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    private function getModelFromCache(string $column, string $value): SummonerModel
    {
        return SummonerModel::firstOrNew([$column => $value]);
    }

}
