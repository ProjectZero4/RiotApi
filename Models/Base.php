<?php

namespace ProjectZero4\RiotApi\Models;

use Illuminate\Database\Query\Builder;
use ProjectZero4\RiotApi\RiotApiCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static static firstOrNew(array $array = [])
 * @method static Builder where(string $column, string $operator, string $value = "")
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
