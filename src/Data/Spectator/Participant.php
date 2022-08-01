<?php

namespace ProjectZero4\RiotApi\Data\Spectator;

use ProjectZero4\RiotApi\Models\Champion;
use ProjectZero4\RiotApi\Models\Summoner;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

use function ProjectZero4\RiotApi\riotApi;

class Participant extends Data
{
    public function __construct(
        public int $teamId,
        public int $spell1Id,
        public int $spell2Id,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Champion::class)]
        public Champion $champion,
        public int $profileIconId,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Summoner::class)]
        public Summoner $summoner,
        public bool $bot,
    ) {}

    public function toArray(): array
    {
        $api = riotApi();
        return array_merge(parent::toArray(), [
            'spell1Url' => $api->getSpellByKey($this->spell1Id)->imageUrl(),
            'spell2Url' => $api->getSpellByKey($this->spell2Id)->imageUrl(),
        ]);
    }


}