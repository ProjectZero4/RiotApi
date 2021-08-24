<?php


namespace ProjectZero4\RiotApi\Models;


use App\packages\ProjectZero4\RiotApi\Models\Base;
use App\packages\ProjectZero4\RiotApi\RiotApiCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ChampionMastery
 * @package ProjectZero4\RiotApi\Models
 * @property-read int id
 * @property int championId
 * @property int championLevel
 * @property int championPoints
 * @property int championPointsSinceLastLevel
 * @property int championPointsUntilNextLevel
 * @property bool chestGranted
 * @property int tokensEarned
 * @property string summonerId
 */
class ChampionMastery extends Base
{
    use Cacheable;

    protected $table = "champion_masteries";

    protected $fillable = [
        'championId',
        'championLevel',
        'championPoints',
        'championPointsSinceLastLevel',
        'championPointsUntilNextLevel',
        'chestGranted',
        'tokensEarned',
        'summonerId',
    ];

    /**
     * @return BelongsTo
     */
    public function summoner(): BelongsTo
    {
        return $this->belongsTo(Summoner::class, 'summonerId', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function champion(): BelongsTo
    {
        return $this->belongsTo(Champion::class, "championId", "key");
    }
}
