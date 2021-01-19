<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ExcelService;
use App\Models\Users;
use App\User;

use App\Models\JobStart;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function file()
    {
        return view('file');
    }
    public function exportDebt( ExcelService $excelService)
    {
        $listResult=JobStart::list();
        return $excelService->exportDebt($listResult);
    }
    public function replenishmentWithdrawalPayment()
    {
        return view('print\payment\advance\replenishmentWithdrawalPayment');
    }

}
