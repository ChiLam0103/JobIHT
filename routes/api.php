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
        //III.file manager(quan ly ho so)
        Route::group(['prefix' => 'file'], function () {
            //phieu theo doi
            Route::group(['prefix' => 'job-start'], function () {
                Route::get('/', 'JobStartController@list');
                Route::get('search/type={type}&value={value}', 'JobStartController@search');
                Route::get('not-created', 'JobStartController@listNotCreatedOrder');
                Route::get('des/{id}', 'JobStartController@des');
                Route::post('add', 'JobStartController@add');
                Route::post('edit', 'JobStartController@edit');
                Route::get('remove-check/{id}', 'JobStartController@removeCheck');
                Route::post('remove', 'JobStartController@remove');
            });
            Route::group(['prefix' => 'job-order'], function () {
                Route::get('/', 'JobOrderController@list');
                Route::get('search/type={type}&value={value}', 'JobOrderController@search');
                Route::get('des/job={id}&type={TYPE}', 'JobOrderController@des');
                Route::post('add', 'JobOrderController@add');
                Route::post('add-d', 'JobOrderController@addJobD');
                Route::post('edit', 'JobOrderController@edit');
                Route::post('edit-d', 'JobOrderController@editJobD');
                Route::get('remove-check/{id}', 'JobOrderController@removeCheck');
                Route::post('remove', 'JobOrderController@remove');
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
        //IV. payment manager(quan ly thu chi)
        Route::group(['prefix' => 'payment'], function () {
            //1.phieu chi tam ung
            Route::group(['prefix' => 'lender'], function () {
                Route::get('/', 'LenderController@list');
                Route::get('list-advance', 'LenderController@listAdvance');
                Route::get('search/type={type}&value={value}', 'LenderController@search');
                Route::get('des/{id}', 'LenderController@des');
                Route::post('add', 'LenderController@add');
                Route::post('edit', 'LenderController@edit');
                Route::post('add-d', 'LenderController@addD');
                Route::post('edit-d', 'LenderController@editD');
                Route::post('remove', 'LenderController@remove');
            });
            //3.yeu cau thanh toan
            Route::group(['prefix' => 'debit-note'], function () {
                Route::get('/', 'DebitNoteController@list');
                Route::get('search/type={type}&value={value}', 'DebitNoteController@search');
                Route::get('not-created', 'DebitNoteController@listNotCreated');
                Route::get('des/{id}', 'DebitNoteController@des');
                Route::get('des-job-not-created/{id}', 'DebitNoteController@desJobNotCreated');
                Route::post('add', 'DebitNoteController@add');
                Route::post('edit', 'DebitNoteController@edit');
                Route::get('remove-check/{id}', 'DebitNoteController@removeCheck');
                Route::post('remove', 'DebitNoteContro ller@remove');

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
                Route::get('des-month/type={FEE_TYPE}&value={BOAT_FEE_MONTH}', 'BoatFeeController@desMonth');
                Route::post('edit', 'BoatFeeController@edit');
            });
        });

        //print
        Route::group(['prefix' => 'print'], function () {
            //II. báo biểu hồ sơ
            Route::group(['prefix' => 'file'], function () {
                // 1.in phieu theo doi
                Route::group(['prefix' => 'job-start'], function () {
                    Route::get('fromjob={fromjob}&tojob={tojob}', 'PrintFileController@jobStart');
                });
                //2.in job order
                Route::group(['prefix' => 'job-order'], function () {
                    Route::get('jobno={jobno}', 'PrintFileController@jobOrder');
                    Route::get('boat/jobno={jobno}', 'PrintFileController@jobOrderBoat');
                    Route::get('custno={id}&jobno={jobno}', 'PrintFileController@jobOrderCustomer');
                    Route::get('custno={id}', 'PrintFileController@getJobOrderCustomer');
                    Route::get('fromdate={fromdate}&todate={todate}', 'PrintFileController@jobOrder_Date');
                });
                //3.bao bieu refund
                Route::group(['prefix' => 'refund'], function () {
                    //1.hang tau, 2.khach hang, 3.dai ly
                    Route::get('type={type}&id={id}&jobno={jobno}&fromdate={fromdate}&todate={todate}', 'PrintFileController@refund');
                });
                //4.thong ke job order
                Route::group(['prefix' => 'statistic'], function () {
                    //thống kê tạo job
                    Route::get('created-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'PrintFileController@statisticCreatedJob');
                    Route::get('user-import-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'PrintFileController@statisticUserImportJob');
                });
            });
            //IV. payment manager(quan ly thu chi)
            Route::group(['prefix' => 'payment'], function () {
                //1.phieu chi tam ung
                Route::group(['prefix' => 'advance-payment'], function () {
                    //1.phieu chi
                    Route::get('fromadvance={fromadvance}&toadvance={toadvance}', 'PrintPaymentController@advancePayment');//1.1 phieu chi
                    Route::get('replenishment/fromadvance={fromadvance}&toadvance={toadvance}', 'PrintPaymentController@replenishment');//1.2 phieu bu
                    Route::get('withdrawal/fromadvance={fromadvance}&toadvance={toadvance}', 'PrintPaymentController@withdrawal');//1.3 phieu tra
                    // Route::get('without-job/fromdate={fromdate}&todate={todate}', 'PrintPaymentController@withoutJob_AdvancePayment');//1.4 phieu tam ung chua mo job order
                    Route::get('advance/fromadvance={fromadvance}&toadvance={toadvance}', 'PrintPaymentController@advance');//1.5 phieu tam ung
                    Route::get('replenishment-withdrawal/moneyused={moneyused}&fromadvance={fromadvance}&toadvance={toadvance}', 'PrintPaymentController@replenishmentWithdrawal');//1.6 phieu bu-tra tam ung

                    Route::get('statistical-advance/fromdate={fromdate}&todate={todate}', 'PrintPaymentController@statisticalAdvance');//1.7 thống kê phiếu tạm ứng
                    Route::get('statistical-replenishment/fromdate={fromdate}&todate={todate}', 'PrintPaymentController@statisticalReplenishment');//1.8 thống kê phiếu bù
                    Route::get('statistical-withdrawal/fromdate={fromdate}&todate={todate}', 'PrintPaymentController@statisticalWithdrawal');//1.9 thống kê phiếu trả
                    Route::get('statistical-direct/fromdate={fromdate}&todate={todate}', 'PrintPaymentController@statisticalDirect');//1.10 thống kê phiếu chi trực tiếp
                    Route::get('statistical-replenishment-withdrawal/fromdate={fromdate}&todate={todate}', 'PrintPaymentController@statisticalReplenishmentWithdrawal');//1.11 thống kê phiếu bù & trả


                });
            });
        });
    });
});
