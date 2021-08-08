<?php


namespace ProjectZero4\RiotApi\Endpoints;


use JetBrains\PhpStorm\Pure;
use ProjectZero4\RiotApi\Models\ChampionMastery as ChampionMasteryModel;
use ProjectZero4\RiotApi\Models\Summoner;
use ProjectZero4\RiotApi\Models\Summoner as SummonerModel;
use ProjectZero4\RiotApi\Models\League as LeagueModel;
use Illuminate\Database\Eloquent\Collection;
use function ProjectZero4\RiotApi\iconPath;

class League extends Endpoint
{
    const CURRENT_VERSION = 'v4';

    const ENDPOINT = 'lol/league/{version}';

    protected int $cacheTime = 120;

    public function bySummoner(SummonerModel $summoner)
    {
        $leagues = $summoner->leagues;
        if (!$this->isOutdated($leagues)) {
            return $leagues;
        }
        $response =  $this->sendRequest($this->buildUrl("entries/by-summoner/{$summoner->id}"));
        return $this->buildLeaguesFromResponse($response, $summoner);
    }

    /**
     * @param array $response
     * @param SummonerModel $summoner
     */
    protected function buildLeaguesFromResponse(array $response, Summoner $summoner)
    {
        $leagues = collect();
        foreach($response as $leagueData) {
            $summonerLeague = LeagueModel::where('queueType', $leagueData['queueType'])
                ->where('summonerId', $summoner->id)
                ->firstOrNew();
            $summonerLeague->fill($leagueData);
            $summonerLeague->save();
            $leagues->add($summonerLeague);
        }
        return $leagues;
    }

}
