<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Exports\stockReportFullExport;
use App\Exports\CustomerproductvisitExcelReportExport;
use Excel;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Collection;


class ReportController extends Controller
{

    public function getProductVisits()
    {
        $data =  DB::table('productvisit_view')->get();

        $response = ["status" => "Success", "data"=> $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getProductVisitsMonths($year, $productId=null)
    {
        if ($productId!=null) {
            $productvisitMonths = DB::table('productvisit_view')
                        ->where('productId', $productId)
                        ->where('visityear', $year)
                        ->distinct('productvisitMonthNumber')
                        ->select('productvisitMonthNumber', 'productvisitMonthName')
                        ->orderBy('productvisitMonthNumber')
                        ->get();
        } else {
            $productvisitMonths = DB::table('productvisit_view')
                        ->where('visityear', $year)
                        ->distinct('productvisitMonthNumber')
                        ->select('productvisitMonthNumber', 'productvisitMonthName')
                        ->orderBy('productvisitMonthNumber')
                        ->get();
        }


        $response = ["status" => "Success", "data"=> $productvisitMonths];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getPorductVisitsYears($productId=null)
    {
        if ($productId!=null) {
            $visityear = DB::table('productvisit_view')->where('productId', $productId)->distinct('visityear')->orderBy('visityear')->pluck('visityear');
        }
        else {
            $visityear = DB::table('productvisit_view')->distinct('visityear')->orderBy('visityear')->pluck('visityear');
        }

        $response = ["status" => "Success", "data"=> $visityear];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getMonthlyProductVisitsReport($year, $productId=null)
    {
        if($productId!=null)
        {
            $thisYearMonthlyProductVisitsReport = DB::table('productvisit_view')
                                                ->where('productId', $productId)
                                                ->where('visityear', $year)
                                                ->groupBy('productvisitMonthNumber')
                                                ->select(DB::raw('productvisitMonthName as month , count(productId) as visits'))
                                                ->get();
        }
        else
        {
            $thisYearMonthlyProductVisitsReport = DB::table('productvisit_view')->where('visityear', $year)->groupBy('productvisitMonthNumber')
                                        ->select(DB::raw('productvisitMonthName as month , count(productId) as visits'))
                                        ->get();
        }

        $response = ["status" => "Success", "data"=> $thisYearMonthlyProductVisitsReport];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }



    public function getYearlyProductVisitsReport($productId=null)
    {
        if ($productId!=null) {
            $yearlyproductvisitsReport = DB::table('productvisit_view')
                            ->where('productId', $productId)
                            ->groupBy('visityear')
                            ->select(DB::raw('visityear as year , count(productId) as visits'))
                            ->get();
        } else {
            $yearlyproductvisitsReport = DB::table('productvisit_view')
                            ->groupBy('visityear')
                            ->select(DB::raw('visityear as year , count(productId) as visits'))
                            ->get();
        }


        $response = ["status" => "Success", "data"=> $yearlyproductvisitsReport];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getDailyProductVisitsReport($year, $monthNumber, $productId=null)
    {
        if ($productId!=null) {
            $dailyproductvisitsReport = DB::table('productvisit_view')
                            ->where('productId', $productId)
                            ->where('visityear', $year)
                            ->where('productvisitMonthNumber', $monthNumber)
                            ->groupBy('productvisitDayNumber')
                            ->select(DB::raw('concat(productvisitDayNumber,"-",productvisitDayNameShort) as day , count(productId) as visits'))
                            ->orderBy('productvisitDayNumber')
                            ->get();
        } else {
            $dailyproductvisitsReport = DB::table('productvisit_view')
                            ->where('visityear', $year)
                            ->where('productvisitMonthNumber', $monthNumber)
                            ->groupBy('productvisitDayNumber')
                            ->select(DB::raw('concat(productvisitDayNumber,"-",productvisitDayNameShort) as day , count(productId) as visits'))
                            ->orderBy('productvisitDayNumber')
                            ->get();
        }


        $response = ["status" => "Success", "data"=> $dailyproductvisitsReport];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getCustProductVisitReportCategories()
    {
        $categories =  DB::table('categories')->select('categoryId', 'category')->orderBy('categoryId')->get();

        $categories = $categories->prepend((object)['categoryId'=> -1, 'category'=> 'All' ]);

        $gifttypes =  DB::table('gifttypes')->orderBy('giftTypeId')->get();

        $gifttypesforcategories = new Collection();

        foreach ($gifttypes as $key => $gifttype)
        {
            $gifttypesforcategories->push((object) [ 'categoryId' =>1000+$gifttype->giftTypeId, 'category' =>$gifttype->giftType ]);
        }


        $data = $categories->merge($gifttypesforcategories);

        $response = ["status" => "Success", "data"=> $data];
        return response(json_encode($response, JSON_NUMERIC_CHECK)  , 200, ["Content-Type" => "application/json"]);
    }


    public function getCustProductVisitReportOld(Request $request)
    {
        $report_data =  DB::table('customerproductvisit_view')->get();

        // ==================category restriction================
        if (isset($request->categoryId) && $request->categoryId!=null)
        {
            $categoryId = $request->categoryId;

            // -1 = all
            // no need to code

            // 1 to 1000 = categoryId
            if ($categoryId>0 && $categoryId<=1000)
            {
                $report_data = $report_data->where('categoryId', $categoryId);
            }
            // >= 1001 = giftTypeId
            if ($categoryId>1000 )
            {
                $giftTypeId = $categoryId%1000;
                $giftproducts = DB::table('giftproducts')->where('giftTypeId', $giftTypeId)->pluck('productId');
                $report_data = $report_data->whereIn('productId', $giftproducts);
            }
        }
        // ==================category restriction================


        // ==================customer restriction================
        if (isset($request->customerId) && $request->customerId!=null)
        {
            $customerId = $request->customerId;
            $report_data = $report_data->where('customerId', $customerId);
        }
        // ==================customer restriction================


        // ==================product restriction================
        if (isset($request->productId) && $request->productId!=null)
        {
            $productId = $request->productId;
            $report_data = $report_data->where('productId', $productId);
        }
        // ==================product restriction================


        // ==================year restriction================
        if (isset($request->year) && $request->year!=null)
        {
            $year = $request->year;
            $report_data = $report_data->where('visityear', $year);
        }
        // ==================year restriction================


        // ==================startDate restriction================
        if (isset($request->startDate) && $request->startDate!=null)
        {
            $startDate = $request->startDate;
            $report_data = $report_data->where('visitDate', '>=', $startDate);
        }
        // ==================startDate restriction================

        // ==================endDate restriction================
        if (isset($request->endDate) && $request->endDate!=null)
        {
            $endDate = $request->endDate;
            $report_data = $report_data->where('visitDate', '<=', $endDate);
        }
        // ==================endDate restriction================


        // // ==================sorting restriction================
        // if (isset($request->sortBy) && $request->sortBy!=null)
        // {
        //     $sortBy = $request->sortBy;
        //     switch ($sortBy) {
        //         case "all_cust_prod_hit_count_asc":
        //             $report_data = $report_data->sortBy('all_cust_prod_hit_count');
        //             break;
        //         case "all_cust_prod_hit_count_desc":
        //             $report_data = $report_data->sortByDesc('all_cust_prod_hit_count');
        //             break;
        //         case "date_asc":
        //             $report_data = $report_data->sortBy([
        //                                 ['visityear', 'asc'],
        //                                 ['visitMonthNumber', 'asc'],
        //                                 ['visitDayNumber', 'asc'],
        //                             ]);
        //             break;
        //         default:
        //             $report_data = $report_data->sortBy([
        //                 ['visityear', 'desc'],
        //                 ['visitMonthNumber', 'desc'],
        //                 ['visitDayNumber', 'desc'],
        //             ]);
        //     }
        // }
        // // ==================sorting restriction================



        $response = ["status" => "Success", "data"=> $report_data];
        return response(json_encode($response, JSON_NUMERIC_CHECK)  , 200, ["Content-Type" => "application/json"]);
    }


    public function getCustProductVisitReport(Request $request)
    {
        $product_customers =  DB::table('productvisit')
                            ->select('productcategories.categoryId', 'categories.category', 'products.productId', 'products.productName as productName', 'productvisit.userId AS customerId', 'users.name as name', 'users.phone as phone')
                            ->leftJoin('products', 'products.productId', 'productvisit.productId')
                            ->leftJoin('users', 'users.id', 'productvisit.userId')
                            ->leftJoin('productcategories', 'productcategories.productId', 'products.productId')
                            ->leftJoin('categories', 'productcategories.categoryId', 'categories.categoryId')
                            ->distinct()
                            ->get();
        $custproductvisits = DB::table('productvisit')
                            ->selectRaw('productvisit.productvisitId, productvisit.productId, productvisit.userId AS customerId, YEAR(productvisit.created_at) AS visitYear, DATE_FORMAT(productvisit.created_at, "%Y-%m-%d") as visitDate')
                            ->get();

                            // dd($custproductvisits);

        $report_data = collect([]);
        foreach ($product_customers as $i => $product_customer) {

            $single_custproductvisits_data = $custproductvisits->where('productId', $product_customer->productId)->where('customerId', $product_customer->customerId);

            $custFirstProductVisitDate = $single_custproductvisits_data->pluck('visitDate')->first() ?? '';
            $custLastProductVisitDate = $single_custproductvisits_data->sortByDesc('productvisitId')->pluck('visitDate')->first() ?? '';

            ($custFirstProductVisitDate==$custLastProductVisitDate) ?  $custLastProductVisitDate ='' : null;

            $report_data[$i] = [
                'categoryId' => $product_customer->categoryId,
                'category' => $product_customer->category,
                'customerId' => $product_customer->customerId,
                'name' => $product_customer->name,
                'phone' => $product_customer->phone,
                'productId' => $product_customer->productId,
                'productName' => $product_customer->productName,
                'productPicturePath' => getProductImage($product_customer->productId),
                'custFirstProductVisitDate' => $custFirstProductVisitDate,
                'custLastProductVisitDate' => $custLastProductVisitDate,
            ];
        }

        // ==================category restriction================
        if (isset($request->categoryId) && $request->categoryId!=null)
        {
            $categoryId = $request->categoryId;

            // -1 = all
            // no need to code

            // 1 to 1000 = categoryId
            if ($categoryId>0 && $categoryId<=1000)
            {
                $report_data = $report_data->where('categoryId', $categoryId);
            }
            // >= 1001 = giftTypeId
            if ($categoryId>1000 )
            {
                $giftTypeId = $categoryId%1000;
                $giftproducts = DB::table('giftproducts')->where('giftTypeId', $giftTypeId)->pluck('productId');
                $report_data = $report_data->whereIn('productId', $giftproducts);
            }
        }
        // ==================category restriction================

        // ==================customer restriction================
        if (isset($request->customerId) && $request->customerId!=null)
        {
            $customerId = $request->customerId;
            $report_data = $report_data->where('customerId', $customerId);
        }
        // ==================customer restriction================


        // ==================product restriction================
        if (isset($request->productId) && $request->productId!=null)
        {
            $productId = $request->productId;
            $report_data = $report_data->where('productId', $productId);
        }
        // ==================product restriction================


        $report_data = $report_data->map(function ($record, $i) use($request, $custproductvisits) {

            $custproductvisits = $custproductvisits->where('productId', $record['productId']);

            $cust_prod_hit_count = 0;
            $all_cust_prod_hit_count = 0;

            // dd($custproductvisits);
            // ==================startDate restriction================
            if (isset($request->startDate) && $request->startDate!=null)
            {
                $custproductvisits = $custproductvisits->where('visitDate', '>=', $request->startDate);
            }
            // ==================startDate restriction================


            // ==================endDate restriction================
            if (isset($request->endDate) && $request->endDate!=null)
            {
                $custproductvisits = $custproductvisits->where('visitDate', '<=', $request->endDate);
            }
            // ==================endDate restriction================

            // ==================year restriction================
            if (isset($request->year) && $request->year!=null)
            {
                $custproductvisits = $custproductvisits->where('visitYear', $request->year);
            }
            // ==================year restriction================

            // dd($custproductvisits);


            $cust_prod_hit_count = $custproductvisits->where('customerId', $record['customerId'])->count('productId');
            $all_cust_prod_hit_count = $custproductvisits->count('productId');

            $record['cust_prod_hit_count'] = $cust_prod_hit_count;
            $record['all_cust_prod_hit_count'] = $all_cust_prod_hit_count;

            return $record;
        });


        // ==================sorting restriction================
        if (isset($request->sortBy) && $request->sortBy!=null)
        {
            $sortBy = $request->sortBy;
            switch ($sortBy) {
                case "all_cust_prod_hit_count_asc":
                    $report_data = $report_data->sortBy('all_cust_prod_hit_count');
                    break;
                case "all_cust_prod_hit_count_desc":
                    $report_data = $report_data->sortByDesc('all_cust_prod_hit_count');
                    break;
                case "date_asc":
                    $report_data = $report_data->sortBy('custFirstProductVisitDate');
                    break;
                default:
                    $report_data = $report_data->sortByDesc('custLastProductVisitDate');
            }
        }
        // ==================sorting restriction================


        $response = ["status" => "Success", "data"=> $report_data];
        return response(json_encode($response, JSON_NUMERIC_CHECK)  , 200, ["Content-Type" => "application/json"]);
    }


    public function customerproductvisitExcelReportDownload(Request $request)
    {
        return Excel::download(new CustomerproductvisitExcelReportExport($request->categoryId, $request->customerId, $request->productId, $request->year, $request->startDate, $request->endDate), 'customerproductvisitExcelReport.xlsx');
    }





    public function getWishlistall()
    {
        $data = DB::table('wishlistall_view')->get();

        $response = ["status" => "Success", "data"=> $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getComparelist()
    {
        $data = DB::table('comparelist_view')->get();

        $response = ["status" => "Success", "data"=> $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getMonthlySalesReportold()
    {
        $cartData = DB::table('cart')->get();

        $firstCartDate = YmdTodmY($cartData->pluck('created_at')->first());
        $firstCartDateMonthFirstDate = Carbon::parse($firstCartDate)->startOfMonth()->format('Y-m-d');
        // dd($firstCartDateMonthFirstDate);

        $lastCartDate = YmdTodmY($cartData->pluck('created_at')->last());
        $lastCartDateMonthLastDate = Carbon::parse($lastCartDate)->endOfMonth()->format('Y-m-d');
        // dd($lastCartDateMonthLastDate);

        $dates = DB::table('dates')
                ->where('date', '>=', $firstCartDateMonthFirstDate)
                ->where('date', '<=', $lastCartDateMonthLastDate)
                ->pluck('date');


        dd($dates);



        $response = ["status" => "Success", "data"=> $dates];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getMonthlySalesReport($year)
    {

        $thisYearMonthlySalesReport = DB::table('cart_view')->where('cartYear', $year)->groupBy('cartMonthNumber')
                                        ->select(DB::raw('cartMonthName as month , sum(ifnull(cartProductsTotalSum, 0)) as amount'))
                                        ->get();

        $response = ["status" => "Success", "data"=> $thisYearMonthlySalesReport];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getCartYears()
    {

        $cartYears = DB::table('cart_view')->distinct('cartYear')->orderBy('cartYear')->pluck('cartYear');

        $response = ["status" => "Success", "data"=> $cartYears];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getYearlySalesReport()
    {
        $yearlysalesReport = DB::table('cart_view')
                            ->groupBy('cartYear')
                            ->select(DB::raw('cartYear as year , sum(ifnull(cartProductsTotalSum, 0)) as amount'))
                            ->get();

        $response = ["status" => "Success", "data"=> $yearlysalesReport];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getCartMonths($year)
    {
        $cartMonths = DB::table('cart_view')
                        ->where('cartYear', $year)
                        ->distinct('cartMonthNumber')
                        ->select('cartMonthNumber', 'cartMonthName')
                        ->orderBy('cartMonthNumber')
                        ->get();

        $response = ["status" => "Success", "data"=> $cartMonths];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getDailySalesReport($year, $monthNumber)
    {
        $dailysalesReport = DB::table('cart_view')
                            ->where('cartYear', $year)
                            ->where('cartMonthNumber', $monthNumber)
                            ->groupBy('cartDayNumber')
                            ->select(DB::raw('concat(cartDayNumber,"-",cartDayNameShort) as day , sum(ifnull(cartProductsTotalSum, 0)) as amount'))
                            ->orderBy('cartDayNumber')
                            ->get();

        $response = ["status" => "Success", "data"=> $dailysalesReport];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getValuableCustomers(Request $request)
    {
        $valuableCustomersData = Cache::rememberForever('getValuableCustomersvaluablecustomers',  function () {
            return DB::table('valuablecustomers_view')->orderBy('totalValue', 'DESC')->get();
        });
        $cartsData = Cache::rememberForever('getValuableCustomerscartdata',  function () {
            return DB::table('cart')->get();
        });

        if(request()->has('customerId') and $request->customerId!=null && ($request->customerId>0 || $request->customerId==-1 ))
        {
            if ($request->customerId>0) {
                $valuableCustomersData = $valuableCustomersData->where('id', $request->customerId);
            }

            if (request()->has('startDate') and $request->startDate!=null) {
                $valuableCustomersData = $valuableCustomersData->map(function($record) use($cartsData, $request){
                    $record->totalOrders = $cartsData->where('userId', $record->id)->where('created_at', '>=',$request->startDate)->count('cartId');
                    $record->totalValue = $cartsData->where('userId', $record->id)->where('created_at', '>=',$request->startDate)->sum('cartProductsTotalSum');

                    return $record;
                });

                if (request()->has('endDate') and $request->endDate!=null) {
                    $valuableCustomersData = $valuableCustomersData->map(function($record) use($cartsData, $request){
                        $record->totalOrders = $cartsData->where('userId', $record->id)->where('created_at', '>=',$request->startDate)->where('created_at', '<=',$request->endDate)->count('cartId');
                        $record->totalValue = $cartsData->where('userId', $record->id)->where('created_at', '>=',$request->startDate)->where('created_at', '<=',$request->endDate)->sum('cartProductsTotalSum');

                        return $record;
                    });
                }
            }

        }


        $response = ["status" => "Success", "data"=> $valuableCustomersData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function stockReportFullExcelReportDownload()
    {
        return Excel::download(new stockReportFullExport, 'stockReportFullExport.xlsx');
    }


    public function getDmySalesReportData(Request $request)
    {
        $dmysalesreportData = Cache::rememberForever('dmysalesreportData', function () {
            return DB::table('dmysalesreport_view')->get();
        });

        $dmysalesreportsalesdashboardData = Cache::rememberForever('dmysalesreportsalesdashboardData', function () {
            return DB::table('salesdashboardreport_view')->first();
        });

        // $months = getMonths();

        if(request()->has('filter1') and $request->filter1==1 and request()->has('all') and $request->all==1 )
        {
            $totalSales = $dmysalesreportData->sum('cartProductsTotalSum');
            $totalOrdersPerDayCount = $dmysalesreportData->count('cartId');
            $avgOrdersPerDayCount = $dmysalesreportData->groupBy('cartDate')->count('cartId');
            $dmysalesreportData = $dmysalesreportData->take(1);

            $startDate = $dmysalesreportsalesdashboardData->firstCartDate;
            $endDate = date("Y-m-d");

            $totalDays = getNumberOfDaysFrom2YmdDates($startDate, $endDate);

            $dmysalesreportData = $dmysalesreportData->map(function ($record) use($totalSales,$totalOrdersPerDayCount, $totalDays)
            {
                $record->cartDateYear = null;
                $record->cartDateMonthName =  null;
                $record->cartDate =  null;
                $record->totalSales =  round($totalSales);

                $record->totalOrdersPerDay =  $totalOrdersPerDayCount;
                $record->totalDays =  $totalDays;
                $record->avgOrdersPerDay =  $totalOrdersPerDayCount/$totalDays;

                return $record;
            });
        }
        else if(request()->has('filter1') and $request->filter1==1 and request()->has('startDate') and $request->startDate!=null and request()->has('endDate') and $request->endDate!=null)
        {
            $startDate = request('startDate');
            $endDate = request('endDate');

            $dmysalesreportData = $dmysalesreportData->where('cartDate','>=', $startDate)->where('cartDate','<=', $endDate);

            $totalDays = getNumberOfDaysFrom2YmdDates($startDate, $endDate);

            $totalSales = $dmysalesreportData->sum('cartProductsTotalSum');
            $totalOrdersPerDayCount = $dmysalesreportData->count('cartId');


            $dmysalesreportData = $dmysalesreportData->map(function ($record) use($totalSales,$totalOrdersPerDayCount, $totalDays)
            {
                $record->cartDateYear = null;
                $record->cartDateMonthName =  null;
                $record->cartDate =  null;
                $record->totalSales =  round($totalSales);

                $record->totalOrdersPerDay =  $totalOrdersPerDayCount;
                $record->totalDays =  $totalDays;
                $record->avgOrdersPerDay =  $totalOrdersPerDayCount/$totalDays;

                return $record;
            });
        }
        else if(request()->has('filter2') and $request->filter2==1 )
        {
            $totalData = $dmysalesreportData;
            $dmysalesreportData = $dmysalesreportData->unique('cartDateYear');


            $dmysalesreportData = $dmysalesreportData->map(function ($record) use($totalData, $dmysalesreportsalesdashboardData)
            {
                $record->cartDateMonthName =  null;
                $record->cartDate =  null;
                $record->totalSales =  $totalData->where('cartDateYear', $record->cartDateYear)->sum('cartProductsTotalSum') ;

                $totalOrdersPerDay = $totalData->where('cartDateYear', $record->cartDateYear)->count('cartId');

                $record->totalOrdersPerDay =  $totalOrdersPerDay;

                $totalDays = self::totalDaysCalculationOfYearWithCartStartDate($record->cartDateYear, $dmysalesreportsalesdashboardData->firstCartDate);
                $record->totalDays =  $totalDays;

                $record->avgOrdersPerDay =  $totalOrdersPerDay/$totalDays;

                return $record;
            });
        }
        else if(request()->has('filter3') and $request->filter3==1 and request()->has('year') and $request->year > 0 )
        {
            $year= $request->year;
            $totalData = $dmysalesreportData->where('cartDateYear', $year);
            // $dmysalesreportData = $dmysalesreportData->take(1);
            $dmysalesreportData = $dmysalesreportData->where('cartDateYear', $year)->unique('cartDateMonthNumber');

            $dmysalesreportData = $dmysalesreportData->map(function ($record) use($totalData, $dmysalesreportsalesdashboardData, $year)
            {
                $record->cartDateYear = $year;
                // $record->cartDateMonthName =  $record->cartDateMonthName;
                $record->cartDate =  null;
                $record->totalSales =  $totalData->where('cartDateMonthNumber', $record->cartDateMonthNumber)->sum('cartProductsTotalSum') ;

                $totalOrdersPerDay = $totalData->where('cartDateMonthNumber', $record->cartDateMonthNumber)->count('cartId');

                $record->totalOrdersPerDay =  $totalOrdersPerDay;

                $record->totalDays =  null;

                $record->avgOrdersPerDay =  null;

                return $record;
            });
        }
        else if(request()->has('filter4') and $request->filter4==1 and request()->has('year') and $request->year > 0 and request()->has('monthId') and $request->monthId > 0 )
        {
            $year= $request->year;
            $monthId= $request->monthId;

            $dmysalesreportData = $dmysalesreportData->where('cartDateYear', $year)->where('cartDateMonthNumber', $monthId);
            $totalData = $dmysalesreportData;

            $dmysalesreportData = $dmysalesreportData->unique('cartDate');

            $dmysalesreportData = $dmysalesreportData->map(function ($record) use($totalData,  $year)
            {
                $record->cartDateYear = $year;
                $record->totalSales =  $totalData->where('cartDate', $record->cartDate)->sum('cartProductsTotalSum') ;
                $record->totalOrdersPerDay =  $totalData->where('cartDate', $record->cartDate)->count('cartId');

                return $record;
            });
        }


        $response = ["status" => "Success", "data"=> $dmysalesreportData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function totalDaysCalculationOfYearWithCartStartDate($year, $firstCartDate)
    {
        $days = 0;
        $currentYear = getCurrentYear();
        $firstCartDateYear = getYearFromYmdDate($firstCartDate);

        if ($year==$firstCartDateYear && $year==$currentYear)
        {
            $days = getNumberOfDaysFrom2YmdDates($firstCartDate, getToday());
        }
        else if ($year==$firstCartDateYear)
        {
            $days = getNumberOfDaysFrom2YmdDates($firstCartDate, getSpecificYearLastDate($year));
        }
        else if($year==$currentYear)
        {
            $days = getNumberOfDaysFrom2YmdDates( getCurrentYearFirstDate(), getToday());
        }
        else
        {
            $days = getNumberOfDaysFrom2YmdDates( getSpecificYearFirstDate($year), getSpecificYearLastDate($year));
        }

        return $days;
    }



    public function getCartYearMonths()
    {
        $yearmonths = Cache::rememberForever('getCartYearMonths',  function () {
            return DB::table('cartyearmonths_view')->get();
        });

        $response = ["status" => "Success", "data"=> $yearmonths];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
