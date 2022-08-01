<?php

namespace ProjectZero4\RiotApi\Data\Casts;
use ProjectZero4\RiotApi\RiotApi;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class Champion implements Cast
{
    public function cast(DataProperty $property, mixed $value): \ProjectZero4\RiotApi\Models\Champion
    {
        /** @var RiotApi $api */
        $api = app(RiotApi::class);
        return $api->championByKey($value);
    }
}