<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payments\Entities\Bill;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            // maybe use in future
//            $table->morphs('buyer'); // тот кто платит по счету (the one who will pay)
//            $table->morphs('seller'); // тот кому придет оплата (the one who will get payment)
//            $table->morphs('target'); // что оплачивается (what will be paid for)

            $table->unsignedBigInteger('customer_id')->comment('ID покупателя');
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBigInteger('restaurant_id')->comment('ID ресторана, которому нужно заплатить за заказ');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

            $table->unsignedBigInteger('order_id')->comment('ID заказа');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedDecimal('amount', 8, 2)->comment('Сумма к оплате');

            $table->enum('status', [
                Bill::STATUS_NOT_PAID,
                Bill::STATUS_PAID,
                Bill::STATUS_NOT_FULLY_PAID,
            ])->default( Bill::STATUS_NOT_PAID)->comment('Статус оплаты');

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
        Schema::dropIfExists('bills');
    }
}
