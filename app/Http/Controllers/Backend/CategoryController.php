<?php
namespace App\Http\Controllers\Backend;
use Validator;

use Carbon\Carbon;
use App\Categories;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{

    public function getCategories()
    {
        $categories = Cache::rememberForever('categories',  function () {
            return DB::table('categories_view')->get();
        });

        $response = ["status" => "Success", "data"=> $categories];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getCategory($categoryId)
    {
        $category = DB::table('categories')->where('categoryId', $categoryId)->first();
        $response = ["status" => "Success", "data"=> $category];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getCategoryWithSlug($categorySlug)
    {
        $category = DB::table('categories')->where('categorySlug', $categorySlug)->first();
        $response = ["status" => "Success", "data"=> $category];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|unique:categories',
            'categorySlug' => 'required|string|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except(['picPath', 'token', '_method']);
        Categories::create($inputs);

        $categoryId = DB::getPdo()->lastInsertId();


        if ( $request->has('picPath') && $request->picPath !=null &&  strlen($request->picPath)>100) {
            $png_url = "category-".$categoryId.'_'.rand(999,9999999).".png";
            $data = substr($request->picPath, strpos($request->picPath, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('categoriesUploads')->put($png_url, $data);
            Categories::find($categoryId)->update(['picPath' => '/uploads/categories/'.$png_url]);
        }

        cacheRemove();

        $response = ["status" => "Success", "data" => "Category successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|unique:categories,category,'.$request->categoryId.',categoryId',
            'categorySlug' => 'required|string|unique:categories,categorySlug,'.$request->categoryId.',categoryId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Categories::where('categoryId', $request->categoryId)->update($request->except(['token', 'picPath']));

        if(!request('parentCategoryId'))
        {
            Categories::where('categoryId', $request->categoryId)->update([
                'parentCategoryId' => null
            ]);
        }


        if ( $request->has('picPath') && $request->picPath !=null &&  strlen($request->picPath)>100) {
            $png_url = "category-".$request->categoryId.'_'.rand(999,9999999).".png";
            $data = substr($request->picPath, strpos($request->picPath, ',') + 1);
            $data = base64_decode($data);
            Storage::disk('categoriesUploads')->put($png_url, $data);
            Categories::find($request->categoryId)->update(['picPath' => '/uploads/categories/'.$png_url]);
        }

        if ( !isset($request->picPath) || $request->picPath == null)
        {
            Categories::find($request->categoryId)->update(['picPath' => '']);
        }
        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteCategory($categoryId)
    {
        DB::table('categories')->where('categoryId', $categoryId)->delete();
        cacheRemove();

        $response = ["status" => "Success", "data" => "Category Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
