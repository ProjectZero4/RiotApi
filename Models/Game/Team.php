<?php


namespace ProjectZero4\RiotApi\Models\Game;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use ProjectZero4\RiotApi\RiotApiCollection;

/**
 * Class Team
 * @package ProjectZero4\RiotApi\Models\Game
 *
 * @property int teamId
 * @property int game_id
 * @property bool win
 * @property string ban_1
 * @property string ban_2
 * @property string ban_3
 * @property string ban_4
 * @property string ban_5
 * @property bool baron_first
 * @property bool champion_first
 * @property bool dragon_first
 * @property bool inhibitor_first
 * @property bool rift_herald_first
 * @property bool tower_first
 * @property int baron_kills
 * @property int champion_kills
 * @property int dragon_kills
 * @property int inhibitor_kills
 * @property int rift_herald_kills
 * @property int tower_kills
 *
 * @property-read RiotApiCollection<Participant> participants
 * @property-read Game games
 */
class Team extends GameBase
{
    protected $fillable = [
        'teamId',
        'win',
        'ban_1',
        'ban_2',
        'ban_3',
        'ban_4',
        'ban_5',
        'baron_first',
        'champion_first',
        'dragon_first',
        'inhibitor_first',
        'rift_herald_first',
        'tower_first',
        'baron_kills',
        'champion_kills',
        'dragon_kills',
        'inhibitor_kills',
        'rift_herald_kills',
        'tower_kills',
        'bans',
        'objectives',
    ];

    /**
     * @param array $attributes
     * @return array{bans: array, objectives: array, teamId: int, win: bool}
     */
    protected function convertAttributes(array $attributes): array
    {
        if (!isset($attributes['teamId'])) {
            return $attributes;
        }
        $converted = [
            'teamId' => $attributes['teamId'],
            'win' => (bool)$attributes['win'],
        ];
        foreach ($attributes['bans'] as $key => $ban) {
            $converted["ban_" . ++$key] = $ban['championId'];
        }
        foreach ($attributes['objectives'] as $key => $objective) {
            $converted[Str::snake($key) . "_kills"] = $objective['kills'];
            $converted[Str::snake($key) . "_first"] = $objective['first'];
        }
        return $converted;
    }

    /**
     * @return HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
