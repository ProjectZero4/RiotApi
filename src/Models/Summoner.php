<?php


namespace ProjectZero4\RiotApi\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use ProjectZero4\RiotApi\Data\Game\Participant as ParticipantDto;
use ProjectZero4\RiotApi\Data\ParticipantAggregate;
use ProjectZero4\RiotApi\Models\Base;
use ProjectZero4\RiotApi\Models\Game\Game;
use ProjectZero4\RiotApi\Models\Game\Participant;
use ProjectZero4\RiotApi\RiotApiCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use function ProjectZero4\RiotApi\iconPath;
use function ProjectZero4\RiotApi\riotApi;

/**
 * Class Summoner
 * @package ProjectZero4\RiotApi\Models
 * @property-read int internalKey
 * @property string id
 * @property string accountId
 * @property string puuid
 * @property string name
 * @property int profileIconId
 * @property int revisionDate
 * @property int summonerLevel
 * @property string nameKey
 * @property-read RiotApiCollection<ChampionMastery> masteries
 * @property-read RiotApiCollection<League> leagues
 * @property-read RiotApiCollection<Game> recentGames
 * @property-read ?Carbon lastSeen
 * @property-read ?League soloQ
 * @property-read ?League flex
 */
class Summoner extends Base
{
    use Cacheable;

    protected $table = "summoners";

    protected $primaryKey = "internalKey";

    protected $with = [
        'leagues',
    ];

    protected $fillable = [
        'id',
        'accountId',
        'puuid',
        'name',
        'profileIconId',
        'revisionDate',
        'summonerLevel',
    ];

    protected $appends = [
        'soloQ',
        'flex',
    ];

    public static int $cacheTime = 120;

    public static function boot()
    {
        parent::boot();
        self::saving(function (Summoner $summoner) {
            $summoner->nameKey = Summoner::convertSummonerNameToKey($summoner->name);
        });
    }

    public static function convertSummonerNameToKey(string $summonerName): string
    {
        return strtolower(str_replace([' ', '+'], '', urldecode($summonerName)));
    }

    #[Pure] public function iconUrl(): string
    {
        return iconPath("profileicon/{$this->profileIconId}.png");
    }

    public function masteries(): HasMany
    {
        return $this->hasMany(ChampionMastery::class, 'summonerId', 'id');
    }

    public function leagues(): HasMany
    {
        return $this->hasMany(League::class, 'summonerId', 'id');
    }

    /**
     * @return HasManyThrough
     */
    public function games(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, Participant::class, 'summonerId', 'id', 'id', 'game_id')
            ->with('participants');

    }

    public function recentGames(): HasManyThrough
    {
        return $this->games()->orderBy('gameCreation', 'desc')->limit(20);
    }

    public function gameTest()
    {
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'summonerId', 'id');
    }

    public function getLastSeenAttribute(): ?Carbon
    {
        if ($latestGame = $this->recentGames->first()) {
            return $latestGame->gameCreation->addSeconds($latestGame->gameDuration);
        }
        return null;
    }

    public function lastGame()
    {
        return $this->games()->with('participants')->latest('gameCreation')->limit(1)->first();
    }

    public function lastGameParticipant(): ParticipantDto
    {
        return $this->lastGame()->participantBySummoner($this);
    }

    public function getSoloQAttribute(): ?League
    {
        return $this->leagues()->where('queueType', League::SOLO_Q)->first();
    }

    public function getFlexAttribute(): ?League
    {
        return $this->leagues()->where('queueType', League::FLEX)->first();
    }

    public static function fromName(string $summonerName): ?Summoner
    {
        return Summoner::where('nameKey', Summoner::convertSummonerNameToKey($summonerName))->first();
    }

    public function championAggregate(Champion $champion): ParticipantAggregate
    {
        return ParticipantAggregate::from($this->participants()
            ->where('championId', $champion->key)
            ->select([
                DB::raw("avg(assists) as assists"),
                DB::raw("avg(deaths) as deaths"),
                DB::raw("avg(kills) as kills"),
                DB::raw("count(*) as games"),
                DB::raw("sum(win) as wins"),
            ])
            ->first());
    }

    public function getPersonalSplashUrl(): string
    {
        if ($this->participants()->count() > 0 && ($lastGameSplash = $this->lastGameParticipant()->champion?->splashUrl())) {
            return $lastGameSplash;
        }

        if ($mastery = riotApi()->masteryBySummoner($this)->first()) {
            return $mastery->champion->splashUrl();
        }

        return Champion::whereId('Yasuo')->first()->splashUrl();
    }
}
