<?php


namespace ProjectZero4\RiotApi\Models;

use Illuminate\Database\Eloquent\Model;
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
 */
class Summoner extends Model
{
    use Cacheable;

    protected $table = "summoners";

    protected $fillable = [
        'id',
        'accountId',
        'puuid',
        'name',
        'profileIconId',
        'revisionDate',
        'summonerLevel',
    ];

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
}
