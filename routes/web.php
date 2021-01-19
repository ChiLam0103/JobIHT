<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', 'UsersController@index');

Route::get('file', 'HomeController@file');
Route::namespace('Statistics')->group(function () {
        //print
        Route::group(['prefix' => 'print', 'namespace' => 'Prints'], function () {
            //1. báo biểu hồ sơ
            Route::group(['prefix' => 'file'], function () {
                // 1.in phieu theo doi
                Route::group(['prefix' => 'job-start'], function () {
                    Route::get('fromjob={fromjob}&tojob={tojob}', 'FileController@jobStart');
                });
                //2.in job order
                Route::group(['prefix' => 'job-order'], function () {
                    Route::get('jobno={jobno}', 'FileController@jobOrder');
                    Route::get('boat/jobno={jobno}', 'FileController@jobOrderBoat');
                    Route::get('custno={id}&jobno={jobno}', 'FileController@jobOrderCustomer');
                    Route::get('custno={id}', 'FileController@getJobOrderCustomer');
                    Route::get('fromdate={fromdate}&todate={todate}', 'FileController@jobOrder_Date');
                });
                //3.bao bieu refund
                Route::group(['prefix' => 'refund'], function () {
                    //1.hang tau, 2.khach hang, 3.dai ly
                    Route::get('type={type}&custno={custno}&jobno={jobno}&fromdate={fromdate}&todate={todate}', 'FileController@refund');
                    Route::get('post-export', 'FileController@postExportRefund');
                });
                //4.thong ke job order
                Route::group(['prefix' => 'statistic'], function () {
                    //thống kê tạo job
                    Route::get('created-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'FileController@statisticCreatedJob');
                    Route::get('user-import-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'FileController@statisticUserImportJob');
                });
            });
            //2. payment manager(quan ly thu chi)
            Route::group(['prefix' => 'payment'], function () {
                //1.phieu chi tam ung
                Route::group(['prefix' => 'advance'], function () {
                    //1.1 phieu chi
                    Route::get('advance_no={advanceno}', 'PaymentController@advance');
                    //1.2thống kê phiếu bù và phiếu trả
                    Route::post('replenishment-withdrawal-payment', 'PaymentController@postReplenishmentWithdrawalPayment');
                });
                //2. phiếu yêu cầu thanh toán
                Route::group(['prefix' => 'debit-note'], function () {
                    Route::get('type={type}&jobno={jobno}&custno={custno}&fromdate={fromdate}&todate={todate}&debittype={debittype}&person={person}&phone={phone}&bankno={bankno}', 'PaymentController@debitNote');
                    Route::post('/', 'PaymentController@postDebitNote');
                    Route::get('/', 'PaymentController@postDebitNote');
                });
                //4. báo biểu lợi nhuận
                Route::get('profit/type={type}&jobno={jobno}&custno={custno}&fromdate={fromdate}&todate={todate}', 'PaymentController@profit');
                //5. thống kê số job trong tháng
                Route::get('job-monthly/type={type}&custno={custno}&fromdate={fromdate}&todate={todate}', 'PaymentController@jobMonthly');
                //6. thong ke thanh toan cua khach hang
                Route::get('payment-customers/type={type}&custno={custno}&fromdate={fromdate}&todate={todate}', 'PaymentController@paymentCustomers');
                //7. thong ke job order
                Route::get('job-order/type={type}&custno={custno}&person={person}&fromdate={fromdate}&todate={todate}', 'PaymentController@jobOrder');
                //8. thống kê phiếu thu
                Route::get('receipts/type={type}&receiptno={receiptno}', 'PaymentController@receipt');
            });
            // //export excel
            // Route::group(['prefix' => 'export'], function () {
            //     Route::post('/', 'JobStartController@exportDebt');
            // });
        });
        //export
        Route::group(['prefix' => 'export', 'namespace' => 'Exports'], function () {
            //1. báo biểu hồ sơ
            Route::group(['prefix' => 'file'], function () {
                //2.job order
                Route::post('job-order', 'FileController@exportJobOrder_Date');
                //3. báo biểu refund
                Route::post('refund', 'FileController@postExportRefund');
                //5. thống kê nâng hạ
                Route::get('lifting/fromdate={fromdate}&todate={todate}', 'FileController@lifting');
            });
            //2. payment manager(quan ly thu chi)
            Route::group(['prefix' => 'payment'], function () {
                //1.2 thống kê phiếu bù và phiếu trả
                Route::post('advance/replenishment-withdrawal-payment', 'PaymentController@postExportReplenishmentWithdrawalPayment');
                //2. phiếu yêu cầu thanh toán
                Route::post('debit-note', 'PaymentController@postExportDebitNote');
                //4. báo biểu lợi nhuận
                Route::post('profit', 'PaymentController@profit');
                //5. thống kê số job trong tháng
                Route::post('job-monthly', 'PaymentController@postExportJobMonthly');
                //6. thong ke thanh toan cua khach hang
                Route::post('payment-customers', 'PaymentController@postExportPaymentCustomers');
                //7. thong ke job order
                Route::post('job-order', 'PaymentController@postExportjobOrder');
            });
        });
});
