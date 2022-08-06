<?php


namespace ProjectZero4\RiotApi;


use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Artisan;
use ProjectZero4\RiotApi\Data\Spectator\ActiveGame;
use ProjectZero4\RiotApi\Data\Spectator\FeaturedGames;
use ProjectZero4\RiotApi\Endpoints\Spectator;
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
use ProjectZero4\RiotApi\Data\Spell;

use function PHPUnit\Framework\isEmpty;

/**
 * Class RiotApi
 * @package ProjectZero4\RiotApi
 * @property-read Summoner $summoner
 * @property-read ChampionMastery $mastery
 * @property-read League $league
 * @property-read Game $game
 * @property-read Status $status
 * @property-read Spectator $spectator
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
    public readonly string $region;

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
     * @var Status
     */
    protected Status $_status;

    /**
     * @var Spectator
     */
    protected Spectator $_spectator;

    /**
     * RiotApi constructor.
     */
    public function __construct(string $region)
    {
        $this->client = new Client();
        $this->region = $region;
    }

    /**
     * Lazy loading of endpoints used at runtime
     *
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
            'status' => $this->_status = new Status($this->client, $this->region),
            'spectator' => $this->_spectator = new Spectator($this->client, $this->region),
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
        $this->leagueBySummoner($summonerModel);
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

    public function activeGameBySummoner(Models\Summoner $summoner): array
    {
        return $this->spectator->bySummonerId($summoner->id);
    }

    public function featuredGames(): FeaturedGames
    {
        if (!$featuredGames = Cache::get('featuredGames')) {
            $featuredGames = $this->spectator->featuredGames();
            // to allow $summoner as DTO property instead of $summonerName
            foreach ($featuredGames['gameList'] as &$game) {
                foreach ($game['participants'] as &$participant) {
                    $participant['summoner'] = $participant['summonerName'];
                    $participant['champion'] = $participant['championId'];
                }
            }
            Cache::put('featuredGames', $featuredGames);
        }

        return FeaturedGames::from($featuredGames);
    }

    /**
     * @param Models\Summoner $summoner
     * @param array{startTime: int, endTime: int, queue: int, type: string, start: int, count: int} $query
     * @return array
     * @throws Exceptions\RateLimitException
     * @throws GuzzleException
     */
    public function listGamesBySummoner(Models\Summoner $summoner, array $query = []): array
    {
        return $this->game->listBySummoner($summoner, $query);
    }

    public function rawGameById(string $matchId): array
    {
        return $this->game->byMatchId($matchId);
    }


    /**
     *  =================== OTHER FUNCTIONS ===================
     */

    public function getChampions()
    {
        $champions = Cache::get('lol-champions');
        if (!$champions) {
            $champions = json_decode(
                $this->client->get(
                    "https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()}/data/en_GB/champion.json"
                )->getBody()->getContents(),
                true
            );
            Cache::add('lol-champions', $champions, 3600);
        }
        return $champions;
    }
    public function getSpells()
    {
        $spells = Cache::get('lol-spells');
        if (!$spells) {
            $spells = json_decode(
                $this->client->get(
                    "https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()}/data/en_GB/summoner.json"
                )->getBody()->getContents(),
                true
            );
            Cache::add('lol-spells', $spells, 3600);
        }
        return $spells;
    }

    /**
     * @param int $key
     * @return Spell
     */
    public function getSpellByKey(int $key): Spell
    {
        $spells = collect($this->getSpells()['data'])->mapWithKeys(function ($spell, $key) {
            return [$spell['key'] => $spell];
        });

        return Spell::from($spells->get($key));
    }

    public function getChampion(string $championId)
    {
        $champions = Cache::get("lol-champion-{$championId}");
        if (!$champions) {
            $champions = json_decode(
                $this->client->get(
                    "https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()}/data/en_GB/champion/{$championId}.json"
                )->getBody()->getContents(),
                true
            );
            Cache::add("lol-champion-{$championId}", $champions, 3600);
        }
        return $champions;
    }

    public function getChampionByName(string $championName): Champion
    {
        $championKey = Champion::convertNameToKey($championName);
        return Champion::whereKey($championKey)->first();
    }

    public function championByKey(int $championKey): Champion
    {
        $cacheKey = "champions/key/$championKey";
        if (!$champion = Cache::get($cacheKey)) {
            $champion = Champion::where('key', $championKey)->firstOrFail();
            Cache::put($cacheKey, $champion->toArray());
            return $champion;
        }

        return (new Champion($champion));
    }

    public function getPatches()
    {
        $versions = Cache::get('lol-patches');
        if (!$versions) {
            $versions = json_decode(
                $this->client->get("https://ddragon.leagueoflegends.com/api/versions.json")->getBody()->getContents(),
                true
            );
            Cache::add('lol-patches', $versions, 3600);
        }
        return $versions;
    }

    #[ArrayShape(["name" => "string", "start" => "int", "season" => "int"])]
    public function getCurrentPatch()
    {
        $patches = $this->getPatches();
        return reset($patches);
    }

    public function getStatus(): string
    {
        $status = $this->status->getStatus();
        if (isEmpty($status)) {
            return 'online';
        }
        return $status['maintenance_status'];
    }

    public function getCurrentSeason(): int
    {
        $currentPatch = $this->getCurrentPatch();
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
        foreach ($patches as $patch) {
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
            $maps = json_decode(
                $this->client->get("https://static.developer.riotgames.com/docs/lol/maps.json")->getBody()->getContents(
                ),
                true
            );
            Cache::add('lol-maps', $maps, 3600);
        }
        return $maps;
    }

    public function getQueues(): array
    {
        $queues = Cache::get('lol-queues');
        if (!$queues) {
            $queues = json_decode(
                $this->client->get("https://static.developer.riotgames.com/docs/lol/queues.json")->getBody(
                )->getContents(),
                true
            );
            Cache::add('lol-queues', $queues, 3600);
        }
        return $queues;
    }

    public function getSummonerSpells()
    {
        $spells = Cache::get('lol-spells');
        if (!$spells) {
            $spells = json_decode(
                $this->client->get(
                    "https://ddragon.leagueoflegends.com/cdn/{$this->getCurrentPatch()}/data/en_US/summoner.json"
                )->getBody()->getContents(),
                true
            );
            Cache::add('lol-spells', $spells, 3600);
        }
        return $spells;
    }

    public function processLiveGameStats(ActiveGame $liveGame)
    {
        foreach ($liveGame->participants as $participant) {
            $latestGame = $participant->summoner->recentGames()->first();
            if (!$latestGame || ($latestGame->created_at && $latestGame->created_at->gt(Carbon::now()->subHours(2)))) {
                continue;
            }

            Artisan::call("riotApi:games", [
                '--summonerName' => $participant->summoner->name,
            ]);
        }
    }
}
