<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    public function list(Request $request): Response
    {
        $keyword = "";
        $status_online = "1";
        if ($request->search) {
            $keyword = $request->search;
        }
        if($request->status_online!=""){
            $status_online = $request->status_online;
        }
        if($request->status_online=="All"){
            $status_online = "";
        }
        // dd($request->all());
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
            'sma_products.status_atas',
            'sma_categories.status_online as online_category'
        )
        ->leftJoin('sma_brands','sma_brands.id','sma_products.brand')
        ->leftJoin('sma_categories','sma_categories.id','sma_products.category_id')
        ->where(function ($query) use ($keyword) {
            return $query->where('sma_products.name', 'like', '%' . $keyword . '%')
                ->orWhere('sma_products.code', 'like', '%' . $keyword . '%');
        })
        ->when( $status_online != "", function ($query) use ($status_online) {
            $query->where('sma_products.status_online', $status_online );
        })
        
            ->paginate(10);

        $status_online_value = [];
        $status_atas_value = [];
        foreach ($product as $row) {
            if($row->status_online=="1"){
                $status_online_value[] = $row->id;
            }
            if($row->status_atas=="1"){
                $status_atas_value[] = $row->id;
            }
        }

        return Inertia::render('Product/List', [
            'product' => $product,
            'search' => $keyword,
            'status_online' => ($status_online=="") ? "All" : $status_online,
            'status_online_value' => $status_online_value,
            'status_atas_value' => $status_atas_value,
            'page'=>$request->page
        ]);
    }

    public function update($id, Request $request): Response
    {
        $content = Content::find($id);
        $provinsi   = Province::select('province_id as code', 'province_name as label')->get();
        $kota       = City::query();
        $kota->select('city_id as code', 'city_name as label');
        $statusOutlet = ['CO', 'MBF', 'FRANCHISEE'];
        $areaOutlet = ['WEST', 'CENTRAL', 'EAST'];

        $outlet     = Outlet::query();
        // dd($request->formKota);
        if ($request->formKota) {
            $outlet->where('city_id', $request->formKota);
        }
        if ($request->namaApotek) {
            $outlet->where('outlet_name', 'LIKE', "%" . $request->namaApotek . "%");
        }
        if ($request->formProvinsi) {
            $outlet->where('province_id', $request->formProvinsi);
            $kota->where('province_id', $request->formProvinsi);
        }
        $outlet->where('status_operation', 'ACTIVE');
        $outlet = $outlet->get();
        $kota   = $kota->get();


        $statusArea = [];
        if ($request->area) {
            foreach ($request->area as $key => $value) {
                $statusArea[$value] = 1;
            }
        } else {
            foreach ($areaOutlet as $key => $value) {
                $statusArea[$value] = 1;
            }
        }

        $statusOutletAktif = [];
        if ($request->statusOutlet) {
            foreach ($request->statusOutlet as $key => $value) {
                $statusOutletAktif[$value] = 1;
            }
        } else {
            foreach ($statusOutlet as $key => $value) {
                $statusOutletAktif[$value] = 1;
            }
        }

        $dataOutlet['WEST'] = [];
        $dataOutlet['EAST'] = [];
        $dataOutlet['CENTRAL'] = [];
        foreach ($outlet as $row) {
            $dataOutlet[$row->area][$row->outlet_type][] = $row;
        }
        $contentDetail = ContentDetail::where('content_id', $id)->get();
        $listOutlet = [];
        foreach ($contentDetail as $row) {
            $listOutlet[] = (int)$row->outlet_code;
        }

        return Inertia::render('Content/Create', [
            'kota' => $kota,
            'provinsi' => $provinsi,
            'statusOutletAktif' => $statusOutletAktif,
            'areaOutlet' => $areaOutlet,
            'dataOutlet' => $dataOutlet,
            'statusArea' => $statusArea,
            'statusOutlet' => $statusOutlet,
            'listOutlet' => $listOutlet,
            'contentOld' => $content,
            'pilih_gerai' => 'sebagian'
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // $validated = $request->validate([
        //     'image1' => 'required|mimes:jpg,jpeg,png|max:3072',
        //     'image2' => 'required|mimes:jpg,jpeg,png|max:3072',
        //     'image3' => 'required|mimes:jpg,jpeg,png|max:3072',
        //     'image4' => 'required|mimes:jpg,jpeg,png|max:3072',
        // ]);

        $product = Product::find($request->id);
        if ($request->image1) {
            $request->image1->storeAs('uploads/product', "image1_" . $product->id . "." . $request->image1->extension());
            $product->image1 = "image1_" . $product->id . "." . $request->image1->extension();
            $product->save();
        }
        if ($request->image2) {
            $request->image2->storeAs('uploads/product', "image2_" . $product->id . "." . $request->image2->extension());
            $product->image2 = "image2_" . $product->id . "." . $request->image2->extension();
            $product->save();
        }
        if ($request->image3) {
            $request->image3->storeAs('uploads/product', "image3_" . $product->id . "." . $request->image3->extension());
            $product->image3 = "image3_" . $product->id . "." . $request->image3->extension();
            $product->save();
        }
        if ($request->image4) {
            $request->image4->storeAs('uploads/product', "image4_" . $product->id . "." . $request->image4->extension());
            $product->image4 = "image4_" . $product->id . "." . $request->image4->extension();
            $product->save();
        }
        return Redirect::route('listProduct',[
            'status_online'=>$request->status_online,
            'search'=>$request->search,
            'page'=>$request->page
        ]);
    }

    public function changeStatusOnline(Request $request){
        $product = Product::find($request->id);
        $product->status_online = ($product->status_online=="1") ? 0 : 1;
        $product->save();

        return Redirect::route('listProduct',[
            'status_online'=>$request->status_online,
            'search'=>$request->search,
            'page'=>$request->page
        ]);
    }
    
    public function changeStatusAtas(Request $request){
        $product = Product::find($request->id);
        $product->status_atas = ($product->status_atas=="1") ? 0 : 1;
        $product->save();

        return Redirect::route('listProduct',[
            'status_online'=>$request->status_online,
            'search'=>$request->search,
            'page'=>$request->page
        ]);
    }

    public function image($jenis, $id)
    {
        if ($jenis == "product1") {
            $image = Product::find($id);
            // dd($image);
            if ($image->image1) {
                $contents = Storage::get('uploads/product/' . $image->image1);
                echo $contents;
            }
        }

        if ($jenis == "product2") {
            $image = Product::find($id);
            if ($image->image2) {
                $contents = Storage::get('uploads/product/' . $image->image2);
                echo $contents;
            }
        }

        if ($jenis == "product3") {
            $image = Product::find($id);
            if ($image->image3) {
                $contents = Storage::get('uploads/product/' . $image->image3);
                echo $contents;
            }
        }

        if ($jenis == "product4") {
            $image = Product::find($id);
            if ($image->image3) {
                $contents = Storage::get('uploads/product/' . $image->image4);
                echo $contents;
            }
        }
    }
}
