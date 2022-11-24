<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class BrandController extends Controller
{


    public function getBrands()
    {
        $brands = Cache::remember('brands', 5, function () {
            return DB::table('brand_view')->get();
        });

        $response = ["status" => "Success", "data"=> $brands];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getBrand($brandId)
    {
        $brand = DB::table('brand')->where('brandId', $brandId)->first();
        $response = ["status" => "Success", "data"=> $brand];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getBrandWithSlug($brandSlug)
    {
        $brand = DB::table('brand')->where('brandSlug', $brandSlug)->first();
        $response = ["status" => "Success", "data"=> $brand];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addBrand(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|unique:brand',
            'brandSlug' => 'required|string|unique:brand',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method', 'logo']);
        Brand::create($inputs);

        $brandId = DB::getPdo()->lastInsertId();

        if ( $request->has('logo') && $request->logo !=null &&  strlen($request->logo)>100) {
            $png_url = "brand-".$brandId.'_'.rand(999,9999999).".png";
            $data = substr($request->logo, strpos($request->logo, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('brandLogoUploads')->put($png_url, $data);
            Brand::find($brandId)->update(['logo' => '/uploads/brands/logo/'.$png_url]);
        }

        cacheRemove();

        $response = ["status" => "Success", "data" => "Brand successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editBrand(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|unique:brand,brand,'.$request->brandId.',brandId',
            'brandSlug' => 'required|string|unique:brand,brandSlug,'.$request->brandId.',brandId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Brand::where('brandId', $request->brandId)->update($request->except(['token', 'logo']));

        if ( $request->has('logo') && $request->logo !=null &&  strlen($request->logo)>100) {
            $png_url = "brand-".$request->brandId.'_'.rand(999,9999999).".png";
            $data = substr($request->logo, strpos($request->logo, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('brandLogoUploads')->put($png_url, $data);
            Brand::find($request->brandId)->update(['logo' => '/uploads/brands/logo/'.$png_url]);
        }

        if ( !isset($request->logo) || $request->logo == null)
        {
            Brand::find($request->brandId)->update(['logo' => '']);
        }

        cacheRemove();


        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteBrand($brandId)
    {
        DB::table('brand')->where('brandId', $brandId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Brand Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
