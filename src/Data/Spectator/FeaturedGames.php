<?php

namespace ProjectZero4\RiotApi\Data\Spectator;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class FeaturedGames extends Data
{
    public function __construct(
        /** @var DataCollection<ActiveGame> */
        public DataCollection $gameList,
        public int $clientRefreshInterval
    ) {}
}