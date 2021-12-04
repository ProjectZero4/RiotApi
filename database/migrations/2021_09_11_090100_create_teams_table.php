<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->integer('teamId');
            $table->boolean('win')->index();
            $table->string('ban_1')->index();
            $table->string('ban_2')->index();
            $table->string('ban_3')->index();
            $table->string('ban_4')->index();
            $table->string('ban_5')->index();
            $table->boolean('baron_first');
            $table->boolean('champion_first');
            $table->boolean('dragon_first');
            $table->boolean('inhibitor_first');
            $table->boolean('rift_herald_first');
            $table->boolean('tower_first');
            $table->integer('baron_kills');
            $table->integer('champion_kills');
            $table->integer('dragon_kills');
            $table->integer('inhibitor_kills');
            $table->integer('rift_herald_kills');
            $table->integer('tower_kills');
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
        Schema::dropIfExists('teams');
    }
}
