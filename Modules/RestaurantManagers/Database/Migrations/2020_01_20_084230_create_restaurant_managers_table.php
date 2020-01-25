<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('surname')->comment('Фамилия');
            $table->string('first_name')->comment('Имя');
            $table->string('middle_name')->nullable()->comment('Отчество');
            $table->string('phone_number')->comment('Номер телефона');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('restaurant_id')->comment('ID ресторана менеджера');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->softDeletes();
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
        Schema::dropIfExists('restaurant_managers');
    }
}
