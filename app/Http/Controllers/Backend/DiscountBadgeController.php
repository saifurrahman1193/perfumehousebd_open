<?php
namespace App\Http\Controllers\Backend;
use Validator;

use Carbon\Carbon;
use App\DiscountBadges;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class DiscountBadgeController extends Controller
{
    //===================== Discount badge crud========================
    //===================== Discount badge crud========================
    public function getDiscountBadges()
    {
        $discountBadges = Cache::rememberForever('getDiscountBadges', function () {
            return DB::table('discountbadges')->orderBy('reqPointsToAchieve')->get();
        });

        $response = ["status" => "Success", "data"=> $discountBadges];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getDiscountBadge($discountBadgeId)
    {
        $discountBadge = DB::table('discountbadges')->where('discountBadgeId', $discountBadgeId)->first();
        $response = ["status" => "Success", "data"=> $discountBadge];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addDiscountBadge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discountBadge' => 'required|string|unique:discountbadges',
            'reqPointsToAchieve' => 'required|numeric',
            'discBadgeDiscountPercent' => 'required|numeric',
            'discountUpToAmount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        DiscountBadges::create($inputs);

        cacheRemove();

        $response = ["status" => "Success", "data" => "DiscountBadge successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editDiscountBadge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discountBadge' => 'required|string|unique:discountbadges,discountBadge,'.$request->discountBadgeId.',discountBadgeId',
            'reqPointsToAchieve' => 'required|numeric',
            'discBadgeDiscountPercent' => 'required|numeric',
            'discountUpToAmount' => 'required|numeric',
            'color' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        DiscountBadges::where('discountBadgeId', $request->discountBadgeId)->update($request->except(['token']));

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteDiscountBadge($discountBadgeId)
    {
        DB::table('discountbadges')->where('discountBadgeId', $discountBadgeId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "DiscountBadge Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    //===================== Discount badge crud========================
    //===================== Discount badge crud========================



    //===================== Discount point========================
    //===================== Discount point========================

    public function getDiscountPoint()
    {
        $discountpoint = DB::table('discountpoints')->first();
        $response = ["status" => "Success", "data"=> $discountpoint];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function discountPointUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        DB::table('discountpoints')->where('discountPointId', $request->discountPointId)
        ->update([
            'amount'=> $request->amount
        ]);

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function enableCustToHoldBadgeDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discountBadgeId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::table('users')->where('id', Auth::user()->id)
        ->update([
            'discountBadgeId'=> $request->discountBadgeId
        ]);

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    //===================== Discount point========================
    //===================== Discount point========================



    //===================== Discount badge uses crud========================
    //===================== Discount badge uses crud========================
    public function getTotAvailValidDiscBadgePointsForCU()
    {
        $discbadgeusespoints = Cache::rememberForever('discbadgeuses', function () {
            return DB::table('discbadgeuses')->where('userId', Auth::user()->id)->sum('pointsUsed');
        });

        $cartvalidpoints = Cache::rememberForever('cartvalidpoints', function () {
            return DB::table('cart')->where('userId', Auth::user()->id)->where('isPointValid', 1)->sum('points');
        });

        $availablepoints = $cartvalidpoints-$discbadgeusespoints;

        $response = ["status" => "Success", "points"=> $availablepoints];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }
    //===================== Discount badge uses crud========================
    //===================== Discount badge uses crud========================


}
