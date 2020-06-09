<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCouriersForeignKeyRestriction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->foreign('restaurant_id')
                ->references('id')
                ->on('restaurants')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
        });
    }
}
