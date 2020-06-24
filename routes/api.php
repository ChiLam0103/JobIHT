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
        //user
        Route::group(['prefix' => 'user'], function () {
            Route::post('login', 'UserController@login');
            Route::get('list', 'UserController@listUser');
            Route::get('get/{USER_NO?}', 'UserController@getUser');
            Route::get('list-menu-pro', 'UserController@listMenuPro');
        });
        //pay
        Route::group(['prefix' => 'pay'], function () {
            Route::get('list-type', 'PayController@listPayType');
            Route::get('list-note', 'PayController@listPayNote');
        });
        //job
        Route::group(['prefix' => 'job'], function () {
            Route::get('list-start', 'JobController@listJobStart');//phieu theo doi
            Route::post('des/{id?}', 'JobController@desJob');
            Route::post('add', 'JobController@addJob');
            Route::post('edit', 'JobController@editJob');
            Route::post('remove', 'JobController@deleteJob');
        });
        //menu
        Route::group(['prefix' => 'menu'], function () {
            Route::get('list-header', 'MenuController@listMenuGroup');
            Route::get('list-sidebar', 'MenuController@listMenu');
        });
        //data basic
        Route::group(['prefix' => 'data-basic'], function () {
            //company
            Route::group(['prefix' => 'company'], function () {
                Route::get('/', 'CompanyController@getInfoCompany');
                Route::post('add', 'CompanyController@addCompany');
                Route::post('edit', 'CompanyController@editCompany');
                Route::post('remove', 'CompanyController@deleteCompany');
            });
           
            //customer
            Route::group(['prefix' => 'customer'], function () {
                Route::get('/', 'CustomerController@listCustomer');
                Route::get('des/{id}', 'CustomerController@desCustomer');
                Route::post('add', 'CustomerController@addCustomer');
                Route::post('edit', 'CustomerController@editCustomer');
                Route::post('remove', 'CustomerController@deleteCustomer');
            });
           
            //staff-customs(nhan vien hai quan)
            Route::group(['prefix' => 'staff-customs'], function () {
                Route::get('/', 'StaffCustomerController@listStaffCustoms');

            });
            Route::group(['prefix' => 'type-cost'], function () {
                Route::get('/', 'TypeCostController@listTypeCost');

            });
            Route::group(['prefix' => 'carriers'], function () {
                Route::get('/', 'CarriersController@listCarriers');

            });
            Route::group(['prefix' => 'agent'], function () {
                Route::get('/', 'AgentController@listAgent');

            });
            Route::group(['prefix' => 'branch'], function () {
                Route::get('/', 'BranchController@listBranch');

            });
            Route::group(['prefix' => 'garage'], function () {
                Route::get('/','GarageController@listBranch');

            });
           
        });
    });
});
