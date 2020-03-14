<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAdminFieldToRestaurantManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_managers', function (Blueprint $table) {
            $table->boolean('is_admin')
                ->default(false)
                ->comment('Является ли менеджер владельцем (администратором) ресторана')
                ->after('restaurant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_managers', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
}
