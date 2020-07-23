<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin: *');
//Access-Control-Allow-Origin: *
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::namespace('Api\v1')->group(function () {
    //web
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
                Route::get('/type={type}', 'CustomerController@list');
                Route::get('des/id={id}/type={type}', 'CustomerController@des');
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
            Route::group(['prefix' => 'branch'], function () {
                Route::get('/', 'BranchController@list');
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
                Route::get('search/q={request}', 'JobStartController@search');
                Route::get('not-created', 'JobStartController@listNotCreatedOrder');
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
                Route::post('edit', 'JobOrderController@edit');
                Route::get('remove-check/{id}', 'JobOrderController@removeCheck');
                Route::post('remove', 'JobOrderController@remove');

                Route::post('add-d', 'JobOrderController@addOrderD');
                Route::post('edit-d', 'JobOrderController@editOrderD');
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
            //3.yeu cau thanh toan
            Route::group(['prefix' => 'debit-note'], function () {
                Route::get('/', 'DebitNoteController@list');
                Route::get('not-created', 'DebitNoteController@listNotCreated');
                Route::get('des/{id}', 'DebitNoteController@des');
                Route::post('add', 'DebitNoteController@add');
                Route::post('edit', 'DebitNoteController@edit');
                Route::get('remove-check/{id}', 'DebitNoteController@removeCheck');
                Route::post('remove', 'DebitNoteController@remove');

                Route::post('add-d', 'DebitNoteController@addDebitD');
                Route::post('edit-d', 'DebitNoteController@editDebitD');
                Route::post('remove-d', 'DebitNoteController@removeDebitD');
            });
            //4. duyet thanh toan khach hang
            Route::group(['prefix' => 'paid-debit'], function () {
                Route::get('list-pending', 'DebitNoteController@listPending');
                Route::get('list-paid', 'DebitNoteController@listPaid');
                Route::post('change', 'DebitNoteController@change');
            });
            //6. bang kiem tra du lieu
            Route::group(['prefix' => 'check-data'], function () {
                Route::get('/', 'DebitNoteController@checkData');
            });
             //8. chi phi tien tau/cuoc cont
             Route::group(['prefix' => 'boat-fee'], function () {
                Route::get('list-boat-month-m', 'BoatFeeController@listBoatMonthM');
                Route::get('list-fee-month-m', 'BoatFeeController@listFeeMonthM');
                Route::get('des-month', 'BoatFeeController@desMonth');
            });
        });
        //print
        Route::group(['prefix' => 'print'], function () {
            Route::group(['prefix' => 'job-start'], function () {
                Route::get('jobno={id}', 'PrintController@jobStart');
            });
            Route::group(['prefix' => 'job-order'], function () {
                Route::get('jobno={id}', 'PrintController@jobOrder');
                // Route::get('custno={id}', 'PrintController@jobOrder_Customer');
                // Route::get('todate={todate}/fromdate={fromdate}', 'PrintController@jobOrder_Date');
            });
            Route::group(['prefix' => 'refund'], function () {
                //1.hang tau, 2.khach hang, 3.dai ly
                Route::get('type={type}/id={id}/jobno={jobno}/todate={todate}/fromdate={fromdate}', 'PrintController@refund');
            });
        });
    });

});
