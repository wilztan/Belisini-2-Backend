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


Route::get('/product','ProductController@index');

Route::post('/login','UserController@accessToken');

Route::post('/register','UserController@store');

//show product
Route::get('/product/{name}/{id}','ProductController@showProduct');



Route::group(['middleware' => ['web','auth:api']], function()
{
	Route::resource('/item','ProductController');

	Route::resource('users','UserController');

	Route::get('profile','UserController@userInfo');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
