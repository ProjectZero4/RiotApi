<?php


namespace ProjectZero4\RiotApi\Endpoints;


use ProjectZero4\RiotApi\Models\Summoner;

class ChampionMastery extends Endpoint
{
    const CURRENT_VERSION = 'v4';

    const ENDPOINT = 'lol/champion-mastery/{version}';

    protected int $cacheTime = 300;

    public function bySummoner(Summoner $summoner)
    {


        if(!$summoner->isOutdated($this->cacheTime)) {
            return $summonerModel;
        }
        $response = $this->sendRequest($this->buildUrl("champion-mastery/by-summoner/{$summoner->id}"));
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    public function bySummonerByChampion(Summoner $summoner, Champion $champion)
    {

    }
}
