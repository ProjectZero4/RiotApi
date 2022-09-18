<?php

namespace ProjectZero4\RiotApi\Data\Casts;
use ProjectZero4\RiotApi\RiotApi;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class Summoner implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): \ProjectZero4\RiotApi\Models\Summoner
    {
        /** @var RiotApi $api */
        $api = app(RiotApi::class);
        return $api->summonerBySummonerName($value);
    }
}