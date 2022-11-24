<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\ProductTypes;


class ProductTypeController extends Controller
{

    public function getProductTypes()
    {
        $productTypes = DB::table('producttypes_view')->get();

        $response = ["status" => "Success", "data"=> $productTypes];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getProductType($productTypeId)
    {
        $productType = DB::table('producttypes_view')->where('productTypeId', $productTypeId)->first();
        $response = ["status" => "Success", "data"=> $productType];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addProductType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productType' => 'required|string|unique:producttypes',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        ProductTypes::create($inputs);

        $productTypeId = DB::getPdo()->lastInsertId();

        cacheRemove();

        $response = ["status" => "Success", "data" => "ProductTypes successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editProductType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productType' => 'required|string|unique:producttypes,productType,'.$request->productTypeId.',productTypeId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        ProductTypes::where('productTypeId', $request->productTypeId)->update($request->except(['token', '_method']));

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteProductType($productTypeId)
    {
        DB::table('producttypes')->where('productTypeId', $productTypeId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "ProductTypes Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
