<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Reviews;


class ReviewController extends Controller
{


    public function getAllReviews()
    {
        $reviews = DB::table('reviews_view')->get();

        $response = ["status" => "Success", "data"=> $reviews];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getProductReviews($productId)
    {
        $reviews = DB::table('reviews_view')->where('productId', $productId )->get();

        $response = ["status" => "Success", "data"=> $reviews];
        return response(json_encode($response, JSON_NUMERIC_CHECK ), 200, ["Content-Type" => "application/json"]);
    }


    public function getProductReviewsWithPaginate($productId)
    {
        $reviews = DB::table('reviews_view')->where('productId', $productId )->orderBy('reviewId', 'DESC')->paginate(5);

        $response = ["status" => "Success", "data"=> $reviews];
        return response(json_encode($response, JSON_NUMERIC_CHECK ), 200, ["Content-Type" => "application/json"]);
    }


    public function addReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|string',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        if ( isset($request->token) && strlen($request->token)>10) {
            $request['reviewerId'] = auth()->user()->id;
        }

        $inputs = $request->all();
        Reviews::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "Review successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
