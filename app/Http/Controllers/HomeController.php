<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExcelService;
use App\Models\JobStart;
use App\Models\JobM;
use App\Models\Customer;

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
}
