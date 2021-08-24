<?php

namespace App\packages\ProjectZero4\RiotApi\Models;

use App\packages\ProjectZero4\RiotApi\RiotApiCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static static firstOrNew(array $array = [])
 * @method static static where(string $column, string $operator, string $value = "")
 */
abstract class Base extends Model
{

    /**
     * @param array $models
     * @return RiotApiCollection
     */
    public function newCollection(array $models = []): RiotApiCollection
    {
        return new RiotApiCollection($models);
    }
}
