<?php

namespace ProjectZero4\RiotApi\Data\Spectator;

use ProjectZero4\RiotApi\Data\Casts\CollectionCast;
use ProjectZero4\RiotApi\RiotApiCollection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class FeaturedGames extends Data
{
    public function __construct(
        #[WithCast(CollectionCast::class, ActiveGame::class)]
        public RiotApiCollection $gameList,
        public int $clientRefreshInterval
    ) {}
}