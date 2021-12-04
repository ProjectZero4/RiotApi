<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummonersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('summoners', function (Blueprint $table) {
            $table->id('internalKey');
            $table->string('id')->index();
            $table->string('accountId')->index();
            $table->string('puuid')->index();
            $table->string('name');
            $table->integer('profileIconId');
            $table->bigInteger('revisionDate');
            $table->integer('summonerLevel');
            $table->string('nameKey')->index();
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
        Schema::dropIfExists('summoners');
    }
}
