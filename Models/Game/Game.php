<?php


namespace ProjectZero4\RiotApi\Models\Game;

use Illuminate\Database\Eloquent\Relations\HasMany;
use ProjectZero4\RiotApi\Models;
use ProjectZero4\RiotApi\RiotApiCollection;

/**
 * Class Game
 * @package ProjectZero4\RiotApi\Models
 *
 * @property int gameCreation
 * @property int gameDuration
 * @property int gameId
 * @property string gameMode
 * @property string gameName
 * @property int gameStartTimestamp
 * @property string gameType
 * @property string gameVersion
 * @property int mapId
 * @property string platformId
 * @property int queueId
 * @property string tournamentCode
 * @property string matchId
 *
 * @property-read int duration
 * @property-read RiotApiCollection<Participant> participants
 * @property-read RiotApiCollection<Team> teams
 */
class Game extends GameBase
{
    protected $fillable = [
        'gameCreation',
        'gameDuration',
        'gameId',
        'gameMode',
        'gameName',
        'gameStartTimestamp',
        'gameType',
        'gameVersion',
        'mapId',
        'platformId',
        'queueId',
        'tournamentCode',
        'matchId',
    ];

    /**
     * @return int
     */
    public function getDurationAttribute(): int
    {
        return $this->gameDuration;
    }

    protected function convertAttributes(array $attributes): array
    {
        unset($attributes['participants'], $attributes['teams']);
        return $attributes;
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
