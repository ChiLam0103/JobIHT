<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataBasic extends Model
{
    public static function getInfoCompany()
    {
        $data = DB::table('COMPANY')->get();
        return $data;
    }
    public static function listCustomer()
    {
        $data = DB::table('CUSTOMER')->get();
        return $data;
    }
    public static function listStaffCustoms()
    {
        $data = DB::table('PERSONAL')->where('BRANCH_ID','IHTVN1')->get();
        return $data;
    }
    public static function listTypeCost()
    {
        $data = DB::table('DM_NOIDUNG')->get();
        return $data;
    }
    public static function listCarriers()
    {
        $data = DB::table('CUSTOMER')->where('CUST_TYPE',2)->where('BRANCH_ID','IHTVN1')->get();
        return $data;
    }
    public static function listAgent()
    {
        $data = DB::table('CUSTOMER')->where('CUST_TYPE',3)->where('BRANCH_ID','IHTVN1')->get();
        return $data;
    }
    public static function listBranch()
    {
        $data = DB::table('CKICO_BRANCH')->get();
        return $data;
    }
    public static function listGarage()
    {
        $data = DB::table('CUSTOMER')->where('CUST_TYPE',4)->where('BRANCH_ID','IHTVN1')->get();
        return $data;
    }
}
