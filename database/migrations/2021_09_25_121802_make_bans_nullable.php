<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeBansNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->integer('ban_1')->nullable()->change();
            $table->integer('ban_2')->nullable()->change();
            $table->integer('ban_3')->nullable()->change();
            $table->integer('ban_4')->nullable()->change();
            $table->integer('ban_5')->nullable()->change();
            $table->integer('baron_first')->nullable()->change();
            $table->integer('dragon_first')->nullable()->change();
            $table->integer('inhibitor_first')->nullable()->change();
            $table->integer('rift_herald_first')->nullable()->change();
            $table->integer('baron_kills')->nullable()->change();
            $table->integer('dragon_kills')->nullable()->change();
            $table->integer('inhibitor_kills')->nullable()->change();
            $table->integer('rift_herald_kills')->nullable()->change();
            $table->integer('tower_first')->nullable()->change();
            $table->integer('tower_kills')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
