<?php


namespace ProjectZero4\RiotApi\Endpoints;


use Illuminate\Support\Collection;
use ProjectZero4\RiotApi\Models\Champion;
use ProjectZero4\RiotApi\Models\ChampionMastery as ChampionMasteryModel;
use ProjectZero4\RiotApi\Models\Summoner;

class ChampionMastery extends Endpoint
{
    const CURRENT_VERSION = 'v4';

    const ENDPOINT = 'lol/champion-mastery/{version}';

    protected int $cacheTime = 300;

    /**
     * @param Summoner $summoner
     * @return array{0: array{}}
     */
    public function bySummoner(Summoner $summoner): array
    {
        return $this->sendRequest($this->buildUrl("champion-masteries/by-summoner/{$summoner->id}"));
    }

    /**
     * @param Summoner $summoner
     * @param Champion $champion
     * @return ChampionMasteryModel
     */
    public function bySummonerByChampion(Summoner $summoner, Champion $champion): ChampionMasteryModel
    {
        /**
         * @var ChampionMasteryModel $championMastery
         */
        $championMastery = $summoner->masteries()->where('championId', $champion->key)->firstOrNew();
        if ($championMastery->isOutdated()) {
            $response = $this->sendRequest($this->buildUrl("champion-masteries/by-summoner/{$summoner->id}/by-champion/{$champion->key}"));
            $championMastery->fill($response);
            $championMastery->save();
        }
        return $championMastery;
    }

    /**
     * @param Summoner $summoner
     * @return int
     */
    public function scoreBySummoner(Summoner $summoner): int
    {
        $masteries = $summoner->masteries;
        if ($masteries->isOutdated()) {
            $masteries = collect($this->bySummoner($summoner));
        }
        return $masteries->sum('championLevel');
    }

    /**
     * @param array $response
     * @param Summoner $summoner
     * @return Collection<ChampionMasteryModel>
     */
    protected function buildMasteriesFromResponse(array $response, Summoner $summoner): Collection
    {
        $championMasteries = collect();
        foreach ($response as $masteryData) {
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
