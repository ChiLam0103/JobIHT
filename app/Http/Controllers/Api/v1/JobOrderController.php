<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\JobD;
use App\Models\JobM;

class JobOrderController extends Controller
{
    public function list(){
        $data=JobM::list();
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
    public function des($id){
        $job_m=JobM::des($id);

        $job_d=JobD::getJob($id);
        if($job_m){
             return response()->json([
                    'success' => true,
                    'job_m'=>$job_m,
                    'job_d'=>$job_d,

                ], Response::HTTP_OK);
           }else{
            return response()->json( [
                'success' => false,
                'message' => 'null'],
                 Response::HTTP_BAD_REQUEST);
           }
    }
    public function add(Request $request){
        $data=JobM::add($request);
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
    public function addOrderD(Request $request){
        $data=JobD::add($request);
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
    public function edit(Request $request){
        $data=JobM::edit($request);
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
    public function editOrderD(Request $request){
        $data=JobD::edit($request);
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
    public function removeCheck($id){
        $data=JobM::removeCheck($id);
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
    public function remove(Request $request){
        $data=JobM::remove($request);
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
    public function removeOrderD(Request $request){
        $data=JobD::remove($request);
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
    //3
    public function listPending(){
        $data=JobM::listPending();
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
    public function listApproved(){
        $data=JobM::listApproved();
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
    public function approved(Request $request){
        $data=JobM::approved($request);
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
