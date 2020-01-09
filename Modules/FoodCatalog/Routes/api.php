<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('food/{food}/categories/attach/{category}', 'CategoryController@attach')->name('food.categories.attach');
Route::post('food/{food}/categories/detach/{category}', 'CategoryController@detach')->name('food.categories.detach');
Route::post('food/{food}/tags/attach/{tag}', 'TagController@attach')->name('food.tags.attach');
Route::post('food/{food}/tags/detach/{tag}', 'TagController@detach')->name('food.tags.detach');

Route::apiResources([
    'categories' => 'CategoryController',
    'tags' => 'TagController',
]);
