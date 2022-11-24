<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Area;

use Illuminate\Support\Facades\Cache;

class AreaController extends Controller
{

    public function getAreas()
    {
        $areas = Cache::rememberForever('getAreas', function () {
            return DB::table('area_view')->get();
        });

        $response = ["status" => "Success", "data"=> $areas];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




    public function getArea($areaId)
    {
        $area = DB::table('area_view')->where('areaId', $areaId)->first();
        $response = ["status" => "Success", "data"=> $area];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        Area::create($inputs);

        cacheRemove();

        $response = ["status" => "Success", "data" => "Area successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Area::where('areaId', $request->areaId)->update($request->except(['token', 'isDeletable']));

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteArea($areaId)
    {
        DB::table('area')->where('areaId', $areaId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Area Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

}
