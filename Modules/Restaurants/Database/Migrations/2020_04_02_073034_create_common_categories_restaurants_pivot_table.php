<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonCategoriesRestaurantsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_categories_restaurants_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('restaurant_id')->comment('ID ресторана');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

            $table->unsignedBigInteger('common_category_id')->comment('ID общей категории ресторана');
            $table->foreign('common_category_id')->references('id')->on('common_categories');

            $table->primary(['restaurant_id', 'common_category_id'], 'common_categories_restaurants_pivot_idx_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common_categories_restaurants_pivot');
    }
}
