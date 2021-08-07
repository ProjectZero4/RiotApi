<?php


namespace ProjectZero4\RiotApi\Endpoints;


use Illuminate\Support\Collection;
use ProjectZero4\RiotApi\Models\ChampionMastery as ChampionMasteryModel;
use ProjectZero4\RiotApi\Models\Summoner;

class ChampionMastery extends Endpoint
{
    const CURRENT_VERSION = 'v4';

    const ENDPOINT = 'lol/champion-mastery/{version}';

    protected int $cacheTime = 300;

    public function bySummoner(Summoner $summoner)
    {
        $masteries = $summoner->masteries;
        $renewCache = false;
        if ($masteries->isEmpty()) {
            $renewCache = true;
        }
        foreach ($masteries as $championMastery) {
            if ($championMastery->isOutdated($this->cacheTime)) {
                $renewCache = true;
                break;
            }
        }
        if (!$renewCache) {
            return $masteries;
        }
        $response = $this->sendRequest($this->buildUrl("champion-masteries/by-summoner/{$summoner->id}"));
        return $this->buildMasteriesFromResponse($response, $summoner);
    }

    public function bySummonerByChampion(Summoner $summoner, Champion $champion)
    {

    }

    /**
     * @param array $response
     * @param Summoner $summoner
     * @return Collection|ChampionMasteryModel[]
     */
    protected function buildMasteriesFromResponse(array $response, Summoner $summoner): Collection
    {
        $championMasteries = collect();
        foreach($response as $masteryData) {
            $championMastery = ChampionMasteryModel::where('championId', $masteryData['championId'])
                ->where('summonerId', $summoner->id)
                ->firstOrNew();
            $championMastery->fill($masteryData);
            $championMastery->save();
            $championMasteries->add($championMastery);
        }
        return $championMasteries;
    }
}
