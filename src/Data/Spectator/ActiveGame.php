<?php

namespace ProjectZero4\RiotApi\Data\Spectator;

use ProjectZero4\RiotApi\Collections\ParticipantCollection;
use ProjectZero4\RiotApi\Data\Casts\CollectionCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ActiveGame extends Data
{

    protected array $teams;

    public function __construct(
        public int $gameId,
        public int $mapId,
        public string $gameMode,
        public string $gameType,
        public int $gameQueueConfigId,
        #[WithCast(CollectionCast::class, Participant::class)]
        public ParticipantCollection $participants,
        public array $observers,
        public string $platformId,
        public array $bannedChampions,
        public int $gameStartTime,
        public int $gameLength,
    ) {
    }
}