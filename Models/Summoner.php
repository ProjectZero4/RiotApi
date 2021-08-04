<?php


namespace ProjectZero4\RiotApi\Models;

//{"id":"wKORKdoMFxbFB5zKtazU8oq_YxcoUgHOJ7-ilYhUSfnUwX8","accountId":"wVcRIGt8pPp73YquSNGWUG7vOE7C8zsPjIckuJKRkRHySy8",
//"puuid":"J0do9pRUjhtkVHF3JyrCW7wSgDwMyJKPr9XKibvEN1Gsp3fMrz0x3QU4QBEv2Mhl5xXNekNfg0a2Qg","name":"Project Zero",
//"profileIconId":780,"revisionDate":1627939176000,"summonerLevel":397}
use Illuminate\Database\Eloquent\Model;

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
}
