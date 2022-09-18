<?php

namespace ProjectZero4\RiotApi\Data\Casts;
use ProjectZero4\RiotApi\Data\Spell as SpellDto;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function ProjectZero4\RiotApi\riotApi;

class Spell implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): SpellDto
    {
        return SpellDto::from(riotApi()->getSpellByKey($value));
    }
}