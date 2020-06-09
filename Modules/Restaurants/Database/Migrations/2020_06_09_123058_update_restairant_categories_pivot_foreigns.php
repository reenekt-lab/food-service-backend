<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRestairantCategoriesPivotForeigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_categories_restaurants_pivot', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->foreign('restaurant_id')
                ->references('id')
                ->on('restaurants')
                ->onDelete('cascade');
            $table->dropForeign(['common_category_id']);
            $table->foreign('common_category_id')
                ->references('id')
                ->on('common_categories')
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
        Schema::table('common_categories_restaurants_pivot', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->dropForeign(['common_category_id']);
            $table->foreign('common_category_id')->references('id')->on('common_categories');
        });
    }
}
