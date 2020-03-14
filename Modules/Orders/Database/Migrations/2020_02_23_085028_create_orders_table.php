<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('customer_id')->comment('ID покупателя');
            $table->foreign('customer_id')->references('id')->on('customers');

            // text or json. text - universal, json - more powerful.
            $table->text('content')->comment('Содержимое заказа (id товаров и их количество)');

            $table->unsignedBigInteger('restaurant_id')->comment('ID ресторана, у которого сделан заказ');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

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
        Schema::dropIfExists('orders');
    }
}
