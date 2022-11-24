<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Suppliers;
use Illuminate\Support\Facades\Storage;


class SupplierController extends Controller
{

    public function getSuppliers()
    {
        $suppliers = DB::table('suppliers_view')->get();

        $response = ["status" => "Success", "data"=> $suppliers];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSupplier($supplierId)
    {
        $supplier = DB::table('suppliers_view')->where('supplierId', $supplierId)->first();
        $response = ["status" => "Success", "data"=> $supplier];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method', 'logoPath']);
        Suppliers::create($inputs);

        $supplierId = DB::getPdo()->lastInsertId();

        if ( $request->has('logoPath') && $request->logoPath !=null &&  strlen($request->logoPath)>100) {
            $png_url = "supplier-".$supplierId.'_'.rand(999,9999999).".png";
            $data = substr($request->logoPath, strpos($request->logoPath, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('suppliersLogoUploads')->put($png_url, $data);
            Suppliers::find($supplierId)->update(['logoPath' => '/uploads/suppliers/logo/'.$png_url]);
        }


        cacheRemove();
        $response = ["status" => "Success", "data" => "Supplier successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Suppliers::where('supplierId', $request->supplierId)->update($request->except(['token', 'logoPath']));

        if ( $request->has('logoPath') && $request->logoPath !=null &&  strlen($request->logoPath)>100) {
            $png_url = "supplier-".$request->supplierId.'_'.rand(999,9999999).".png";
            $data = substr($request->logoPath, strpos($request->logoPath, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('suppliersLogoUploads')->put($png_url, $data);
            Suppliers::find($request->supplierId)->update(['logoPath' => '/uploads/suppliers/logo/'.$png_url]);
        }

        if ( !isset($request->logoPath) || $request->logoPath == null)
        {
            Suppliers::find($request->supplierId)->update(['logoPath' => '']);
        }


        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteSupplier($supplierId)
    {
        DB::table('suppliers')->where('supplierId', $supplierId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Supplier Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
