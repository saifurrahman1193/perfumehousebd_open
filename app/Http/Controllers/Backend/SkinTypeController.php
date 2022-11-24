<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\SkinTypes;


class SkinTypeController extends Controller
{

    public function getSkinTypes()
    {
        $skinTypes = DB::table('skintypes_view')->get();

        $response = ["status" => "Success", "data"=> $skinTypes];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSkinType($skinTypeId)
    {
        $skinType = DB::table('skintypes_view')->where('skinTypeId', $skinTypeId)->first();
        $response = ["status" => "Success", "data"=> $skinType];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addSkinType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skinType' => 'required|string|unique:skintypes',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        SkinTypes::create($inputs);

        $skinTypeId = DB::getPdo()->lastInsertId();


        cacheRemove();
        $response = ["status" => "Success", "data" => "SkinTypes successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editSkinType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skinType' => 'required|string|unique:skintypes,skinType,'.$request->skinTypeId.',skinTypeId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        SkinTypes::where('skinTypeId', $request->skinTypeId)->update($request->except(['token', '_method']));


        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteSkinType($skinTypeId)
    {
        DB::table('skintypes')->where('skinTypeId', $skinTypeId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "SkinTypes Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
