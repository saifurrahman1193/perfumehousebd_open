<?php
namespace App\Http\Controllers\Backend;
use Validator;

use App\Gifttypes;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


class GiftTypeController extends Controller
{

    public function getGiftTypes()
    {
        $gifttypes = Cache::rememberForever('getGiftTypes', function () {
            return DB::table('gifttypes')->get();
        });

        $response = ["status" => "Success", "data"=> $gifttypes];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getGiftType($giftTypeId)
    {
        $gifttype = DB::table('gifttypes')->where('giftTypeId', $giftTypeId)->first();
        $response = ["status" => "Success", "data"=> $gifttype];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addGiftType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'giftType' => 'required|string|unique:gifttypes',
            'giftTypeSlug' => 'required|string|unique:gifttypes',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        Gifttypes::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "Gift Type successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editGiftType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'giftType' => 'required|string|unique:gifttypes,giftType,'.$request->giftTypeId.',giftTypeId',
            'giftTypeSlug' => 'required|string|unique:gifttypes,giftTypeSlug,'.$request->giftTypeId.',giftTypeId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Gifttypes::where('giftTypeId', $request->giftTypeId)->update($request->except(['token']));
        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteGiftType($giftTypeId)
    {
        DB::table('gifttypes')->where('giftTypeId', $giftTypeId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Gift type Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
