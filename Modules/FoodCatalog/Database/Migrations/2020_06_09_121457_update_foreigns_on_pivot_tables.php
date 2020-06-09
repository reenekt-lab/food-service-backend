<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForeignsOnPivotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_category', function (Blueprint $table) {
            $table->dropForeign(['food_id']);
            $table->foreign('food_id')
                ->references('id')
                ->on('foods')
                ->onDelete('cascade');

            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
        Schema::table('food_tags', function (Blueprint $table) {
            $table->dropForeign(['food_id']);
            $table->foreign('food_id')
                ->references('id')
                ->on('foods')
                ->onDelete('cascade');

            $table->dropForeign(['tag_id']);
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_category', function (Blueprint $table) {
            $table->dropForeign(['food_id']);
            $table->foreign('food_id')->references('id')->on('foods');
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories');
        });
        Schema::table('food_category', function (Blueprint $table) {
            $table->dropForeign(['food_id']);
            $table->foreign('food_id')->references('id')->on('foods');
            $table->dropForeign(['tag_id']);
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }
}
