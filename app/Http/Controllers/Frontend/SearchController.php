<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{

    public function getSearchProducts($searchInput)
    {
        $searchProducts = DB::table('products_view')
                            ->where('productName', 'like', '%'.$searchInput.'%')
                            ->orWhere('searchTags', 'like', '%'.$searchInput.'%')
                            ->get();
        $response = ["status" => "Success", "data"=> $searchProducts];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSearchProductsWithDiscountCalculationWP($searchInput)
    {
        $products = DB::table('products_view')
                            ->where('productName', 'like', '%'.$searchInput.'%')
                            ->orWhere('searchTags', 'like', '%'.$searchInput.'%')
                            ->paginate(20);

        $data = [];
        foreach ($products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $paginationData = collect([]);
        if(!$products->isEmpty())
        {
            $row_set['current_page']=$products->currentPage();
            $row_set['last_page']=$products->lastPage();
            $row_set['perPage']=$products->perPage();
            $row_set['total']=$products->total();
            $row_set['path']=$products->path();
            $row_set['searchInput']=$searchInput;
            $paginationData = $row_set;
        }
        // , 'products'=> $products

        $response = ["status" => "Success", "data" => $data, 'paginationData' => $paginationData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getSearchProductsWithDiscountCalculation($searchInput)
    {
        $products = Cache::rememberForever('getSearchProductsWithDiscountCalculationproducts',  function () {
            return DB::table('products_view')->get();
        });

        $searchProducts = $products->filter(function ($item) use($searchInput)  {
            return preg_match('/'.$searchInput.'/i', $item->productName) || preg_match('/'.$searchInput.'/i', $item->searchTags) ;
        });

        $data = [];
        foreach ($searchProducts as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $response = ["status" => "Success", "data" => $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSearchCategoryProducts($category)
    {
        $searchProducts = DB::table('products_view')
                            ->where('categories', 'like', '%'.$category.'%')
                            ->get()
                            ->unique('productId');
        $response = ["status" => "Success", "data"=> $searchProducts];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getSearchCategoryProductsWithDiscountCalculation($categorySlug)
    {

        $categories = Cache::rememberForever('categories',  function () {
            return DB::table('categories')->get();
        });

        $categoryId = $categories->where('categorySlug', $categorySlug)->pluck('categoryId')->first();

        $products = Cache::rememberForever('products',  function () {
            return DB::table('product_categories_tree_view')
                ->whereNotNull('categoryId')
                ->whereNotNull('productId')
                ->get();
        });

        $searchProducts1 = $products->where('categoryId', $categoryId);
        $searchProducts2 = $products->where('parentCategoryId', $categoryId);

        $searchProducts = $searchProducts1->merge($searchProducts2);
        $searchProducts = $searchProducts->unique('productId');


        $data = [];
        foreach ($searchProducts as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $response = ["status" => "Success", "data" => $data];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSearchCategoryProductsWithDiscountCalculationWP($categorySlug)
    {
        $categories = Cache::rememberForever('categories',  function () {
            return DB::table('categories')->get();
        });
        $categoryId = $categories->where('categorySlug', $categorySlug)->pluck('categoryId')->first();

        $products =  DB::table('product_categories_tree_view')
                    ->where([
                        ['categoryId', '!=', null],
                        ['productId', '!=', null],
                        ['categoryId', '=', $categoryId],

                    ])
                    ->orWhere([
                        ['categoryId', '!=', null],
                        ['productId', '!=', null],
                        ['parentCategoryId', '=', $categoryId],
                    ])
                    ->orderByDesc( 'sellingPrice', 'outOfStock' )
                    ->paginate(20);

        $data = [];
        foreach ($products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $paginationData = collect([]);
        if(!$products->isEmpty())
        {
            $row_set['current_page']=$products->currentPage();
            $row_set['last_page']=$products->lastPage();
            $row_set['perPage']=$products->perPage();
            $row_set['total']=$products->total();
            $row_set['path']=$products->path();
            $row_set['categorySlug']=$categorySlug;
            $paginationData = $row_set;
        }
        // , 'products'=> $products

        $response = ["status" => "Success", "data" => $data, 'paginationData' => $paginationData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSearchBrandProductsWithDiscountCalculationWP($brandSlug)
    {
        $brandId = DB::table('brand')->where('brandSlug', $brandSlug)->pluck('brandId')->first();

        $products =  DB::table('products_view')
                    ->where([
                        ['brandId', '!=', null],
                        ['brandId', '=', $brandId],
                    ])
                    ->orderByDesc( 'sellingPrice', 'outOfStock' )
                    ->paginate(20);

        $data = [];
        foreach ($products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $paginationData = collect([]);
        if(!$products->isEmpty())
        {
            $row_set['current_page']=$products->currentPage();
            $row_set['last_page']=$products->lastPage();
            $row_set['perPage']=$products->perPage();
            $row_set['total']=$products->total();
            $row_set['path']=$products->path();
            $row_set['brandSlug']=$brandSlug;
            $paginationData = $row_set;
        }
        // , 'products'=> $products

        $response = ["status" => "Success", "data" => $data, 'paginationData' => $paginationData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSearchLimitedEditionProductsWithDiscountCalculationWP($limitedEditionSlug)
    {

        $products =  DB::table('products_view')
                    ->where('isLimitedEdition', 1)
                    ->paginate(20);

        $data = [];
        foreach ($products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $paginationData = collect([]);
        if(!$products->isEmpty())
        {
            $row_set['current_page']=$products->currentPage();
            $row_set['last_page']=$products->lastPage();
            $row_set['perPage']=$products->perPage();
            $row_set['total']=$products->total();
            $row_set['path']=$products->path();
            $row_set['limitedEditionSlug']=$limitedEditionSlug;
            $paginationData = $row_set;
        }
        // , 'products'=> $products

        $response = ["status" => "Success", "data" => $data, 'paginationData' => $paginationData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSearchNewArrivalProductsWithDiscountCalculationWP($newArrivalSlug)
    {

        $products =  DB::table('products_view')
                    ->where('isNewArrival', 1)
                    ->paginate(20);

        $data = [];
        foreach ($products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $paginationData = collect([]);
        if(!$products->isEmpty())
        {
            $row_set['current_page']=$products->currentPage();
            $row_set['last_page']=$products->lastPage();
            $row_set['perPage']=$products->perPage();
            $row_set['total']=$products->total();
            $row_set['path']=$products->path();
            $row_set['newArrivalSlug']=$newArrivalSlug;
            $paginationData = $row_set;
        }
        // , 'products'=> $products

        $response = ["status" => "Success", "data" => $data, 'paginationData' => $paginationData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getSearchGiftTypeProductsWithDiscountCalculationWP($giftTypeSlug)
    {

        $products =  DB::table('products_view')
                    ->where('giftTypes', $giftTypeSlug)
                    ->orderByDesc( 'sellingPrice', 'outOfStock' )
                    ->paginate(20);

        $data = [];
        foreach ($products as $key => $value)
        {
            $data[$key] = $value;

            $productId = $data[$key]->productId;
            list($discountPercent, $discountAmount) = getProductDiscount($productId);

            $data[$key]->discountPercent = $discountPercent;
            $data[$key]->discountAmount = $discountAmount;

        }

        $paginationData = collect([]);
        if(!$products->isEmpty())
        {
            $row_set['current_page']=$products->currentPage();
            $row_set['last_page']=$products->lastPage();
            $row_set['perPage']=$products->perPage();
            $row_set['total']=$products->total();
            $row_set['path']=$products->path();
            $row_set['giftTypeSlug']=$giftTypeSlug;
            $paginationData = $row_set;
        }
        // , 'products'=> $products

        $response = ["status" => "Success", "data" => $data, 'paginationData' => $paginationData];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }







}
