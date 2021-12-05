<?php


namespace ProjectZero4\RiotApi\Models\Game;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Summoner
 * @package ProjectZero4\RiotApi\Models
 * @property int $id
 * @property int $map_id
 * @property string $description
 * @property string $notes
 *
 * @property-read Carbon $updated_at
 * @property-read Carbon $created_at
 * @property-read Collection<Map> map
 */
class Queue extends GameBase
{
    protected $fillable = [
        'id',
        'map_id',
        'description',
        'notes',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
