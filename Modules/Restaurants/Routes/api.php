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

//Route::middleware('auth:api')->get('/restaurants', function (Request $request) {
//    return $request->user();
//});

Route::get('restaurants/{restaurant}/food', 'FoodController@listByRestaurant')->name('restaurants.listByRestaurant');

Route::apiResources([
    'restaurants' => 'RestaurantsController',
    'food' => 'FoodController',
]);

Route::apiResource('common-category', 'CommonCategoryController')->only([
    'index',
    'show',
]);
