<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gameCreation')->index();
            $table->integer('gameDuration')->index();
            $table->bigInteger('gameId')->index();
            $table->string('gameMode');
            $table->string('gameName');
            $table->bigInteger('gameStartTimestamp');
            $table->string('gameType');
            $table->string('gameVersion');
            $table->integer('mapId')->index();
            $table->string('platformId')->index();
            $table->integer('queueId')->index();
            $table->string('tournamentCode')->nullable();
            $table->string('matchId')->index();
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
        Schema::dropIfExists('games');
    }
}
