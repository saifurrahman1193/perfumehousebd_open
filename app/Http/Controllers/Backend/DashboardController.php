<?php
namespace App\Http\Controllers\Backend;
use App\Uom;

use Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


class DashboardController extends Controller
{
    public function getSalesDashboardReport()
    {
        $salesData = Cache::rememberForever('getSalesDashboardReport', function () {
            return DB::table('salesdashboardreport_view')->get();
        });

        $response = ["status" => "Success", "data"=> $salesData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


}
