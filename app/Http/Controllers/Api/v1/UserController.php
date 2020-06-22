<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Users;

class UserController extends Controller
{
    public function login(Request $request)
    {
       $data = Users::login($request);
       if($data){
        if($data=='404' || $data=='401'){
            return response()->json( [
                'success' => false,
                'message' => 'Invalid User or Password'],
                Response::HTTP_BAD_REQUEST);
           }else{
            return response()->json([
                'success' => true,
                'data'=>$data
            ], Response::HTTP_OK);
           }
       }else{
        return response()->json( [
            'success' => false,
            'message' => 'Invalid User or Password'],
             Response::HTTP_BAD_REQUEST);
       }
       
    }
    public function listUser(){
        $data=Users::listUser();
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
    public function getUser($USER_NO){
        $data=Users::getUser($USER_NO);
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
    public function listMenuPro(){
        $data=Users::listMenuPro();
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
