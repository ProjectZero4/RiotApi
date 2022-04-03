<?php

namespace ProjectZero4\RiotApi\Tests;

use ProjectZero4\RiotApi\RiotApi;
use Tests\TestCase;

class BaseCase extends TestCase
{

    protected static RiotApi $api;

    public static function setUpBeforeClass(): void
    {
        static::$api = app(RiotApi::class);
    }
    protected function getProjectZero()
    {
        return static::$api->summonerBySummonerName('Project Zero');
    }
}