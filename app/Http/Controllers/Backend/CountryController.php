<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Country;


class CountryController extends Controller
{


    public function getCountries()
    {
        $countries = DB::table('country_view')->get();

        $response = ["status" => "Success", "data"=> $countries];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getCountry($countryId)
    {
        $country = DB::table('country_view')->where('countryId', $countryId)->first();
        $response = ["status" => "Success", "data"=> $country];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|string|unique:country',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        Country::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Country successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|string|unique:country,country,'.$request->countryId.',countryId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Country::where('countryId', $request->countryId)->update($request->except(['token']));
        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteCountry($countryId)
    {
        DB::table('country')->where('countryId', $countryId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Country Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


}
