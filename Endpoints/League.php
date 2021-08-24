<?php


namespace ProjectZero4\RiotApi\Endpoints;


use ProjectZero4\RiotApi\Models\Summoner;
use ProjectZero4\RiotApi\Models\Summoner as SummonerModel;
use ProjectZero4\RiotApi\Models\League as LeagueModel;

/**
 * Class League
 * @package ProjectZero4\RiotApi\Endpoints
 */
class League extends Endpoint
{
    /**
     * @var string
     */
    const CURRENT_VERSION = 'v4';
    /**
     * @var string
     */
    const ENDPOINT = 'lol/league/{version}';
    /**
     * @var int
     */
    protected int $cacheTime = 120;

    public function bySummoner(SummonerModel $summoner)
    {
        $leagues = $summoner->leagues;
        if (!$leagues->isOutdated()) {
            return $leagues;
        }
        $response = $this->sendRequest($this->buildUrl("entries/by-summoner/{$summoner->id}"));
        return $this->buildLeaguesFromResponse($response, $summoner);
    }

    /**
     * @param array $response
     * @param SummonerModel $summoner
     * @return \Illuminate\Support\Collection
     */
    protected function buildLeaguesFromResponse(array $response, Summoner $summoner)
    {
        $leagues = collect();
        foreach ($response as $leagueData) {
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
