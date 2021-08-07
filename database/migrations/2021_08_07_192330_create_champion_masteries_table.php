<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampionMasteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champion_masteries', function (Blueprint $table) {
            $table->id();
            $table->integer('championId')->index();
            $table->integer('championLevel')->index();
            $table->bigInteger("championPoints");
            $table->bigInteger('championPointsSinceLastLevel');
            $table->integer('championPointsUntilNextLevel');
            $table->boolean("chestGranted");
            $table->integer("tokensEarned");
            $table->string("summonerId")->index();
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
        Schema::dropIfExists('champion_masteries');
    }
}
