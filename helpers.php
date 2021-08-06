<?php

namespace ProjectZero4\RiotApi;

use JetBrains\PhpStorm\Pure;

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
