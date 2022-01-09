<?php

namespace ProjectZero4\RiotApi;

use JetBrains\PhpStorm\Pure;
use ProjectZero4\RiotApi\Endpoints\ChampionMastery;
use ProjectZero4\RiotApi\Endpoints\Game;
use ProjectZero4\RiotApi\Endpoints\League;
use ProjectZero4\RiotApi\Endpoints\Summoner;

/**
 * @param string $path
 * @return string
 */
#[Pure] function riotApiRoot(string $path = ""): string
{
    return "/vendor/riot-api/{$path}";
}

/**
 * @param string $path
 * @return string
 */
#[Pure] function imagesPath(string $path = ""): string
{
    return riotApiRoot("images/{$path}");
}

/**
 * @param string $path
 * @return string
 */
#[Pure] function iconPath(string $path = ""): string
{
    return imagesPath("icons/{$path}");
}
/**
 * @param string $path
 * @return string
 */
#[Pure] function championPath(string $path = ""): string
{
    return imagesPath("champion/{$path}");
}

/**
 * @param int $spellId
 * @return string
 */
#[Pure] function spellUrl(int $spellId): string
{
    return iconPath("spells/{$spellId}.png");
}

/**
 * @param int $perkId
 * @return string
 */
#[Pure] function perkUrl(int $perkId): string
{
    $perkFilename = match($perkId) {
        7200 => '7200_Domination',
        7201 => '7201_Precision',
        7202 => '7202_Whimsy',
        7204 => '7204_Resolve',
        default => '7200_Domination',

    };

    return iconPath("perk/Styles/{$perkFilename}.png");
}
