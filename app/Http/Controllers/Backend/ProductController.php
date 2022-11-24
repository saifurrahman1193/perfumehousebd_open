<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Products;
use App\FeaturedProducts;
use App\LimitedEditions;
use App\NewArrivals;
use App\LatestProducts;
use App\BestsellingProducts;
use App\TopratedProducts;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class ProductController extends Controller
{

    public function getProducts()
    {
        $products = Cache::rememberForever('getProducts', function () {
            return DB::table('products_view')->get();
        });

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getProductsWithProductIds(Request $request)
    {
        $products = Cache::rememberForever('getProductsWithProductIds', function () {
            return DB::table('products_view')->get();
        });

        $products = $products->whereIn('productId', $request->products);

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }



    public function getRandomProducts($numberOfProductReturn)
    {
        $products = DB::table('products_view')->take($numberOfProductReturn)->get();

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getLastNNumberProducts($numberOfProductReturn, $rangeStart=1)
    {
        $products = Cache::rememberForever('getLastNNumberProducts', function () {
            return DB::table('products_view')->orderBy('sellingPrice', 'desc')->get();
        });

        if ($rangeStart>0 && $numberOfProductReturn>0) {
            $products=$products->slice($rangeStart, $numberOfProductReturn);
        }
        else {
            $products=$products->take($numberOfProductReturn);
        }

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




    public function getProductCategories()
    {
        $products_with_categories = DB::table('productcategories_view')->get();
        $response = ["status" => "Success", "data"=> $products_with_categories];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




    public function getRelatedProducts()
    {
        $products = DB::table('products_view')->orderBy(DB::raw('RAND()'))->take(8)->get();

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getProductPictures()
    {
        $productpictures = Cache::rememberForever('getProductPictures', function ()  {
            return DB::table('productpictures')->orderBy('isDefault', 'DESC')->get();
        });

        $response = ["status" => "Success", "data"=> $productpictures];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getSingleProductPictures($productId)
    {
        $productpictures = Cache::rememberForever('getSingleProductPictures', function ()  {
            return DB::table('productpictures')->orderBy('isDefault', 'DESC')->get();
        });
        $productpictures = $productpictures->where('productId', $productId);

        $response = ["status" => "Success", "data"=> $productpictures];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getProductPicturesWithProductIds(Request $request)
    {
        $productpictures = Cache::rememberForever('getProductPicturesWithProductIds', function ()  {
            return DB::table('productpictures')->orderBy('isDefault', 'DESC')->get();
        });

        $productIds = $request->productIds;
        $productpictures = $productpictures->whereIn('productId', $productIds);

        $response = ["status" => "Success", "data"=> $productpictures];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




    public function getProduct($productId)
    {
        $product = DB::table('products')->where('productId', $productId)->first();
        $response = ["status" => "Success", "data"=> $product];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getProductAllData($productId)
    {
        $product = DB::table('products_view')->where('productId', $productId)->first();
        $response = ["status" => "Success", "data"=> $product];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getGiftProducts()
    {
        $products = DB::table('giftproducts_view')->get();
        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }



    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productName' => 'required|string',
            // 'sku' => 'required|string|unique:products',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method', 'categoryIds', 'giftTypeIds']);
        Products::create($inputs);

        $productId = DB::getPdo()->lastInsertId();


        if($request->has('categoryIds') and $request->categoryIds != null  )
        {
            DB::table('productcategories')->where('productId', $productId)->delete();

            foreach ($request->categoryIds as $categoryId)
            {
                DB::table('productcategories')->insert([
                    'productId' => $productId,
                    'categoryId' => $categoryId,
                ]);
            }
        }


        if($request->has('giftTypeIds')  and $request->giftTypeIds != null  )
        {
            DB::table('giftproducts')->where('productId', $productId)->delete();

            foreach ($request->giftTypeIds as $gifttypeid)
            {
                DB::table('giftproducts')->insert([
                    'productId' => $productId,
                    'giftTypeId' => $gifttypeid,
                ]);
            }
        }

        cacheRemove();


        $response = ["status" => "Success", "data" => "Product successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function addProductPicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
            'productPicturePath' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);


            //decode base64 string
            $png_url = "product-".$request->productId.'_'.rand(999,9999999).".png";

            // local path
            $path = public_path().'/uploads/products/' . $png_url;


            //    Image::make(file_get_contents($request->photoPath))->save($path);
            $data = substr($request->productPicturePath, strpos($request->productPicturePath, ',') + 1);
            // dd($data);
            $data = base64_decode($data);
            // dd($data);
            Storage::disk('productUploads')->put($png_url, $data);
            // dd('success');



            DB::table('productpictures')->insert(['productPicturePath' => '/uploads/products/'.$png_url, 'productId'=>$request->productId]);

            cacheRemove();


        $response = ["status" => "Success", "data" => "Picture successfully added!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'productName' => 'required|string|unique:products,productName,'.$request->productId.',productId',
            'productName' => 'required|string',
            // 'sku' => 'required|string|unique:products,sku,'.$request->productId.',productId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        // forcing null if not exist on update
        if(!isset($request->genderId)) { $request['genderId'] = null; }
        if(!isset($request->applicationAreaId)) { $request['applicationAreaId'] = null; }
        if(!isset($request->productTypeId)) { $request['productTypeId'] = null; }
        if(!isset($request->countryId)) { $request['countryId'] = null; }
        if(!isset($request->formulationId)) { $request['formulationId'] = null; }
        if(!isset($request->skinTypeId)) { $request['skinTypeId'] = null; }

        Products::where('productId', $request->productId)->update($request->except(['token', 'categoryIds', 'giftTypeIds']));


        if($request->has('categoryIds')  )
        {
            DB::table('productcategories')->where('productId', $request->productId)->delete();

            foreach ($request->categoryIds as $categoryId)
            {
                DB::table('productcategories')->insert([
                    'productId' => $request->productId,
                    'categoryId' => $categoryId,
                ]);
            }
        }


        if($request->has('giftTypeIds')  )
        {
            DB::table('giftproducts')->where('productId', $request->productId)->delete();

            foreach ($request->giftTypeIds as $gifttypeid)
            {
                DB::table('giftproducts')->insert([
                    'productId' => $request->productId,
                    'giftTypeId' => $gifttypeid,
                ]);
            }
        }

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteProduct($productId)
    {
        DB::table('products')->where('productId', $productId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Product Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteProductPicture($productPictureId)
    {
        $productPicturePath = DB::table('productpictures')->where('productPictureId', $productPictureId)->pluck('productPicturePath')->first();

        try {
            unlink(public_path().$productPicturePath);
        } catch (\Throwable $th) {
        }


        DB::table('productpictures')->where('productPictureId', $productPictureId)->delete();

        cacheRemove();

        $response = ["status" => "Success", "data" => "Product picture successfully deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }



    public function setProductPictureDefault($productPictureId)
    {
        $productId = DB::table('productpictures')->where('productPictureId', $productPictureId)->pluck('productId')->first();
        DB::table('productpictures')->where('productId', $productId)->update([
            'isDefault'=> 0
        ]);

        DB::table('productpictures')->where('productPictureId', $productPictureId)->update([
            'isDefault'=> 1
        ]);

        cacheRemove();

        $response = ["status" => "Success", "data" => "Product picture successfully set as default!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // featured products
    // featured products
    public function getFeaturedProducts(Request $request)
    {
        $products = Cache::rememberForever('getFeaturedProducts',  function ()  {
            return DB::table('featuredproducts_view')->get();
        });

        if (request()->has('numberOfProductShows') && request('numberOfProductShows') != null ) {
            $products = $products->take(5);
        }

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addFeaturedProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        FeaturedProducts::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Product successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function deleteFeaturedProduct($featuredProductId)
    {
        DB::table('featuredproducts')->where('featuredProductId', $featuredProductId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Featured Product Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function deleteFeaturedProductWithProductId($productId)
    {
        DB::table('featuredproducts')->where('productId', $productId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Featured Product Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // featured products
    // featured products



    // latest products
    // latest products
    public function getLatestProducts()
    {

        $products = Cache::rememberForever('getLatestProducts', function ()  {
            return DB::table('latestproducts_view')->get();
        });

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addLatestProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        LatestProducts::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Product successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function deleteLatestProduct($latestProductId)
    {
        DB::table('latestproducts')->where('latestProductId', $latestProductId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Latest Product Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // latest products
    // latest products





    // best selling products
    // best selling products
    public function getBestsellingProducts()
    {
        $products = Cache::rememberForever('getBestsellingProducts', function () {
            return DB::table('bestsellingproducts_view')->get();
        });

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addBestsellingProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        BestsellingProducts::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Product successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function deleteBestsellingProduct($bestsellingProductId)
    {
        DB::table('bestsellingproducts')->where('bestsellingProductId', $bestsellingProductId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Best selling Product Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // best selling products
    // best selling products



    // top rated products
    // top rated products
    public function getTopratedProducts()
    {

        $products = Cache::rememberForever('getTopratedProducts', function ()  {
            return DB::table('topratedproducts_view')->get();
        });

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addTopratedProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        TopratedProducts::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Product successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function deleteTopratedProduct($topratedProductId)
    {
        DB::table('topratedproducts')->where('topratedProductId', $topratedProductId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Top rated Product Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // top rated products
    // top rated products




    // limited editions
    // limited editions
    public function getLimitedEditions()
    {
        $products = Cache::remember('getLimitedEditions', 5, function () {
            return DB::table('limitededitions_view')->get();
        });

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addLimitedEdition(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        LimitedEditions::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Product successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function deleteLimitedEdition($limitedEditionId)
    {
        DB::table('limitededitions')->where('limitedEditionId', $limitedEditionId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Limited Edition Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // limited editions
    // limited editions


    // new arrivals
    // new arrivals
    public function getNewArrivals()
    {
        $products = Cache::remember('getNewArrivals', 5, function () {
            return DB::table('newarrivals_view')->get();
        });

        $response = ["status" => "Success", "data"=> $products];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addNewArrival(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except([ 'token', '_method']);
        NewArrivals::create($inputs);
        cacheRemove();

        $response = ["status" => "Success", "data" => "Product successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function deleteNewArrival($newArrivalId)
    {
        DB::table('newarrivals')->where('newArrivalId', $newArrivalId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "New Arrival Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    // new arrivals
    // new arrivals


    // product visit

    public function addProductVisit(Request $request)
    {
        $userId = null;
        if (auth()->user()) {
            $userId = auth()->user()->id;
        }

        // dd($request->productId);

        DB::table('productvisit')->insert([
            'productId' => $request->productId,
            'userId' =>$userId ,
        ]);

        $response = ["status" => "Success", "data" => "Successfully added!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    // public function getCurrentUserProductVisits()
    // {
    //     $data=[];
    //     if (auth()->user()) {
    //         $productsIds =  DB::table('productvisit')->where('userId', auth()->user()->id)->whereNotNull('productId')->groupBy('productId')->pluck('productId');

    //         $data = Cache::remember('products', 5, function () use ($productsIds) {
    //             return DB::table('products_view')->whereIn('productId', $productsIds)->get();
    //         });
    //     }

    //     $response = ["status" => "Success", "data"=> $data];
    //     return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    // }

    public function getUniqueDataOfLatestViewsProducts(){

    }

    public function getCurrentUserProductVisits()
    {
        $finalproducts=[];
        if (auth()->user()) {
            // $products =  DB::table('latestviews2_view')->where('userId', auth()->user()->id)->get();
            $productIds =  DB::table('productvisit')->where('userId', auth()->user()->id)->where('productId', '!=', null)
                            ->select('productId')->orderBy('productvisitId', 'DESC')
                            ->distinct()
                            ->select('productId')
                            ->take(15);
            $productIds =  $productIds->pluck('productId');
            // dd($productIds->pluck('productId'));


            $products = Cache::rememberForever('getCurrentUserProductVisitsgetProducts', function () {
                return DB::table('products_view')->get();
            });


            $products =  $products->whereIn('productId', $productIds);
            foreach ($productIds as $key =>  $productId)
            {
                $product = $products->where('productId', $productId)->first();
                array_push($finalproducts, $product);
            }
        }

        $response = ["status" => "Success", "data"=> $finalproducts];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }



    public function addWishlistall(Request $request)
    {
        $userId = null;
        if (auth()->user()) {
            $userId = auth()->user()->id;
        }

        DB::table('wishlistall')->insert([
            'productId' => $request->productId,
            'userId' =>$userId ,
        ]);

        $response = ["status" => "Success", "data" => "Successfully added!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addComparelist(Request $request)
    {
        $userId = null;
        if (auth()->user()) {
            $userId = auth()->user()->id;
        }

        DB::table('comparelist')->insert([
            'productId' => $request->productId,
            'userId' =>$userId ,
        ]);

        $response = ["status" => "Success", "data" => "Successfully added!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function verifiedReviewer($productId)
    {
        $userId = null;
        if (auth()->user()) {
            $userId = auth()->user()->id;
        }

        $verifiedReviewers = Cache::rememberForever('verifiedReviewer',  function () {
            return DB::table('verifiedreviewer_view')->get();
        });

        $verifiedReviewer = $verifiedReviewers->where('userId', '!=', null)->where('userId', $userId)->where('productId', $productId)->count('productId');

        $response = ["status" => "Success", "data"=> $verifiedReviewer];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }







}
