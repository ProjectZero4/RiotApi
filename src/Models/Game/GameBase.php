<?php


namespace ProjectZero4\RiotApi\Models\Game;

use ProjectZero4\RiotApi\Models;

/**
 * Class GameBase
 * @package ProjectZero4\RiotApi\Models\Game
 */
abstract class GameBase extends Models\Base
{
    use Models\Cacheable;

    public static int $cacheTime = 0;

    public function fill(array $attributes): static
    {
        $attributes = $this->convertAttributes($attributes);
        return parent::fill($attributes);
    }

    protected function convertAttributes(array $attributes): array
    {
        return $attributes;
    }
}
