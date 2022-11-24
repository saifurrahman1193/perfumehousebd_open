<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\ApplicationArea;


class ApplicationAreaController extends Controller
{

    public function getApplicationAreas()
    {
        $applicationAreas = DB::table('applicationarea_view')->get();

        $response = ["status" => "Success", "data"=> $applicationAreas];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getApplicationArea($applicationAreaId)
    {
        $applicationArea = DB::table('applicationarea_view')->where('applicationAreaId', $applicationAreaId)->first();
        $response = ["status" => "Success", "data"=> $applicationArea];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addApplicationArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicationArea' => 'required|string|unique:applicationarea',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        ApplicationArea::create($inputs);

        $applicationAreaId = DB::getPdo()->lastInsertId();

        cacheRemove();

        $response = ["status" => "Success", "data" => "Application areas successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editApplicationArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicationArea' => 'required|string|unique:applicationarea,applicationArea,'.$request->applicationAreaId.',applicationAreaId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        ApplicationArea::where('applicationAreaId', $request->applicationAreaId)->update($request->except(['token', '_method']));

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteApplicationArea($applicationAreaId)
    {
        DB::table('applicationarea')->where('applicationAreaId', $applicationAreaId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Application area Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
