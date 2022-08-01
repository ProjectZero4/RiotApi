<?php

namespace ProjectZero4\RiotApi\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class CollectionCast implements Cast
{

    public function cast(DataProperty $property, mixed $value): mixed
    {
        $type = $property->castAttribute()->arguments[0];
        $class = $property->types()->first();
        return new $class(array_map(function ($item) use ($type) {
            return $type::from($item);
        }, $value));
    }
}