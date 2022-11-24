<?php
namespace App\Http\Controllers\Backend;
use App\Sitemap;

use Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public function getSitemaps()
    {
        $sitemaps =  DB::table('sitemap')->get();

        $response = ["status" => "Success", "data"=> $sitemaps];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getSitemap($id)
    {
        $sitemap = DB::table('sitemap')->where('id', $id)->first();
        $response = ["status" => "Success", "data"=> $sitemap];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addSitemap(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sitemap' => 'required|string|unique:sitemap',
            'sitemapUrl' => 'required|string|unique:sitemap',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        Sitemap::create($inputs);

        cacheRemove();

        $response = ["status" => "Success", "data" => "Sitemap successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editSitemap(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sitemap' => 'required|string|unique:sitemap,sitemap,'.$request->id.',id',
            'sitemapUrl' => 'required|string|unique:sitemap,sitemapUrl,'.$request->id.',id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Sitemap::where('id', $request->id)->update($request->except(['token']));

        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteSitemap($id)
    {
        DB::table('sitemap')->where('id', $id)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Sitemap Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function generateSitemap(Request $request)
    {
        $dir = 'sitemap.xml';

        try {
            unlink($dir);
        } catch (\Throwable $th) {
        }

        $fullSitemapInit = '<?xml version="1.0" encoding="UTF-8"?>'. PHP_EOL.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'. PHP_EOL;
        $fullSitemapEnd = PHP_EOL.'</urlset>';

        $sitemapsData = DB::table('sitemap')->get();

        $fullSitemapUrls='';

        // only sitemaps from database sitemap url which is stored from admin panel
        foreach ($sitemapsData as $sitemap)
        {
            $fullSitemapUrls = $fullSitemapUrls.'<url>'.PHP_EOL.
                '<loc>'.$request->url.$sitemap->sitemapUrl.'</loc>'.PHP_EOL.
                '<lastmod>'.getToday().'</lastmod>'.PHP_EOL.
                '<changefreq>daily</changefreq>'.PHP_EOL.
                '<priority>0.8</priority>'.PHP_EOL.
            '</url>'.PHP_EOL;
        }

        // all gifttypes products
        $gifttypes = DB::table('gifttypes')->get();
        foreach ($gifttypes as $gifttype)
        {
            $fullSitemapUrls = $fullSitemapUrls.'<url>'.PHP_EOL.
                '<loc>'.$request->url.'/productlist?giftTypeSlug='.$gifttype->giftTypeSlug.'</loc>'.PHP_EOL.
                '<lastmod>'.getToday().'</lastmod>'.PHP_EOL.
                '<changefreq>daily</changefreq>'.PHP_EOL.
                '<priority>0.8</priority>'.PHP_EOL.
            '</url>'.PHP_EOL;
        }

        // all categories products
        $categories = DB::table('categories')->get();
        foreach ($categories as $category)
        {
            $fullSitemapUrls = $fullSitemapUrls.'<url>'.PHP_EOL.
                '<loc>'.$request->url.'/productlist?categorySlug='.$category->categorySlug.'</loc>'.PHP_EOL.
                '<lastmod>'.getToday().'</lastmod>'.PHP_EOL.
                '<changefreq>daily</changefreq>'.PHP_EOL.
                '<priority>0.8</priority>'.PHP_EOL.
            '</url>'.PHP_EOL;
        }

        // all brands products
        $brands = DB::table('brand')->get();
        foreach ($brands as $brand)
        {
            $fullSitemapUrls = $fullSitemapUrls.'<url>'.PHP_EOL.
                '<loc>'.$request->url.'/productlist?brandSlug='.$brand->brandSlug.'</loc>'.PHP_EOL.
                '<lastmod>'.getToday().'</lastmod>'.PHP_EOL.
                '<changefreq>daily</changefreq>'.PHP_EOL.
                '<priority>0.8</priority>'.PHP_EOL.
            '</url>'.PHP_EOL;
        }

        // all products
        $products = DB::table('products')->get();
        foreach ($products as $product)
        {
            $fullSitemapUrls = $fullSitemapUrls.'<url>'.PHP_EOL.
                '<loc>'.$request->url.'/productdetails/'.Str::slug($product->productName, '-').'/'.$product->productId.'</loc>'.PHP_EOL.
                '<lastmod>'.getToday().'</lastmod>'.PHP_EOL.
                '<changefreq>daily</changefreq>'.PHP_EOL.
                '<priority>0.8</priority>'.PHP_EOL.
            '</url>'.PHP_EOL;
        }


        $fullSitemap = $fullSitemapInit.$fullSitemapUrls.$fullSitemapEnd;

        file_put_contents ( $dir , $fullSitemap);

        return response()->download($dir);
    }





}
