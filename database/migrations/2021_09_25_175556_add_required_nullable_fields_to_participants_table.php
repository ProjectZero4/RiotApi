<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequiredNullableFieldsToParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participants', function (Blueprint $table) {
            //
            $table->integer('inhibitorTakedowns')->nullable()->change();
            $table->integer('inhibitorKills')->nullable()->change();
            $table->integer('dragonKills')->nullable()->change();
            $table->integer('baronKills')->nullable()->change();
            $table->integer('nexusKills')->nullable()->change();
            $table->integer('nexusTakedowns')->nullable()->change();
            $table->integer('nexusLost')->nullable()->change();
            $table->integer('turretTakedowns')->nullable()->change();
            $table->integer('turretKills')->nullable()->change();
            $table->integer('turretsLost')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participants', function (Blueprint $table) {
            //
        });
    }
}
