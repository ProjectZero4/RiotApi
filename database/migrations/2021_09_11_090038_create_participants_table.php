<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->integer('assists')->index();
            $table->integer('baronKills');
            $table->integer('bountyLevel');
            $table->integer('champExperience');
            $table->integer('champLevel');
            $table->integer('championId')->index();
            $table->string('championName');
            $table->integer('championTransform');
            $table->integer('consumablesPurchased');
            $table->integer('damageDealtToBuildings');
            $table->integer('damageDealtToObjectives');
            $table->integer('damageDealtToTurrets');
            $table->integer('damageSelfMitigated');
            $table->integer('deaths')->index();
            $table->integer('detectorWardsPlaced');
            $table->integer('doubleKills')->index();
            $table->integer('dragonKills');
            $table->boolean('firstBloodAssist');
            $table->boolean('firstBloodKill');
            $table->boolean('firstTowerAssist');
            $table->boolean('firstTowerKill');
            $table->boolean('gameEndedInEarlySurrender');
            $table->boolean('gameEndedInSurrender');
            $table->integer('goldEarned');
            $table->integer('goldSpent');
            $table->string('individualPosition')->index();
            $table->integer('inhibitorKills');
            $table->integer('inhibitorTakedowns');
            $table->integer('inhibitorsLost');
            $table->integer('item0');
            $table->integer('item1');
            $table->integer('item2');
            $table->integer('item3');
            $table->integer('item4');
            $table->integer('item5');
            $table->integer('item6');
            $table->integer('itemsPurchased');
            $table->integer('killingSprees');
            $table->integer('kills')->index();
            $table->string('lane')->index();
            $table->integer('largestCriticalStrike');
            $table->integer('largestKillingSpree');
            $table->integer('largestMultiKill')->index();
            $table->integer('longestTimeSpentLiving');
            $table->integer('magicDamageDealt');
            $table->integer('magicDamageDealtToChampions');
            $table->integer('magicDamageTaken');
            $table->integer('neutralMinionsKilled');
            $table->integer('nexusKills');
            $table->integer('nexusLost');
            $table->integer('nexusTakedowns');
            $table->integer('objectivesStolen');
            $table->integer('objectivesStolenAssists');
            $table->integer('pentaKills')->index();
            $table->integer('physicalDamageDealt');
            $table->integer('physicalDamageDealtToChampions');
            $table->integer('physicalDamageTaken');
            $table->integer('profileIcon');
            $table->string('puuid')->index();
            $table->integer('quadraKills')->index();
            $table->string('riotIdName')->nullable();
            $table->string('riotIdTagLine')->nullable();
            $table->string('role')->index();
            $table->integer('sightWardsBoughtInGame');
            $table->integer('spell1Casts');
            $table->integer('spell2Casts');
            $table->integer('spell3Casts');
            $table->integer('spell4Casts');
            $table->integer('summoner1Casts');
            $table->integer('summoner1Id');
            $table->integer('summoner2Casts');
            $table->integer('summoner2Id');
            $table->string('summonerId')->index();
            $table->integer('summonerLevel');
            $table->string('summonerName');
            $table->boolean('teamEarlySurrendered');
            $table->integer('team_id')->index();
            $table->string('teamPosition')->index();
            $table->integer('timeCCingOthers');
            $table->integer('timePlayed');
            $table->integer('totalDamageDealt');
            $table->integer('totalDamageDealtToChampions');
            $table->integer('totalDamageShieldedOnTeammates');
            $table->integer('totalDamageTaken');
            $table->integer('totalHeal');
            $table->integer('totalHealsOnTeammates');
            $table->integer('totalMinionsKilled');
            $table->integer('totalTimeCCDealt');
            $table->integer('totalTimeSpentDead');
            $table->integer('totalUnitsHealed');
            $table->integer('tripleKills');
            $table->integer('trueDamageDealt');
            $table->integer('trueDamageTaken');
            $table->integer('turretKills');
            $table->integer('turretTakedowns');
            $table->integer('turretsLost');
            $table->integer('unrealKills')->index();
            $table->integer('visionScore')->index();
            $table->integer('visionWardsBoughtInGame');
            $table->integer('wardsKilled');
            $table->integer('wardsPlaced');
            $table->boolean('win')->index();
            $table->integer('game_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
