<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champions', function (Blueprint $table) {
            $table->id("internalKey");
            $table->string("id")->index();
            $table->integer("key")->index();
            $table->string("name")->index();
            $table->string("title");
            $table->json("image");
            $table->json("skins");
            $table->text("lore");
            $table->text("blurb");
            $table->json("allytips");
            $table->json("enemytips");
            $table->string("partype")->index();
            $table->json("info");
            $table->json("stats");
            $table->json("spells");
            $table->json("passive");
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
        Schema::dropIfExists('champions');
    }
}
