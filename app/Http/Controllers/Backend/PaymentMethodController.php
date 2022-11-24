<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\PaymentMethod;

use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{


    public function getPaymentMethods()
    {
        $paymentmethods = DB::table('paymentmethods_view')->get();

        $response = ["status" => "Success", "data"=> $paymentmethods];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getPaymentMethod($paymentMethodId)
    {
        $paymentmethod = DB::table('paymentmethods_view')->where('paymentMethodId', $paymentMethodId)->first();
        $response = ["status" => "Success", "data"=> $paymentmethod];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addPaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paymentMethod' => 'required|string|unique:paymentmethods',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        PaymentMethod::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Payment Method successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editPaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paymentMethod' => 'required|string|unique:paymentmethods,paymentMethod,'.$request->paymentMethodId.',paymentMethodId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        PaymentMethod::find( $request->paymentMethodId)->update($request->except([ 'qrCode']));


        if (isset($request->qrCode) and strlen($request->qrCode)>100) {
            $png_url = "paymentmethodid-".$request->paymentMethodId.'_'.rand(999,9999999).".png";
            $path = public_path().'/uploads/paymentmethods/qrCode/' . $png_url;
            $data = substr($request->qrCode, strpos($request->qrCode, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('qrcodeuploads')->put($png_url, $data);
            PaymentMethod::find( $request->paymentMethodId)->update(['qrCode' => '/uploads/paymentmethods/qrCode/' . $png_url]);
        }

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deletePaymentMethod($paymentMethodId)
    {
        DB::table('paymentmethods')->where('paymentMethodId', $paymentMethodId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Payment Method Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
