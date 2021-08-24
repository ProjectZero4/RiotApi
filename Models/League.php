<?php


namespace ProjectZero4\RiotApi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\Pure;
use function ProjectZero4\RiotApi\iconPath;


/**
 * Class League
 * @package ProjectZero4\RiotApi\Models
 * @mixin Builder
 * @property string id
 * @property string leagueId
 * @property string queueType
 * @property string tier
 * @property string rank
 * @property string summonerId
 * @property string summonerName
 * @property int leaguePoints
 * @property int wins
 * @property int losses
 * @property boolean veteran
 * @property boolean inactive
 * @property boolean freshBlood
 * @property boolean hotStreak
 * @property-read string queueName
 */
class League extends Model
{
    use Cacheable;

    /**
     * @var string
     */
    protected $table = "leagues";
    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'leagueId',
        'queueType',
        'tier',
        'rank',
        'summonerId',
        'summonerName',
        'leaguePoints',
        'wins',
        'losses',
        'veteran',
        'inactive',
        'freshBlood',
        'hotStreak',
    ];

    /**
     * @return string
     */
    public function getQueueNameAttribute(): string
    {
        return match ($this->queueType) {
            'RANKED_SOLO_5x5' => 'Solo Queue',
            'RANKED_FLEX_SR' => 'Flex',
            default => 'Unknown',
        };
    }

    #[Pure] public function tierIconUrl($tier, $rank): string
    {
        if (empty($rank)){
            return iconPath("tier/{$tier}.png");
        } elseif (empty($tier)){
            return iconPath("tier/provisional.png");
        }

        return iconPath("tier/{$tier}_{$rank}.png");
    }

    #[Pure] public function getUnrankedIcon(): string
    {
        return iconPath("tier/provisional.png");
    }

}
