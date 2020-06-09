<?php
 
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Illuminate\Support\Facades\DB;

use App\Models\Menus;
use App\Models\DataBasic;

class ApiController extends Controller
{
    //menu
    public function listProMenuGroup()
    {
       $data = Menus::listProMenuGroup();
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
    public function listProMenu()
    {
       $data = Menus::listProMenu();
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
    //data basic
    public function getInfoCompany()
    {
       $data = DataBasic::getInfoCompany();
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
    public function listCustomer()
    {
       $data = DataBasic::listCustomer();
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
    public function listStaffCustoms()
    {
       $data = DataBasic::listStaffCustoms();
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
    public function listTypeCost()
    {
       $data = DataBasic::listTypeCost();
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
    public function listCarriers()
    {
       $data = DataBasic::listCarriers();
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
    public function listAgent()
    {
       $data = DataBasic::listAgent();
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
    public function listBranch()
    {
       $data = DataBasic::listBranch();
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
    public function listGarage()
    {
       $data = DataBasic::listGarage();
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
?>