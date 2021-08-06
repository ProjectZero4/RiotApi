<?php


namespace ProjectZero4\RiotApi;


use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\ArrayShape;
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

    /**
     * =================== SUMMONER ENDPOINTS ===================
     */

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

    /**
     * =================== CHAMPION MASTERY ENDPOINTS ===================
     */

    public function masteryBySummoner(Summoner $summoner)
    {
        $this->mastery->bySummoner();
    }


    /**
     *  =================== OTHER FUNCTIONS ===================
     */

    public function getChampions()
    {
        $champions = Cache::get('lol-champions');
        if(!$champions) {
            $champions = json_decode($this->client->get("https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()['name']}.1/data/en_GB/champion.json")->getBody()->getContents(), true);
            Cache::add('lol-champions', $champions, 3600);
        }
        return $champions;
    }

    public function getChampion(string $championId)
    {
        $champions = Cache::get("lol-champion-{$championId}");
        if(!$champions) {
            $champions = json_decode($this->client->get("https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()['name']}.1/data/en_GB/champion/{$championId}.json")->getBody()->getContents(), true);
            Cache::add("lol-champion-{$championId}", $champions, 3600);
        }
        return $champions;
    }

    public function getPatches()
    {
        $versions = Cache::get('lol-patches');
        if(!$versions) {
            $versions = json_decode($this->client->get("https://raw.githubusercontent.com/CommunityDragon/Data/master/patches.json")->getBody()->getContents(), true);
            Cache::add('lol-patches', $versions, 3600);
        }
        return $versions;
    }

    #[ArrayShape(["name" => "string", "start" => "int", "season" => "int"])]
    public function getCurrentPatch()
    {
        $patches = $this->getPatches()['patches'];
        return last($patches);
    }



}
