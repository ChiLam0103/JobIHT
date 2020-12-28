<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// header('Access-Control-Allow-Origin: *');
// //Access-Control-Allow-Origin: *
// header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
// header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::namespace('Api\v1')->group(function () {
    //web
    Route::group(['prefix' => 'v1'], function () {
        //user
        Route::group(['middleware' =>  'cors', 'prefix' => 'user'], function () {
            Route::post('login', 'UserController@login');
        });
        //menu
        Route::group(['prefix' => 'menu'], function () {
            Route::get('list-header', 'MenuController@listMenuGroup');
            Route::get('list-sidebar', 'MenuController@listMenu');
        });
        //I.data basic (du lieu co ban)
        Route::group(['prefix' => 'data-basic'], function () {
            Route::group(['prefix' => 'company'], function () {
                Route::get('/', 'CompanyController@list');
                Route::post('add', 'CompanyController@add');
                Route::post('edit', 'CompanyController@edit');
                Route::post('remove', 'CompanyController@remove');
            });
            Route::group(['prefix' => 'bank'], function () {
                Route::get('/', 'BankController@list');
                Route::post('add', 'BankController@add');
                Route::post('edit', 'BankController@edit');
                Route::post('remove', 'BankController@remove');
            });
            Route::group(['prefix' => 'customer'], function () {
                Route::get('/type={type}', 'CustomerController@list');
                Route::get('/type={type}/page={page}', 'CustomerController@listPage');
                Route::get('des/id={id}/type={type}', 'CustomerController@des');
                Route::get('search/group={group}&type={type}&value={value}&page={page}', 'CustomerController@search');
                Route::post('add', 'CustomerController@add');
                Route::post('edit', 'CustomerController@edit');
                Route::post('remove', 'CustomerController@remove');
            });
            //staff-customs(nhan vien hai quan)
            Route::group(['prefix' => 'staff-customs'], function () {
                Route::get('/', 'StaffCustomerController@list');
                Route::get('page={page}', 'StaffCustomerController@listPage');
                Route::get('des/{id}', 'StaffCustomerController@des');
                Route::post('add', 'StaffCustomerController@add');
                Route::post('edit', 'StaffCustomerController@edit');
                Route::post('remove', 'StaffCustomerController@remove');
            });
            Route::group(['prefix' => 'type-cost'], function () {
                Route::get('/', 'TypeCostController@list');
                Route::get('page={page}', 'TypeCostController@listPage');
                Route::get('des/{id}', 'TypeCostController@des');
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
                Route::get('page={page}', 'JobStartController@listPage');
                Route::get('', 'JobStartController@list');
                Route::get('search/type={type}&value={value}&page={page}', 'JobStartController@search');
                Route::get('not-created', 'JobStartController@listNotCreatedOrder');
                Route::get('des/{id}', 'JobStartController@des');
                Route::post('add', 'JobStartController@add');
                Route::post('edit', 'JobStartController@edit');
                Route::post('remove', 'JobStartController@remove');
            });
            Route::group(['prefix' => 'job-order'], function () {
                Route::get('/', 'JobOrderController@list');
                Route::get('page={page}', 'JobOrderController@listPage');
                Route::get('search/type={type}&value={value}&page={page}', 'JobOrderController@search');
                Route::get('des/job={id}&type={TYPE}', 'JobOrderController@des');
                Route::post('add', 'JobOrderController@add');
                Route::post('add-d', 'JobOrderController@addJobD');
                Route::post('edit', 'JobOrderController@edit');
                Route::post('edit-d', 'JobOrderController@editJobD');
                Route::post('remove', 'JobOrderController@remove');
                Route::post('remove-d', 'JobOrderController@removeJobD');
            });
            Route::group(['prefix' => 'approved'], function () {
                Route::get('list-pending/page={page}', 'JobOrderController@listPending');
                Route::get('list-approved/page={page}', 'JobOrderController@listApproved');
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
                Route::get('', 'LenderController@list');
                Route::get('page={page}', 'LenderController@listpage');
                Route::get('job-not-created', 'LenderController@listJobNotCreated');
                Route::get('list-advance', 'LenderController@listAdvance');
                Route::get('search/type={type}&value={value}&page={page}', 'LenderController@search');
                Route::get('des/{id}', 'LenderController@des');
                Route::post('add', 'LenderController@add');
                Route::post('edit', 'LenderController@edit');
                Route::post('add-d', 'LenderController@addD');
                Route::post('edit-d', 'LenderController@editD');
                Route::post('remove', 'LenderController@remove');
                Route::post('remove-d', 'LenderController@removeD');
            });
            //3.yeu cau thanh toan
            Route::group(['prefix' => 'debit-note'], function () {
                Route::get('/', 'DebitNoteController@list');
                Route::get('/custno={custno}', 'DebitNoteController@listCustomer'); //print
                Route::post('list-cust-job', 'DebitNoteController@postListCustomerJob'); //print
                Route::get('/list-job-has-d', 'DebitNoteController@listJobHasD'); //print 2. list debit có chi phí
                Route::get('page={page}', 'DebitNoteController@listPage');
                Route::get('search/type={type}&value={value}&page={page}', 'DebitNoteController@search');

                Route::get('des/{id}', 'DebitNoteController@des');
                Route::get('not-created', 'DebitNoteController@listNotCreated');
                Route::get('des-job-not-created/{id}', 'DebitNoteController@desJobNotCreated');
                Route::post('add', 'DebitNoteController@add');
                Route::post('edit', 'DebitNoteController@edit');
                Route::post('remove', 'DebitNoteController@remove');

                Route::post('add-d', 'DebitNoteController@addDebitD');
                Route::post('edit-d', 'DebitNoteController@editDebitD');
                Route::post('remove-d', 'DebitNoteController@removeDebitD');
            });
            //4. duyet thanh toan khach hang
            Route::group(['prefix' => 'paid-debit'], function () {
                Route::get('list-paid', 'DebitNoteController@listPaid');
                Route::get('list-pending', 'DebitNoteController@listPending');
                Route::get('list-paid/page={page}', 'DebitNoteController@listPaidPage');
                Route::get('list-pending/page={page}', 'DebitNoteController@listPendingPage');
                Route::post('change', 'DebitNoteController@change');
            });
            //6. bang kiem tra du lieu
            Route::group(['prefix' => 'check-data'], function () {
                Route::post('/', 'DebitNoteController@checkData');
            });
            //8. chi phi tien tau/cuoc cont
            Route::group(['prefix' => 'boat-fee'], function () {
                Route::get('list-boat-month-m', 'BoatFeeController@listBoatMonthM');
                Route::get('list-fee-month-m', 'BoatFeeController@listFeeMonthM');
                Route::get('list-boat-month-m/page={page}', 'BoatFeeController@listBoatMonthMPage');
                Route::get('list-fee-month-m/page={page}', 'BoatFeeController@listFeeMonthMPage');
                Route::get('des-month/type={FEE_TYPE}&value={BOAT_FEE_MONTH}', 'BoatFeeController@desMonth');
                Route::post('edit', 'BoatFeeController@edit');
            });
            //10. phieu thu
            Route::group(['prefix' => 'receipts'], function () {
                Route::get('', 'ReceiptsController@list');
                Route::get('page={page}', 'ReceiptsController@listpage');
                Route::get('des/{id}', 'ReceiptsController@des');
                Route::post('add', 'ReceiptsController@add');
                Route::post('edit', 'ReceiptsController@edit');
                Route::post('remove', 'ReceiptsController@remove');
                Route::get('search/type={type}&value={value}&page={page}', 'ReceiptsController@search');
            });
        });
        //print
        Route::group(['prefix' => 'print', 'namespace' => 'Statistic'], function () {
            //II. báo biểu hồ sơ
            Route::group(['prefix' => 'file'], function () {
                // 1.in phieu theo doi
                Route::group(['prefix' => 'job-start'], function () {
                    Route::get('fromjob={fromjob}&tojob={tojob}', 'StatisticFileController@jobStart');
                });
                //2.in job order
                Route::group(['prefix' => 'job-order'], function () {
                    Route::get('jobno={jobno}', 'StatisticFileController@jobOrder');
                    Route::get('boat/jobno={jobno}', 'StatisticFileController@jobOrderBoat');
                    Route::get('custno={id}&jobno={jobno}', 'StatisticFileController@jobOrderCustomer');
                    Route::get('custno={id}', 'StatisticFileController@getJobOrderCustomer');
                    Route::get('fromdate={fromdate}&todate={todate}', 'StatisticFileController@jobOrder_Date');
                });
                //3.bao bieu refund
                Route::group(['prefix' => 'refund'], function () {
                    //1.hang tau, 2.khach hang, 3.dai ly
                    Route::get('type={type}&custno={custno}&jobno={jobno}&fromdate={fromdate}&todate={todate}', 'StatisticFileController@refund');
                    Route::get('post-export', 'StatisticFileController@postExportRefund');
                });
                //4.thong ke job order
                Route::group(['prefix' => 'statistic'], function () {
                    //thống kê tạo job
                    Route::get('created-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'StatisticFileController@statisticCreatedJob');
                    Route::get('user-import-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'StatisticFileController@statisticUserImportJob');
                });
            });
            //IV. payment manager(quan ly thu chi)
            Route::group(['prefix' => 'payment'], function () {
                //1.phieu chi tam ung
                Route::group(['prefix' => 'advance'], function () {
                    //1.1 phieu chi
                    Route::get('advance_no={advanceno}', 'StatisticPaymentController@advance');
                    //1.2thống kê phiếu bù và phiếu trả
                    Route::get('replenishment-withdrawal-payment/advanceno={advanceno}', 'StatisticPaymentController@replenishmentWithdrawalPayment');
                    Route::post('replenishment-withdrawal-payment', 'StatisticPaymentController@postReplenishmentWithdrawalPayment');
                });
                //2. phiếu yêu cầu thanh toán
                Route::group(['prefix' => 'debit-note'], function () {
                    Route::get('type={type}&jobno={jobno}&custno={custno}&fromdate={fromdate}&todate={todate}&debittype={debittype}&person={person}&phone={phone}&bankno={bankno}', 'StatisticPaymentController@debitNote');
                    Route::post('/', 'StatisticPaymentController@postDebitNote');
                });
                //3. báo cáo thu chi mỗi tháng(chưa làm)
                Route::group(['prefix' => 'reports-monthly-revenue-expenditure'], function () {
                });
                //4. báo biểu lợi nhuận(chưa làm)
                Route::group(['prefix' => 'profit'], function () {
                    Route::post('type={type}&jobno={jobno}&custno={custno}&fromdate={fromdate}&todate={todate}', 'StatisticPaymentController@profit');
                });
                //5. thống kê số job trong tháng
                Route::group(['prefix' => 'job-monthly'], function () {
                    Route::get('type={type}&custno={custno}&fromdate={fromdate}&todate={todate}', 'StatisticPaymentController@jobMonthly');
                });
                //8. thống kê phiếu thu
                Route::group(['prefix' => 'receipts'], function () {
                    Route::get('receiptno={receiptno}', 'StatisticPaymesntController@receipt');
                });
            });
            //export excel
            Route::group(['prefix' => 'export'], function () {
                Route::post('/', 'JobStartController@exportDebt');
            });
        });
        //export
        Route::group(['prefix' => 'export', 'namespace' => 'Statistic'], function () {
            //II. báo biểu hồ sơ
            Route::group(['prefix' => 'file'], function () {
                Route::get('lifting/fromdate={fromdate}&todate={todate}', 'StatisticFileController@lifting');
                Route::get('job-order/fromdate={fromdate}&todate={todate}', 'StatisticFileController@exportJobOrder_Date');
            });
            //IV. payment manager(quan ly thu chi)
            Route::group(['prefix' => 'payment'], function () {
                //1.phieu chi tam ung
                Route::group(['prefix' => 'advance'], function () {
                    //1.2thống kê phiếu bù và phiếu trả
                     Route::post('replenishment-withdrawal-payment', 'StatisticPaymentController@postExportReplenishmentWithdrawalPayment');
                });
                //2. phiếu yêu cầu thanh toán
                Route::group(['prefix' => 'debit-note'], function () {
                    Route::get('type={type}&jobno={jobno}&custno={custno}&fromdate={fromdate}&todate={todate}&debittype={debittype}&person={person}&phone={phone}&bankno={bankno}', 'StatisticPaymentController@exportDebitNote');
                    Route::post('/', 'StatisticPaymentController@postExportDebitNote');
                });
            });
        });
        //test
        Route::group(['prefix' => 'test', 'namespace' => 'Statistic'], function () {
            Route::get('/', 'StatisticPaymentController@test')->name('showView');
        });
    });
});
