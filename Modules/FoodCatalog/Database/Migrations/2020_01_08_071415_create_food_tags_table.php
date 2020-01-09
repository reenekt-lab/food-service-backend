<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('food_id')->comment('ID блюда/напитка');
            $table->foreign('food_id')->references('id')->on('foods');
            $table->unsignedBigInteger('tag_id')->comment('ID тега');
            $table->foreign('tag_id')->references('id')->on('tags');
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
        Schema::dropIfExists('food_tags');
    }
}
