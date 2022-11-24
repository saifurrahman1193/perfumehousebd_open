<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Services;
use App\Company;
use \Gumlet\ImageResize;
use Intervention\Image\Facades\Image as Image;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function getService($serviceId)
    {
        $service = DB::table('services')->where('serviceId', $serviceId)->first();
        $response = ["status" => "Success", "data"=> $service];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|unique:services',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except(['picPath', 'token', '_method']);
        Services::create($inputs);

        $serviceId = DB::getPdo()->lastInsertId();


        if ( $request->has('picPath') && $request->picPath !=null &&  strlen($request->picPath)>100) {
            //decode base64 string
           $png_url = $request->service."_".rand(1,1000).".png";

           // local path
           $path = public_path().'/uploads/services/' . $png_url;

           // cpanel path please change before upload to cpanel and comment out local path
           // $path = public_path().'/..'.'/img/services/' . $png_url;


           Image::make(file_get_contents($request->picPath))->save($path);

           Services::where('serviceId', $serviceId)->update(['picPath' => '/uploads/services/'.$png_url]);
        }


        $response = ["status" => "Success", "data" => "Service successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|unique:services,service,'.$request->serviceId.',serviceId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Services::where('serviceId', $request->serviceId)->update($request->except(['token', 'picPath']));

        if ( $request->has('picPath') && $request->picPath !=null &&  strlen($request->picPath)>100) {
            //decode base64 string
           $png_url = $request->service."_".rand(1,1000).".png";

           // local path
           $path = public_path().'/uploads/services/' . $png_url;

           // cpanel path please change before upload to cpanel and comment out local path
           // $path = public_path().'/..'.'/img/services/' . $png_url;


           Image::make(file_get_contents($request->picPath))->save($path);

           Services::where('serviceId', $request->serviceId)->update(['picPath' => '/uploads/services/'.$png_url]);
        }


        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteService($serviceId)
    {
        DB::table('services')->where('serviceId', $serviceId)->delete();

        $response = ["status" => "Success", "data" => "Service Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }



    public function getServices()
    {
        $services = DB::table('services')->orderBy('service')->get();
        $response = ["status" => "Success", "data"=> $services];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getSubServices($serviceId)
    {
        $subservices = DB::table('subservices')->where('serviceId', $serviceId)->orderBy('subService')->get();
        $response = ["status" => "Success", "data"=> $subservices];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getSubService($subServiceId)
    {
        $subservice = DB::table('subservices')->where('subServiceId', $subServiceId)->first();
        $response = ["status" => "Success", "data"=> $subservice];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getPackages($subServiceId)
    {
        $packages = DB::table('packages_view')->where('subServiceId', $subServiceId)->get();
        $response = ["status" => "Success", "data"=> $packages];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getAdvertisementSliders()
    {
        $advertisementsliders = DB::table('advertisementslider')->get();
        $response = ["status" => "Success", "data"=> $advertisementsliders];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getCompanyInfo()
    {
        $companyInfo = Cache::rememberForever('company', function () {
            return DB::table('company')->first();
        });

        $response = ["status" => "Success", "data"=> $companyInfo];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function updateCompanyInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Company::where('companyId', $request->companyId)->update($request->except(['token', 'logoPath','aboutPicPath']));


        if ( $request->has('logoPath') && $request->logoPath !=null &&  strlen($request->logoPath)>100)
        {
            $png_url = "company_logo".".png";
            $path = public_path().'/uploads/company/logo/' . $png_url;
            $data = substr($request->logoPath, strpos($request->logoPath, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('companylogouploads')->put($png_url, $data);

            Company::where('companyId', $request->companyId)->update(['logoPath' => '/uploads/company/logo/'.$png_url]);
        }

        if ( $request->has('aboutPicPath') && $request->aboutPicPath !=null &&  strlen($request->aboutPicPath)>100)
        {
            $png_url = "company_about".".png";
            $path = public_path().'/uploads/company/about/' . $png_url;
            $data = substr($request->aboutPicPath, strpos($request->aboutPicPath, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('companyaboutpicuploads')->put($png_url, $data);

            Company::where('companyId', $request->companyId)->update(['aboutPicPath' => '/uploads/company/about/'.$png_url]);
        }

        cacheRemove();


        $response = ["status" => "Success"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getAreas()
    {
        $areas = DB::table('area')->orderBy('area')->get();
        $response = ["status" => "Success", "data"=> $areas];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function profileUpdate(Request $request)
    {
        User::find(auth()->user()->id)->update($request->except(['photoPath', 'id', 'email', 'password', '_method']));
        if ( $request->has('photoPath') && $request->photoPath !=null &&  strlen($request->photoPath)>100)
        {
            //decode base64 string
            $png_url = "user-".auth()->user()->id.'_'.rand(999,9999999).".png";

            // local path
            $path = public_path().'/uploads/users/' . $png_url;
            // dd($path);
            // cpanel path please change before upload to cpanel and comment out local path
            // $path = public_path().'/..'.'/img/users/' . $png_url;


            //    Image::make(file_get_contents($request->photoPath))->save($path);
            $data = substr($request->photoPath, strpos($request->photoPath, ',') + 1);
            // dd($data);
            $data = base64_decode($data);
            // dd($data);
            Storage::disk('useruploads')->put($png_url, $data);
            // dd('success');

            User::find(auth()->user()->id)->update(['photoPath' => '/uploads/users/'.$png_url]);
        }

        $response = ["status" => "Success"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




}
