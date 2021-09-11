<?php


namespace ProjectZero4\RiotApi\Models;

use ProjectZero4\RiotApi\Models\Base;
use ProjectZero4\RiotApi\RiotApiCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use function ProjectZero4\RiotApi\iconPath;

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
 * @property RiotApiCollection<ChampionMastery> masteries
 * @property RiotApiCollection<League> leagues
 */
class Summoner extends Base
{
    use Cacheable;

    protected $table = "summoners";

    protected $primaryKey = "internalKey";

    protected $fillable = [
        'id',
        'accountId',
        'puuid',
        'name',
        'profileIconId',
        'revisionDate',
        'summonerLevel',
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
        return iconPath("profile/{$this->profileIconId}.png");
    }

    public function masteries()
    {
        return $this->hasMany(ChampionMastery::class, 'summonerId', 'id');
    }

    public function leagues()
    {
        return $this->hasMany(League::class, 'summonerId', 'id');
    }
}
