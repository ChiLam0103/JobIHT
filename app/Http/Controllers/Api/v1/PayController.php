<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\PayNote;
use App\Models\PayType;

class PayController extends Controller
{
    public function listPayType(){
        $data=PayType::listPayType();
        if($data){
             return response()->json([
                    'success' => true,
                    'data'=>$data
                ], Response::HTTP_OK);
           }else{
            return response()->json( [
                'success' => false,
                'message' => 'null'],
                 Response::HTTP_BAD_REQUEST);
           }
    }
    public function listPayNote(){
        $data=PayNote::listPayNote();
        if($data){
             return response()->json([
                    'success' => true,
                    'data'=>$data
                ], Response::HTTP_OK);
           }else{
            return response()->json( [
                'success' => false,
                'message' => 'null'],
                 Response::HTTP_BAD_REQUEST);
           }
    }
}
