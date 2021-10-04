<?php


namespace ProjectZero4\RiotApi;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use ProjectZero4\RiotApi\Endpoints\Status;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\ArrayShape;
use ProjectZero4\RiotApi\Endpoints\ChampionMastery;
use ProjectZero4\RiotApi\Endpoints\Endpoint;
use ProjectZero4\RiotApi\Endpoints\Game;
use ProjectZero4\RiotApi\Endpoints\League;
use ProjectZero4\RiotApi\Endpoints\Summoner;
use ProjectZero4\RiotApi\Models\Champion;
use function PHPUnit\Framework\isEmpty;

/**
 * Class RiotApi
 * @package ProjectZero4\RiotApi
 * @property-read Summoner $summoner
 * @property-read ChampionMastery $mastery
 * @property-read League $league
 * @property-read Game $games
 * @property-read Status $status
 */
class RiotApi
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    protected string $region;

    /**
     * @var Summoner
     */
    protected Summoner $_summoner;

    /**
     * @var Endpoints\League
     */
    protected Endpoints\League $_league;

    /**
     * @var ChampionMastery
     */
    protected ChampionMastery $_mastery;

    /**
     * @var Game
     */
    protected Game $_game;

    /**
     * RiotApi constructor.
     */
    public function __construct(string $region)
    {
        $this->client = new Client();
        $this->summoner = new Summoner($this->client, $region);
        $this->mastery = new ChampionMastery($this->client, $region);
        $this->league = new League($this->client, $region);
        $this->status = new Status($this->client, $region);
        $this->region = $region;
    }

    /**
     * @param string $endpoint
     * @return Endpoint
     * @throws Exception
     */
    protected function endpoint(string $endpoint): Endpoint
    {
        $realEndpoint = "_$endpoint";
        if (isset($this->$realEndpoint)) {
            return $this->$realEndpoint;
        }

        return match ($endpoint) {
            'summoner' => $this->_summoner = new Summoner($this->client, $this->region),
            'mastery' => $this->_mastery = new ChampionMastery($this->client, $this->region),
            'league' => $this->_league = new League($this->client, $this->region),
            'game' => $this->_game = new Game($this->client, $this->region),
            default => throw new Exception("$endpoint is not currently supported or is invalid!"),
        };
    }

    /**
     * @param string $name
     * @return Endpoint
     * @throws Exception
     */
    public function __get(string $name)
    {
        if (property_exists($this, "_$name")) {
            return $this->endpoint($name);
        }
        throw new Exception("$name does not exist!");
    }

    /**
     * =================== SUMMONER ENDPOINTS ===================
     */

    /**
     * @param string $accountId
     * @return Models\Summoner
     */
    public function summonerByAccountId(string $accountId): Models\Summoner
    {
        $summonerModel = Models\Summoner::firstOrNew(["accountId" => $accountId]);
        if (!$summonerModel->isOutdated()) {
            return $summonerModel;
        }
        $response = $this->summoner->byAccountId($accountId);

        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    /**
     * @param string $summonerName
     * @return Models\Summoner
     */
    public function summonerBySummonerName(string $summonerName): Models\Summoner
    {
        $nameKey = Models\Summoner::convertSummonerNameToKey($summonerName);
        $summonerModel = Models\Summoner::firstOrNew(["nameKey" => $nameKey]);
        if (!$summonerModel->isOutdated()) {
            return $summonerModel;
        }
        $response = $this->summoner->bySummonerName($summonerName);
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    /**
     * @param string $puuid
     * @return Models\Summoner
     */
    public function summonerByPuuid(string $puuid): Models\Summoner
    {
        $summonerModel = Models\Summoner::firstOrNew(["puuid" => $puuid]);
        if (!$summonerModel->isOutdated()) {
            return $summonerModel;
        }
        $response = $this->summoner->byPuuid($puuid);
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    /**
     * @param string $summonerId
     * @return Models\Summoner
     */
    public function summonerBySummonerId(string $summonerId): Models\Summoner
    {
        $summonerModel = Models\Summoner::firstOrNew(["id" => $summonerId]);
        if (!$summonerModel->isOutdated()) {
            return $summonerModel;
        }
        $response = $this->summoner->bySummonerId($summonerId);
        $summonerModel->fill($response)->save();
        return $summonerModel;
    }

    /**
     * =================== CHAMPION MASTERY ENDPOINTS ===================
     */

    /**
     * @param Models\Summoner $summoner
     * @return RiotApiCollection<ChampionMastery>
     */
    public function masteryBySummoner(Models\Summoner $summoner): RiotApiCollection
    {
        $masteries = $summoner->masteries;
        if (!$masteries->isOutdated()) {
            return $masteries;
        }
        $response = $this->mastery->bySummoner($summoner);
        $championMasteries = new RiotApiCollection;
        foreach ($response as $masteryData) {
            $championMastery = Models\ChampionMastery::where('championId', $masteryData['championId'])
                ->where('summonerId', $summoner->id)
                ->firstOrNew();
            $championMastery->fill($masteryData);
            $championMastery->save();
            $championMasteries->add($championMastery);
        }
        return $championMasteries;
    }

    /**
     * @param Models\Summoner $summoner
     * @param Champion $champion
     * @return Models\ChampionMastery
     */
    public function masteryBySummonerByChampion(Models\Summoner $summoner, Champion $champion): Models\ChampionMastery
    {
        return $this->mastery->bySummonerByChampion($summoner, $champion);
    }

    public function masteryScoreBySummoner(Models\Summoner $summoner): int
    {
        return $this->mastery->scoreBySummoner($summoner);
    }

    public function leagueBySummoner(Models\Summoner $summoner): array|Collection
    {
        return $this->league->bySummoner($summoner);
    }

    /**
     * =================== GAME (Match) ENDPOINTS ===================
     */


    /**
     * @param Models\Summoner $summoner
     * @param array{startTime: int, endTime: int, queue: int, type: string, start: int, count: int} $query
     * @return array
     */
    public function listGamesBySummoner(Models\Summoner $summoner, array $query = []): array
    {
        Log::info(print_r($query, true));
        return $this->game->listBySummoner($summoner, $query);
    }

    public function rawGameById(string $gameId): array
    {
        return $this->game->byGameId($gameId);
    }


    /**
     *  =================== OTHER FUNCTIONS ===================
     */

    public function getChampions()
    {
        $champions = Cache::get('lol-champions');
        if (!$champions) {
            $champions = json_decode($this->client->get("https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()['name']}.1/data/en_GB/champion.json")->getBody()->getContents(),
                true);
            Cache::add('lol-champions', $champions, 3600);
        }
        return $champions;
    }

    public function getChampion(string $championId)
    {
        $champions = Cache::get("lol-champion-{$championId}");
        if (!$champions) {
            $champions = json_decode($this->client->get("https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()['name']}.1/data/en_GB/champion/{$championId}.json")->getBody()->getContents(),
                true);
            Cache::add("lol-champion-{$championId}", $champions, 3600);
        }
        return $champions;
    }

    public function getChampionByName(string $championName): Champion
    {
        $championKey = Champion::convertNameToKey($championName);
        return Champion::whereKey($championKey)->first();
    }

    public function getPatches()
    {
        $versions = Cache::get('lol-patches');
        if (!$versions) {
            $versions = json_decode($this->client->get("https://raw.githubusercontent.com/CommunityDragon/Data/master/patches.json")->getBody()->getContents(),
                true);
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

    public function getStatus()
    {
        $status = $this->status->getStatus();
        if (isEmpty($status)) {
            return 'online';
        }
        return $status['maintenance_status'];
    }

    public function getCurrentSeason(): int
    {
        $currentPatch = $this->getCurrentPatch()['name'];
        [$season] = explode('.', $currentPatch);
        return $season;
    }

    /**
     * @param int|null $season
     * @return array
     */
    #[ArrayShape(['start' => "\Carbon\Carbon", 'end' => "\Carbon\Carbon"])]
    public function getSeasonTimes(?int $season = null): array
    {
        $season = $season ?? $this->getCurrentSeason();
        $patches = $this->getPatches();
        $startTime = 0;
        $endTime = 0;
        foreach ($patches['patches'] as $patch) {
            if ($this->patchIsPartOfSeason($patch['name'], $season)) {
                if ($startTime === 0) {
                    $startTime = $patch['start'];
                }
            }

            if ($this->patchIsPartOfSeason($patch['name'], $season + 1)) {
                $endTime = $patch['start'];
                break;
            }
        }
        if ($endTime === 0) {
            $endTime = Carbon::now()->timestamp;
        }

        $start = Carbon::createFromTimestamp($startTime);
        $end = Carbon::createFromTimestamp($endTime);
        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    public function patchIsPartOfSeason(string $patch, int $season): bool
    {
        return str_starts_with($patch, $season);
    }

    public function getMaps(): array
    {
        $maps = Cache::get('lol-maps');
        if (!$maps) {
            $maps = json_decode($this->client->get("https://static.developer.riotgames.com/docs/lol/maps.json")->getBody()->getContents(),
                true);
            Cache::add('lol-maps', $maps, 3600);
        }
        return $maps;
    }

    public function getQueues(): array
    {
        $queues = Cache::get('lol-queues');
        if (!$queues) {
            $queues = json_decode($this->client->get("https://static.developer.riotgames.com/docs/lol/queues.json")->getBody()->getContents(),
                true);
            Cache::add('lol-queues', $queues, 3600);
        }
        return $queues;
    }

    public function getSummonerSpells()
    {
        $spells = Cache::get('lol-spells');
        if (!$spells) {
            $spells = json_decode($this->client->get("https://ddragon.leagueoflegends.com/cdn/11.19.1/data/en_US/summoner.json")->getBody()->getContents(),
                true);
            Cache::add('lol-spells', $spells, 3600);
        }
        return $spells;
    }
}
