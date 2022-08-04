<?php

namespace ProjectZero4\RiotApi\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class CollectionCast implements Cast
{

    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        $type = $property->attributes->first()->arguments[0];
        $class = array_keys($property->type->acceptedTypes)[0];
        $items = array_map(function ($item) use ($type) {
            return $type::from($item);
        }, $value);
        return new $class($items);
    }
}