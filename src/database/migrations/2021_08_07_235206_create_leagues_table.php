<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('leagueId')->index();
            $table->string('queueType')->index();
            $table->string('tier')->index();
            $table->string('rank')->index();
            $table->string('summonerId');
            $table->string('summonerName');
            $table->integer('leaguePoints');
            $table->integer('wins');
            $table->integer('losses');
            $table->boolean('veteran');
            $table->integer('inactive');
            $table->integer('freshBlood');
            $table->integer('hotStreak');
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
        Schema::dropIfExists('leagues');
    }
}
