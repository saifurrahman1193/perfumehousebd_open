<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\BatchProducts;


class BatchController extends Controller
{

    public function getBatchProducts()
    {
        $batchproducts = DB::table('batchproducts_view')->get();

        $response = ["status" => "Success", "data"=> $batchproducts];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getBatchProduct($batchProductId)
    {
        $batchproduct = DB::table('batchproducts_view')->where('batchProductId', $batchProductId)->first();

        $response = ["status" => "Success", "data"=> $batchproduct];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addBatchProduct(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'batchDate' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 401);
        // }

        $inputs = $request->except([ 'token', '_method']);

        if ($request->batchProductId!= null)
        {
            $batchproduct = BatchProducts::where('batchProductId', $request->batchProductId)->update($request->except(['token', 'supplier', 'productName', 'shortDescription', 'productQty', 'uomId', 'uom', 'sku', 'brandId', 'brand' ]));
        }
        else{
            $batchproduct = BatchProducts::create($inputs);
        }

        cacheRemove();
        $response = ["status" => "Success", "data" => $batchproduct ];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editBatchProduct(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'batch' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 401);
        // }

        BatchProducts::where('batchProductId', $request->batchProductId)->update($request->except(['token']));

        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteBatchProduct($batchProductId)
    {
        DB::table('batchproducts')->where('batchProductId', $batchProductId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Batch Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




}
