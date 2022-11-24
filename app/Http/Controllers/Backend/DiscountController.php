<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\DiscountAll;
use App\DiscountProduct;
use App\DiscountCustomer;
use App\DiscountProductCustomer;

use Illuminate\Support\Facades\Cache;

class DiscountController extends Controller
{


    // ==================Discount Product Customer=======================
    // ==================Discount Product Customer=======================

    public function getDiscountProductCustomers()
    {
        $discountProductCustomers = DB::table('discountproductcustomer_view')->get();

        $response = ["status" => "Success", "data"=> $discountProductCustomers];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getDiscountProductCustomer($discountProductCustomerId)
    {
        $discountProductCustomer = DB::table('discountproductcustomer_view')->where('discountProductCustomerId', $discountProductCustomerId)->first();
        $response = ["status" => "Success", "data"=> $discountProductCustomer];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addDiscountProductCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|numeric',
            'customerId' => 'required|numeric',
            'discountProductCustomerExpireDate' => 'required|date',
            'discountProductCustomerPercent'=>'required_without:discountProductCustomerAmount',
            'discountProductCustomerAmount'=>'required_without:discountProductCustomerPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        DiscountProductCustomer::create($inputs);

        cacheRemove();

        $response = ["status" => "Success", "data" => "DiscountProductCustomer successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editDiscountProductCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|numeric',
            'customerId' => 'required|numeric',
            'discountProductCustomerExpireDate' => 'required|date',
            'discountProductCustomerPercent'=>'required_without:discountProductCustomerAmount',
            'discountProductCustomerAmount'=>'required_without:discountProductCustomerPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        DiscountProductCustomer::find( $request->discountProductCustomerId)->update($request->all());
        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteDiscountProductCustomer($discountProductCustomerId)
    {
        DB::table('discountproductcustomer')->where('discountProductCustomerId', $discountProductCustomerId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountProductCustomer Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // ==================Discount Product Customer=======================
    // ==================Discount Product Customer=======================




    // ==================Discount Customer=======================
    // ==================Discount Customer=======================

    public function getDiscountCustomers()
    {
        $discountCustomers = DB::table('discountcustomer_view')->get();

        $response = ["status" => "Success", "data"=> $discountCustomers];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getDiscountCustomer($discountCustomerId)
    {
        $discountCustomer = DB::table('discountcustomer_view')->where('discountCustomerId', $discountCustomerId)->first();
        $response = ["status" => "Success", "data"=> $discountCustomer];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addDiscountCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerId' => 'required|numeric',
            'discountCustomerExpireDate' => 'required|date',
            'discountCustomerPercent'=>'required_without:discountCustomerAmount',
            'discountCustomerAmount'=>'required_without:discountCustomerPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        DiscountCustomer::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountCustomer successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editDiscountCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerId' => 'required|numeric',
            'discountCustomerExpireDate' => 'required|date',
            'discountCustomerPercent'=>'required_without:discountCustomerAmount',
            'discountCustomerAmount'=>'required_without:discountCustomerPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        DiscountCustomer::find( $request->discountCustomerId)->update($request->all());

        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteDiscountCustomer($discountCustomerId)
    {
        DB::table('discountcustomer')->where('discountCustomerId', $discountCustomerId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountCustomer Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // ==================Discount Customer=======================
    // ==================Discount Customer=======================




    // ==================Discount Product=======================
    // ==================Discount Product=======================

    public function getDiscountProducts()
    {
        $discountProductss = DB::table('discountproduct_view')->get();

        $response = ["status" => "Success", "data"=> $discountProductss];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getDiscountProduct($discountProductId)
    {
        $discountProduct = DB::table('discountproduct_view')->where('discountProductId', $discountProductId)->first();
        $response = ["status" => "Success", "data"=> $discountProduct];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addDiscountProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|numeric',
            'discountProductExpireDate' => 'required|date',
            'discountProductPercent'=>'required_without:discountProductAmount',
            'discountProductAmount'=>'required_without:discountProductPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        DiscountProduct::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountProduct successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editDiscountProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|numeric',
            'discountProductExpireDate' => 'required|date',
            'discountProductPercent'=>'required_without:discountProductAmount',
            'discountProductAmount'=>'required_without:discountProductPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        DiscountProduct::find( $request->discountProductId)->update($request->all());

        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteDiscountProduct($discountProductId)
    {
        DB::table('discountproduct')->where('discountProductId', $discountProductId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountProduct Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // ==================Discount Product=======================
    // ==================Discount Product=======================


    // ==================Discount All=======================
    // ==================Discount All=======================

    public function getDiscountAlls()
    {
        $discountAlls = DB::table('discountall')->get();

        $response = ["status" => "Success", "data"=> $discountAlls];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getDiscountAll($discountAllId)
    {
        $discountAll = DB::table('discountall')->where('discountAllId', $discountAllId)->first();
        $response = ["status" => "Success", "data"=> $discountAll];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addDiscountAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discountAllExpireDate' => 'required|date',
            'discountAllPercent'=>'required_without:discountAllAmount',
            'discountAllAmount'=>'required_without:discountAllPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }


        $inputs = $request->all();
        DiscountAll::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountAll successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editDiscountAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discountAllExpireDate' => 'required|date',
            'discountAllPercent'=>'required_without:discountAllAmount',
            'discountAllAmount'=>'required_without:discountAllPercent',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        DiscountAll::find( $request->discountAllId)->update($request->all());

        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteDiscountAll($discountAllId)
    {
        DB::table('discountall')->where('discountAllId', $discountAllId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountAll Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // ==================Discount All=======================
    // ==================Discount All=======================









    public function getProductsWithDiscountCalculation(Request $request)
    {

        $products = Cache::rememberForever('getProductsWithDiscountCalculationproducts_view', function () {
            return DB::table('products_view')->get();
        });

        // dd($products);

        $data = [];
        foreach ($products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount, $expireDate, $discountReason) = getProductDiscount($productId);

            $data[$key]['discountPercent'] = $discountPercent;
            $data[$key]['discountAmount'] = $discountAmount;
            $data[$key]['expireDate'] = $expireDate;
            $data[$key]['discountReason'] = $discountReason;
        }

        $response = ["status" => "Success", "data" => $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getDiscountCalculation(Request $request)
    {
        $data = [];
        foreach ($request->products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]['productId'];
            list($discountPercent, $discountAmount, $expireDate, $discountReason) = getProductDiscount($productId);

            $data[$key]['discountPercent'] = $discountPercent;
            $data[$key]['discountAmount'] = $discountAmount;
            $data[$key]['expireDate'] = $expireDate;
            $data[$key]['discountReason'] = $discountReason;
        }
        // dd($data);

        $response = ["status" => "Success", "data" => $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getDiscountCalculationforManualCart(Request $request, $customerId)
    {
        $data = [];
        foreach ($request->products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]['productId'];
            list($discountPercent, $discountAmount) = getProductDiscountForManualCart($productId, $customerId);

            $data[$key]['discountPercent'] = $discountPercent;
            $data[$key]['discountAmount'] = $discountAmount;

        }
        // dd($data);

        $response = ["status" => "Success", "data" => $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }







}
