<?php
namespace App\Http\Controllers\Backend;
use App\Uom;

use Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


class UomController extends Controller
{
    public function getUoms()
    {
        $uoms = Cache::rememberForever('getUoms', function () {
            return DB::table('uom_view')->get();
        });

        $response = ["status" => "Success", "data"=> $uoms];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getUom($uomId)
    {
        $uom = DB::table('uom_view')->where('uomId', $uomId)->first();
        $response = ["status" => "Success", "data"=> $uom];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addUom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uom' => 'required|string|unique:uom',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        Uom::create($inputs);

        cacheRemove();

        $response = ["status" => "Success", "data" => "Uom successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editUom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uom' => 'required|string|unique:uom,uom,'.$request->uomId.',uomId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Uom::where('uomId', $request->uomId)->update($request->except(['token']));

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteUom($uomId)
    {
        DB::table('uom')->where('uomId', $uomId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Uom Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
