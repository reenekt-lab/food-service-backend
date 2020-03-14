<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->bigIncrements('id');

            // от кого
            $table->morphs('from');
            // кому
            $table->morphs('to');
            // на что (счет или другое)
            $table->morphs('for');

            $table->unsignedDecimal('amount', 8, 2)->comment('Сумма платежа');

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
        Schema::dropIfExists('payment_histories');
    }
}
