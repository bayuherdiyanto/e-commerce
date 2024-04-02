<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
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
        $product = Category::select(
            'id',
            'code',
            'name',
            'status_online',
            'status_home',
            'warna_bg'
        )
        ->where(function ($query) use ($keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('code', 'like', '%' . $keyword . '%');
        })
        ->when( $status_online != "", function ($query) use ($status_online) {
            $query->where('status_online', $status_online );
        })
        
            ->paginate(10);

        $status_online_value = [];
        $status_atas_value = [];
        foreach ($product as $row) {
            if($row->status_online=="1"){
                $status_online_value[] = $row->id;
            }
            if($row->status_home=="1"){
                $status_atas_value[] = $row->id;
            }
        }

        return Inertia::render('Category/List', [
            'product' => $product,
            'search' => $keyword,
            'status_online' => ($status_online=="") ? "All" : $status_online,
            'status_online_value' => $status_online_value,
            'status_atas_value' => $status_atas_value,
            'page'=>$request->page
        ]);
    }

    public function changeStatusOnline(Request $request){
        $category = Category::find($request->id);
        $category->status_online = ($category->status_online=="1") ? 0 : 1;
        $category->status_home = ($category->status_online=="0") ? 0 : $category->status_home;
        $category->save();

        if($category->status_online=="0"){
            Product::where(['category_id'=>$category->id])
                ->update(['status_online'=>'0', 'status_atas'=>'0']);
        }


        return Redirect::route('listCategory',[
            'status_online'=>$request->status_online,
            'search'=>$request->search,
            'page'=>$request->page
        ]);
    }
    
    public function changeStatusAtas(Request $request){
        $product = Category::find($request->id);
        $product->status_home = ($product->status_atas=="1") ? 0 : 1;
        $product->save();

        return Redirect::route('listCategory',[
            'status_online'=>$request->status_online,
            'search'=>$request->search,
            'page'=>$request->page
        ]);
    }

    public function categoryWarna(Request $request){
        $product = Category::find($request->id);
        $product->warna_bg = $request->warna;
        $product->save();
        return Redirect::route('listCategory',[
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
