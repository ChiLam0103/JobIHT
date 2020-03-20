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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'Api\ApiController@login');
//list phieu theo doi
Route::post('list-job', 'Api\ApiController@listJob');
//list pay
Route::get('list-pay-type', 'Api\ApiController@listPayType');
Route::get('list-pay-note', 'Api\ApiController@listPayNote');
//chi tiet
Route::post('des-job/{id?}', 'Api\ApiController@desJob');

//them job_order_d
Route::post('create', 'Api\ApiController@create');
Route::post('edit', 'Api\ApiController@edit');
Route::post('remove', 'Api\ApiController@remove');