<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use App\Models\Category;
use App\Models\Promo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class MarketController extends Controller
{
    public function index(Request $request): Response
    {
        $keyword = "";
        $status_online = "1";
        $category_id = "";
        if ($request->search) {
            $keyword = $request->search;
        }
        if ($request->status_online != "") {
            $status_online = $request->status_online;
        }
        if ($request->category_id != "") {
            $category_id = $request->category_id;
        }
        // dd($request->all());
        $product = Product::select(
            'sma_products.id',
            'sma_products.image1',
            // 'sma_products.image2',
            // 'sma_products.image3',
            // 'sma_products.image4',
            // 'sma_products.code',
            'sma_products.name',
            'sma_brands.name as brand_name',
            'sma_categories.name as category_name',
            'sma_products.status_online',
            'sma_products.price',
        )
            ->leftJoin('sma_brands', 'sma_brands.id', 'sma_products.brand')
            ->leftJoin('sma_categories', 'sma_categories.id', 'sma_products.category_id')
            ->where(function ($query) use ($keyword) {
                return $query->where('sma_products.name', 'like', '%' . $keyword . '%')
                    ->orWhere('sma_products.code', 'like', '%' . $keyword . '%');
            })
            ->where('sma_products.status_online', 1)
            ->orderBy('sma_products.status_atas', 'desc')
            ->paginate(10);


        $category = Category::select(
            'id',
            // 'code',
            'name',
            // 'status_online',
            // 'status_home',
            'warna_bg'
        )->where('status_online', 1)
            ->where('status_home', 1)->get();


        return Inertia::render('Market/Main', [
            'product' => $product,
            'search' => $keyword,
            'status_online' => $status_online,
            'category' => $category,
            'category_id' => $category_id
        ]);
    }

    public function kategory(Request $request): Response
    {
        $keyword = "";
        $status_online = "1";
        $category_id = "";
        if ($request->search) {
            $keyword = $request->search;
        }
        if ($request->status_online != "") {
            $status_online = $request->status_online;
        }
        if ($request->category_id != "") {
            $category_id = $request->category_id;
        }

        $category = Category::select(
            'id',
            // 'code',
            'name',
            // 'status_online',
            // 'status_home',
            'warna_bg'
        )->where('status_online', 1)->get();

        return Inertia::render('Market/Category', [
            'search' => $keyword,
            'status_online' => $status_online,
            'category' => $category,
            'category_id' => $category_id
        ]);
    }

    public function product(Request $request)
    {
        $keyword = "";
        $status_online = "1";
        $category_id = "";
        if ($request->search) {
            $keyword = $request->search;
        }
        if ($request->category_id != "") {
            $category_id = $request->category_id;
        }
        // dd($request->all());
        $product = Product::select(
            'sma_products.id',
            'sma_products.image1',
            // 'sma_products.image2',
            // 'sma_products.image3',
            // 'sma_products.image4',
            // 'sma_products.code',
            'sma_products.name',
            'sma_brands.name as brand_name',
            'sma_categories.name as category_name',
            'sma_products.status_online',
            'sma_products.price',
        )
            ->leftJoin('sma_brands', 'sma_brands.id', 'sma_products.brand')
            ->leftJoin('sma_categories', 'sma_categories.id', 'sma_products.category_id')
            ->where(function ($query) use ($keyword) {
                return $query->where('sma_products.name', 'like', '%' . $keyword . '%')
                    ->orWhere('sma_products.code', 'like', '%' . $keyword . '%');
            })
            ->when($category_id != "", function ($query) use ($category_id) {
                $query->where('sma_products.category_id', $category_id);
            })
            ->where('sma_products.status_online', 1)
            ->orderBy('sma_products.status_atas', 'desc')
            ->paginate(8);


        $category = Category::select(
            'id',
            // 'code',
            'name',
            // 'status_online',
            // 'status_home',
            'warna_bg'
        )->where('status_online', 1)->get();

        $category_name = "";
        if ($category_id != "") {
            $category_name = Category::find($category_id);
            if ($category_name) {
                $category_name = $category_name->name;
            }
        }
        // dd($keyword);
        return Inertia::render('Market/Product', [
            'product' => $product,
            'search' => $keyword,
            'status_online' => $status_online,
            'category' => $category,
            'category_name' => $category_name,
            'category_id' => $category_id
        ]);
    }

    public function detailProduct($id)
    {
        $product = Product::select(
            'sma_products.id',
            'sma_products.image1',
            'sma_products.image2',
            'sma_products.image3',
            'sma_products.image4',
            'sma_products.code',
            'sma_products.name',
            'sma_brands.name as brand_name',
            'sma_categories.name as category_name',
            'sma_products.status_online',
            'sma_products.price',
            'sma_products.product_details'
        )
            ->leftJoin('sma_brands', 'sma_brands.id', 'sma_products.brand')
            ->leftJoin('sma_categories', 'sma_categories.id', 'sma_products.category_id')
            ->where('sma_products.id', $id)
            ->first();

        if ($product) {
            $product->price = "Rp. " . number_format($product->price, 0, '.', '.');
        }

        // dd($product);

        return Inertia::render('Market/Detail', [
            'product' => $product,
            'qty' => 0
        ]);
    }

    public function promo()
    {
        $promo = Promo::select(
            'name',
            'start_date',
            'end_date',
            'note'
        )->get();

        foreach ($promo as $row) {
            $row->start_date = date('d F Y', strtotime($row->start_date));
            $row->end_date = date('d F Y', strtotime($row->end_date));
        }

        return Inertia::render('Market/Promo', [
            'promo' => $promo
        ]);
    }
}
