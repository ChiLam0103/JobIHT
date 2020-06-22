<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\JobStart;
use App\Models\JobD;
use App\Models\JobM;

class JobController extends Controller
{
    public function listJobStart(Request $req){
        $skip = $req->skip ? $req->skip : 0;
        $data=JobStart::listJobStart($skip);
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
    public function desJob($id){
        $job_start=Jobs::getByJobNo($id);
        $job_order_m=Jobs::getJobOrderM($id);
        $list_job_order_d=Jobs::listJobOrderD($id);
        if($job_start){
             return response()->json([
                    'success' => true,
                    'job_start'=>$job_start,
                    'job_order_m'=>$job_order_m,
                    'list_job_order_d'=>$list_job_order_d,
                ], Response::HTTP_OK);
           }else{
            return response()->json( [
                'success' => false,
                'message' => 'null'],
                 Response::HTTP_BAD_REQUEST);
           }
    }
    public function addJob(Request $request){
        $data=Jobs::addJob($request);
        if($data=='200'){
             return response()->json([
                    'success' => true,
                    'data'=>$data
                ], Response::HTTP_OK);
           }else{
            return response()->json( [
                'success' => false,
                'message' => $data],
                 Response::HTTP_BAD_REQUEST);
           }
    }
    public function editJob(Request $request){
        $data=Jobs::editJob($request);
        if($data=='200'){
             return response()->json([
                    'success' => true,
                    'data'=>$data
                ], Response::HTTP_OK);
           }else{
            return response()->json( [
                'success' => false,
                'message' => $data],
                 Response::HTTP_BAD_REQUEST);
           }
    }
    public function deleteJob(Request $request){
        $data=Jobs::deleteJob($request);
        if($data=='200'){
             return response()->json([
                    'success' => true,
                    'data'=>$data
                ], Response::HTTP_OK);
           }else{
            return response()->json( [
                'success' => false,
                'message' => $data],
                 Response::HTTP_BAD_REQUEST);
           }
    }
}
