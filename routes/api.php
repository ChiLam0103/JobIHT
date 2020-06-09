<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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


Route::namespace('Api\v1')->group(function () {

    Route::group(['prefix' => 'v1'], function () {

        //menu
        Route::group(['prefix' => 'menu'], function () {
            Route::get('list-header', 'ApiController@listProMenuGroup');
            Route::get('list-sidebar', 'ApiController@listProMenu');

        });
        //data basic
        Route::group(['prefix' => 'data-basic'], function () {
            Route::get('company', 'ApiController@getInfoCompany');
            Route::get('list-customer', 'ApiController@listCustomer');
            Route::get('list-staff-customs', 'ApiController@listStaffCustoms');
            Route::get('list-type-cost', 'ApiController@listTypeCost');
            Route::get('list-carriers', 'ApiController@listCarriers');
            Route::get('list-agent', 'ApiController@listAgent');
            Route::get('list-branch', 'ApiController@listBranch');
            Route::get('list-garage', 'ApiController@listGarage');

        });
    });
});