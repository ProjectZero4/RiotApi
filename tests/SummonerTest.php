<?php

namespace ProjectZero4\RiotApi\Tests;

use ProjectZero4\RiotApi\Models\Summoner;
use ProjectZero4\RiotApi\RiotApi;

class SummonerTest extends BaseCase
{
    public function test_riot_api_construct()
    {
        $api = new RiotApi('euw1');
        $this->assertEquals($api->region, 'euw1');
    }

    public function test_summoner_by_name(): Summoner
    {
        $summoner = static::$api->summonerBySummonerName('Project Zero');
        $this->assertProjectZero($summoner);
        return $summoner;
    }

    /**
     * @depends test_summoner_by_name
     * @param Summoner $summoner
     * @return void
     */
    public function test_summoner_by_id(Summoner $summoner)
    {
        /** @var RiotApi $api */
        $api = app(RiotApi::class);
        $summoner = $api->summonerBySummonerId($summoner->id);
        $this->assertProjectZero($summoner);
    }


    /**
     * @depends test_summoner_by_name
     * @param Summoner $summoner
     * @return void
     */
    public function test_summoner_by_puuid(Summoner $summoner)
    {
        /** @var RiotApi $api */
        $api = app(RiotApi::class);
        $summoner = $api->summonerByPuuid($summoner->puuid);
        $this->assertProjectZero($summoner);
    }

    /**
     * @depends test_summoner_by_name
     * @param Summoner $summoner
     * @return void
     */
    public function test_summoner_by_account_id(Summoner $summoner)
    {
        /** @var RiotApi $api */
        $api = app(RiotApi::class);
        $summoner = $api->summonerByAccountId($summoner->accountId);
        $this->assertProjectZero($summoner);
    }

    protected function assertProjectZero(Summoner $summoner)
    {
        $this->assertEquals('Project Zero', $summoner->name);
        $this->assertEquals('projectzero', $summoner->nameKey);
    }
}

