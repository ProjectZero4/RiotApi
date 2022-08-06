<?php


namespace ProjectZero4\RiotApi\Models\Game;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use ProjectZero4\RiotApi\Data\Game\Participant as ParticipantDto;
use ProjectZero4\RiotApi\Models\Summoner as SummonerModel;
use ProjectZero4\RiotApi\RiotApiCollection;

/**
 * Class Game
 * @package ProjectZero4\RiotApi\Models
 *
 * @property-read Carbon gameCreation
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

    public function getGameCreationAttribute(int $gameCreation): Carbon
    {
        return Carbon::createFromTimestampMs($gameCreation);
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

    public function participantBySummoner(SummonerModel $summoner): ParticipantDto
    {
        $participant = $this->participants()->where('summonerId', $summoner->id)->first();
        return ParticipantDto::from($participant->toArray());
    }
}
