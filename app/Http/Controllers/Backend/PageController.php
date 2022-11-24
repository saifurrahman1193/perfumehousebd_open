<?php
namespace App\Http\Controllers\Backend;
use App\Pages;

use Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Cache;


class PageController extends Controller
{


    public function getPages()
    {
        $pages = Cache::rememberForever('pages',  function () {
            return DB::table('pages')->get();
        });

        $response = ["status" => "Success", "data"=> $pages];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getPage($pageId)
    {
        $page = DB::table('pages')->where('pageId', $pageId)->first();
        $response = ["status" => "Success", "data"=> $page];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getPageWithSlug($pageSlug)
    {
        $page =  DB::table('pages')->where('pageSlug', $pageSlug)->first();
        $response = ["status" => "Success", "data"=> $page];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function editPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pageTitle' => 'required|string|unique:pages,pageTitle,'.$request->pageId.',pageId',
            'pageSlug' => 'required|string|unique:pages,pageSlug,'.$request->pageId.',pageId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Pages::where('pageId', $request->pageId)->update($request->except(['token']));

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }








}
