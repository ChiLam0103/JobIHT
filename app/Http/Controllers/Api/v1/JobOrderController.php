<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\JobD;
use App\Models\JobM;

class JobOrderController extends Controller
{
    public function list()
    {
        $data = JobM::list();
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function listPage($page)
    {
        $data = JobM::listPage($page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page' => $data['total_page'],
                'data' => $data['list_job']
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function search($type, $value, $page)
    {
        $data = JobM::search($type, $value, $page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page' => $data['total_page'],
                'data' => $data['list']
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function des($id, $type)
    {
        $SUM_PORT_AMT = 0;

        $job_m = JobM::des($id);
        $job_d = JobD::getJob($id, $type);
        if ($type == "JOB_ORDER") {
            foreach ($job_d as $job) {
                $SUM_PORT_AMT += $job->PORT_AMT + $job->INDUSTRY_ZONE_AMT;
            }
        }
        foreach ($job_d as $job) {
            if ($job->SER_NO == null) {
                $job_d = [];
            }
        }

        if ($job_m) {
            return response()->json([
                'success' => true,
                'data' => $job_m,
                'SUM_PORT_AMT' => $SUM_PORT_AMT,
                'job_d' => $job_d,

            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function add(Request $request)
    {
        $data = JobM::add($request);
        if ($data == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        }
    }
    public function addJobD(Request $request)
    {
        $data = JobD::add($request);
        if ($data == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        }
    }
    public function edit(Request $request)
    {
        $job_m = JobM::edit($request);
        if ($job_m == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $job_m,
            ], Response::HTTP_OK);
        }
    }
    public function editJobD(Request $request)
    {
        $job_d = JobD::edit($request);
        if ($job_d == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $job_d,
            ], Response::HTTP_OK);
        }
    }
    public function remove(Request $request)
    {
        $data = JobM::remove($request);
        if ($data) {
            return response()->json([
                'success' => true,
                'msg' => $data,
                'message' =>  'Bạn đã xóa thành công'
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function removeJobD(Request $request)
    {
        $data = JobD::remove($request);
        if ($data) {
            return response()->json([
                'success' => true,
                'msg' => $data
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function listPending($page)
    {
        $data = JobM::listPending($page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page' => $data['total_page'],
                'data' => $data['list_job']
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function listApproved($page)
    {
        $data = JobM::listApproved($page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page' => $data['total_page'],
                'data' => $data['list_job']
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function approved(Request $request)
    {
        $data = JobM::approved($request);
        if ($data == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        }
    }
}
