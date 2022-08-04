<?php

namespace ProjectZero4\RiotApi\Data\Spectator;

use ProjectZero4\RiotApi\Data\Spell;
use ProjectZero4\RiotApi\Models\Champion;
use ProjectZero4\RiotApi\Models\Summoner;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class Participant extends Data
{
    public function __construct(
        public int $teamId,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Spell::class)]
        #[MapInputName('spell1Id')]
        public Spell $spell1,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Spell::class)]
        #[MapInputName('spell2Id')]
        public Spell $spell2,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Champion::class)]
        public Champion $champion,
        public int $profileIconId,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Summoner::class)]
        public Summoner $summoner,
        public bool $bot,
    ) {}


}