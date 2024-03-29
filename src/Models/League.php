<?php


namespace ProjectZero4\RiotApi\Models;


use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\Pure;
use function ProjectZero4\RiotApi\iconPath;
use function ProjectZero4\RiotApi\imagesPath;


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
 *
 * @property-read int totalGames
 */
class League extends Base
{
    use Cacheable;

    const SOLO_Q = 'RANKED_SOLO_5x5';
    const FLEX = 'RANKED_FLEX_SR';

    public static int $cacheTime = 60;

    /**
     * @var string
     */
    protected $table = "leagues";

    protected $appends = [
        'unrankedIcon',
        'iconUrl',
        'queueName',
        'winRate',
    ];
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
        if (empty($rank)) {
            return iconPath("tier/{$tier}.png");
        } elseif (empty($tier)) {
            return iconPath("tier/provisional.png");
        }
        $tier = strtolower($tier);
        $rank = strtolower($rank);

        return imagesPath("icons/tier/{$tier}_$rank.png");
    }

    #[Pure] public function iconUrl(): string
    {
        return $this->tierIconUrl($this->tier, $this->rank);
    }

    #[Pure] public function getUnrankedIcon(): string
    {
        return iconPath("tier/provisional.png");
    }

    public function getUnrankedIconAttribute(): string
    {
        return $this->getUnrankedIcon();
    }

    public function getIconUrlAttribute(): string
    {
        return $this->iconUrl();
    }

    public function getTotalGamesAttribute(): int
    {
        return $this->wins + $this->losses;
    }

    public function getWinRateAttribute(): float
    {
        return round($this->wins / $this->totalGames, 2) * 100;
    }
}
