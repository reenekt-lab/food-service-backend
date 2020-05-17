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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthJWT\RegisterController@register');
    Route::post('login', 'AuthJWT\LoginController@login');
    Route::post('logout', 'AuthJWT\LoginController@logout');
    Route::post('refresh', 'AuthJWT\LoginController@refresh');
    Route::get('me', 'AuthJWT\LoginController@me');
});
