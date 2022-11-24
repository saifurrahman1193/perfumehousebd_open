<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DeliveryAddress;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class DeliveryController extends Controller
{
    public function getDeliverySetting()
    {
        $deliverysetting = Cache::rememberForever('deliverysettings', function () {
            return DB::table('deliverysettings')->first();
        });

        $response = ["status" => "Success", "data"=> $deliverysetting];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editDeliverySetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deliverySettingPrice' => 'required',
            'outsideDahakeDeliveryPrice' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        DB::table('deliverysettings')->where('deliverySettingId', 1)->update(
            [
                'deliverySettingPrice' => $request->deliverySettingPrice,
                'outsideDahakeDeliveryPrice' => $request->outsideDahakeDeliveryPrice,
            ]
        );

        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getCurrentUserDeliveryAddresses($userId)
    {
        $deliveryaddress = DB::table('deliveryaddress_view')->where('userId', $userId)->get();
        $response = ["status" => "Success", "data"=> $deliveryaddress];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getAUserDeliveryAddresses($userId)
    {
        $deliveryaddress = DB::table('deliveryaddress_view')->where('userId', $userId)->get();
        $response = ["status" => "Success", "data"=> $deliveryaddress];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





    public function addAUserDeliveryAddress(Request $request, $userId)
    {
        $request['userId'] = $userId;
        $validator = Validator::make($request->all(), [
            'deliveryAddressTitle' => 'required|string',
            'deliveryAddress' => 'required|string',
            'userId' => 'required|numeric',
            'areaId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except(['token', '_method']);


        $inputs['userId'] = $userId;

        DeliveryAddress::create($inputs);


        $deliveryAddressId = DB::getPdo()->lastInsertId();

        // sleep(10);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Delivery address successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


}
