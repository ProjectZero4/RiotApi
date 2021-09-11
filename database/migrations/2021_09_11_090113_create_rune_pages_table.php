<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRunePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rune_pages', function (Blueprint $table) {
            $table->id();
            $table->integer('primary_style_id')->index();
            $table->integer('primary_selection_1_id')->index();
            $table->integer('primary_selection_2_id')->index();
            $table->integer('primary_selection_3_id')->index();
            $table->integer('primary_selection_4_id')->index();
            $table->integer('secondary_style_id')->index();
            $table->integer('secondary_selection_1_id')->index();
            $table->integer('secondary_selection_2_id')->index();
            $table->integer('defense_id')->index();
            $table->integer('flex_id')->index();
            $table->integer('offense_id')->index();
            $table->integer('participant_id')->index();
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
        Schema::dropIfExists('rune_pages');
    }
}
