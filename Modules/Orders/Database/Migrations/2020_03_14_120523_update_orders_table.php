<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Orders\Entities\Order;

class UpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('courier_id')->nullable()->comment('ID курьера, которы должен доставить заказ');
            $table->foreign('courier_id')->references('id')->on('couriers');

            // enum cannot be changed later. see https://laravel.com/docs/6.x/migrations#modifying-columns for details
//            $table->enum('status', [ /* ... */]);

            $table->string('status', 64)->default(Order::STATUS_CREATED)->comment('Статус заказа');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('courier_id');
            $table->dropColumn('status');
        });
    }
}
