<?php


namespace ProjectZero4\RiotApi\Endpoints;


use ProjectZero4\RiotApi\Models\Summoner as SummonerModel;

class League extends Endpoint
{
    const CURRENT_VERSION = 'v4';

    const ENDPOINT = 'lol/league/{version}';

    protected int $cacheTime = 120;

    public function bySummoner(SummonerModel $summoner)
    {
        $response =  $this->sendRequest($this->buildUrl("entries/by-summoner/{$summoner->id}"));
        dd($response);
    }
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
}
