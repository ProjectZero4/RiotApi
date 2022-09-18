<?php

namespace ProjectZero4\RiotApi\Data;

use Spatie\LaravelData\Data;

use function ProjectZero4\RiotApi\spellUrl;

class Spell extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $tooltip,
        public int $maxrank,
        public array $cooldown,
        public int $cooldownBurn,
        public array $cost,
        public int $costBurn,
        public array $datavalues,
        public array $effect,
        public array $effectBurn,
        public array $vars,
        public int $key,
        public int $summonerLevel,
        public array $modes,
        public string $costType,
        public int $maxammo,
        public array $range,
        public int $rangeBurn,
        public array $image,
        public string $resource,
    ) {}

    public function imageUrl()
    {
        return spellUrl($this->id);
    }
}