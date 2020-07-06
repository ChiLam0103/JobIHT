<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('login', 'Api\ApiController@login');
//list phieu theo doi
// Route::post('list-job', 'Api\ApiController@listJob');
// //list pay
// Route::get('list-pay-type', 'Api\ApiController@listPayType');
// Route::get('list-pay-note', 'Api\ApiController@listPayNote');
// //chi tiet
// Route::post('des-job/{id?}', 'Api\ApiController@desJob');

//them job_order_d
// Route::post('create', 'Api\ApiController@create');
// Route::post('edit', 'Api\ApiController@edit');
// Route::post('remove', 'Api\ApiController@remove');


Route::namespace('Api\v1')->group(function () {

    Route::group(['prefix' => 'v1'], function () {
        //user
        Route::group(['prefix' => 'user'], function () {
            Route::post('login', 'UserController@login');
        });
        //menu
        Route::group(['prefix' => 'menu'], function () {
            Route::get('list-header', 'MenuController@listMenuGroup');
            Route::get('list-sidebar', 'MenuController@listMenu');
        });

        //I.data basic
        Route::group(['prefix' => 'data-basic'], function () {
            Route::group(['prefix' => 'company'], function () {
                Route::get('/', 'CompanyController@list');
                Route::post('add', 'CompanyController@add');
                Route::post('edit', 'CompanyController@edit');
                Route::post('remove', 'CompanyController@remove');
            });
            Route::group(['prefix' => 'customer'], function () {
                Route::get('/', 'CustomerController@list');
                Route::get('des/{id}', 'CustomerController@des');
                Route::post('add', 'CustomerController@add');
                Route::post('edit', 'CustomerController@edit');
                Route::post('remove', 'CustomerController@remove');
            });
            //staff-customs(nhan vien hai quan)
            Route::group(['prefix' => 'staff-customs'], function () {
                Route::get('/', 'StaffCustomerController@list');
                Route::get('des/{id}', 'StaffCustomerController@des');
                Route::post('add', 'StaffCustomerController@add');
                Route::post('edit', 'StaffCustomerController@edit');
                Route::post('remove', 'StaffCustomerController@remove');
            });
            Route::group(['prefix' => 'type-cost'], function () {
                Route::get('/', 'TypeCostController@list');
                Route::post('add', 'TypeCostController@add');
                Route::post('edit', 'TypeCostController@edit');
                Route::post('remove', 'TypeCostController@remove');
            });
            Route::group(['prefix' => 'carriers'], function () {
                Route::get('/', 'CarriersController@list');
                Route::get('des/{id}', 'CarriersController@des');
                Route::post('add', 'CarriersController@add');
                Route::post('edit', 'CarriersController@edit');
                Route::post('remove', 'CarriersController@remove');
            });
            Route::group(['prefix' => 'agent'], function () {
                Route::get('/', 'AgentController@list');
                Route::get('des/{id}', 'AgentController@des');
                Route::post('add', 'AgentController@add');
                Route::post('edit', 'AgentController@edit');
                Route::post('remove', 'AgentController@remove');
            });
            Route::group(['prefix' => 'branch'], function () {
                Route::get('/', 'BranchController@list');
            });
            Route::group(['prefix' => 'garage'], function () {
                Route::get('/', 'GarageController@list');
                Route::get('des/{id}', 'GarageController@des');
                Route::post('add', 'GarageController@add');
                Route::post('edit', 'GarageController@edit');
                Route::post('remove', 'GarageController@remove');
            });
        });
        //II.system manager 
        Route::group(['prefix' => 'system'], function () {
            Route::post('login', 'UserController@login');
            //info
            Route::group(['prefix' => 'user'], function () {
                Route::get('/', 'UserController@list');
                Route::get('des/{id}', 'UserController@des');
                Route::post('add', 'UserController@add');
                Route::post('edit', 'UserController@edit');
                Route::post('remove', 'UserController@remove');
            });
            //phan quyen
            Route::group(['prefix' => 'permission'], function () {
                Route::get('/', 'PermissionController@list');
                Route::get('des/{USER_NO?}', 'PermissionController@des');
                Route::post('edit', 'PermissionController@edit');
            });
        });
        //III.file manager 
        Route::group(['prefix' => 'file'], function () {
            //phieu theo doi
            Route::group(['prefix' => 'job-start'], function () {
                Route::get('/', 'JobStartController@list');
                Route::get('not-created-order', 'JobStartController@listNotCreatedOrder');
                Route::get('des/{id}', 'JobStartController@des');
                Route::post('add', 'JobStartController@add');
                Route::post('edit', 'JobStartController@edit');
                Route::get('remove-check/{id}', 'JobStartController@removeCheck');
                Route::post('remove', 'JobStartController@remove');
            });
            Route::group(['prefix' => 'job-order'], function () {
                Route::get('/', 'JobOrderController@list');
                Route::get('des/{id}', 'JobOrderController@des');
                Route::post('add', 'JobOrderController@add');
                Route::post('add-d', 'JobOrderController@addOrderD');
                Route::post('edit', 'JobOrderController@edit');
                Route::post('edit-d', 'JobOrderController@editOrderD');
                Route::get('remove-check/{id}', 'JobOrderController@removeCheck');
                Route::post('remove', 'JobOrderController@remove');
                Route::post('remove-d', 'JobOrderController@removeOrderD');
               
            });
            Route::group(['prefix' => 'approved'], function () {
                Route::get('list-pending', 'JobOrderController@listPending');
                Route::get('list-approved', 'JobOrderController@listApproved');
                Route::post('/', 'JobOrderController@approved');
               
            });
            //pay
            Route::group(['prefix' => 'pay'], function () {
                Route::get('list-type', 'PayController@listPayType');
                Route::get('list-note', 'PayController@listPayNote');
            });
        });
        //IV. payment manager
        Route::group(['prefix' => 'payment'], function () {
            //1.quan ly thu chi
            Route::group(['prefix' => 'advance-slip'], function () { 
                Route::get('/', 'LenderController@list');
                Route::get('des/{id}', 'LenderController@des');
                Route::post('add', 'LenderController@add');
                Route::post('edit', 'LenderController@edit');
                Route::post('remove', 'LenderController@remove');
            });
             //1.yeu cau thanh toan
             Route::group(['prefix' => 'debit-note'], function () { 

             });
        });
    });
});
