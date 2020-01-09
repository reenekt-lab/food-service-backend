<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('Название категории');
            $table->text('description')->nullable()->comment('Описание категории');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID родительской категории');
            $table->foreign('parent_id')->references('id')->on('categories');
            $table->timestamps();
        });

        DB::table('categories')->insert([
            'name' => 'Другое',
            'description' => 'Категория для блюд, не вошедших в другие категории',
        ]);
        DB::table('categories')->insert([
            'name' => 'Бургеры',
            'description' => 'Все виды бургеров',
        ]);

        $drinks_id = DB::table('categories')->insertGetId([
            'name' => 'Напитки',
            'description' => 'Все виды напитков',
        ]);
        $hot_drinks_id = DB::table('categories')->insertGetId([
            'name' => 'Горячие напитки',
            'description' => 'Горячие напитки',
            'parent_id' => $drinks_id,
        ]);
        DB::table('categories')->insert([
            'name' => 'Кофе',
            'description' => 'Кофе',
            'parent_id' => $hot_drinks_id,
        ]);
        DB::table('categories')->insert([
            'name' => 'Чай',
            'description' => 'Чай',
            'parent_id' => $hot_drinks_id,
        ]);

        DB::table('categories')->insert([
            'name' => 'Роллы',
            'description' => 'Все виды роллов',
        ]);
        DB::table('categories')->insert([
            'name' => 'Суши',
            'description' => 'Суши',
        ]);
        DB::table('categories')->insert([
            'name' => 'Пицца',
            'description' => 'Пицца',
        ]);
        DB::table('categories')->insert([
            'name' => 'Десерты',
            'description' => 'Все виды десертов',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
