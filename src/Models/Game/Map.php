<?php


namespace ProjectZero4\RiotApi\Models\Game;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Map
 * @package ProjectZero4\RiotApi\Models\Game
 *
 * @property int id
 * @property string name
 * @property string notes
 *
 * @property-read Collection<Queue> queues
 */
class Map extends GameBase
{
    protected $fillable = [
        'id',
        'name',
        'notes',
    ];

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }
}
