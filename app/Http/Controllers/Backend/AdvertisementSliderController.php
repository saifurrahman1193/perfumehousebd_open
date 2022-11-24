<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\AdvertisementSlider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class AdvertisementSliderController extends Controller
{
    public function getAdvertisementSliders()
    {
        $advertisementsliders = Cache::remember('advertisementsliders', 5, function () {
            return DB::table('advertisementslider')->get();
        });

        $response = ["status" => "Success", "data"=> $advertisementsliders];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getAdvertisementSlider($advertisementSliderId)
    {
        $advertisementslider = DB::table('advertisementslider')->where('advertisementSliderId', $advertisementSliderId)->first();
        $response = ["status" => "Success", "data"=> $advertisementslider];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addAdvertisementSlider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picPath' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method', 'picPath']);
        AdvertisementSlider::create($inputs);

        $advertisementSliderId = DB::getPdo()->lastInsertId();

        if ( $request->has('picPath') && $request->picPath !=null &&  strlen($request->picPath)>100) {
            $png_url = "advertisementSliderId-".$advertisementSliderId.'_'.rand(999,9999999).".png";
            $data = substr($request->picPath, strpos($request->picPath, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('advertisementsliderUploads')->put($png_url, $data);
            AdvertisementSlider::find($advertisementSliderId)->update(['picPath' => '/uploads/advertisementslider/'.$png_url]);
        }

        cacheRemove();

        $response = ["status" => "Success", "data" => "AdvertisementSlider successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteAdvertisementSlider($advertisementSliderId)
    {
        $sliderPicPath = DB::table('advertisementslider')->where('advertisementSliderId', $advertisementSliderId)->pluck('picPath')->first();

        try {
            unlink(public_path().$sliderPicPath);
        } catch (\Throwable $th) {
        }

        DB::table('advertisementslider')->where('advertisementSliderId', $advertisementSliderId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "AdvertisementSlider Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

}
