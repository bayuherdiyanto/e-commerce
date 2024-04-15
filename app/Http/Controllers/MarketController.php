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
use Illuminate\Support\Facades\Session;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;

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

    public function addKeranjang(Request $request)
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::find($keranjang_id);
        if (!$keranjang) {
            $kode = $this->unique_code(10);
            $data = [
                'kode' => $kode,
                'tanggal' => date("Y-m-d H:i:s"),
                'nama' => "",
                'kode_pelanggan' => "",
                'no_telepon' => "",
                'alamat' => "",
                'status' => 0
            ];
            $keranjang = Keranjang::updateOrCreate(['kode' => $kode], $data);
            // session()->put('hello', 'hello');
            session()->put('keranjang', $keranjang->id);
        }
        // dd($request->all());
        if ($request->qty && $request->qty > 0) {
            $product = Product::find($request->product_id);
            if ($product) {
                $data = [
                    'id_keranjang' => $keranjang->id,
                    'item_id' => $product->id,
                    'item_name' => $product->name,
                    'qty' => $request->qty,
                    'harga' => $product->price,
                    'total' => $request->qty * $product->price
                ];
                KeranjangDetail::updateOrCreate(['id_keranjang' => $keranjang->id, 'item_id' => $product->id], $data);
            }
        }

        $totalBelanja = KeranjangDetail::selectRaw('sum(total) as total')->where('id_keranjang', $keranjang->id)->first();
        if ($totalBelanja) {
            $keranjang->total = $totalBelanja->total;
            $keranjang->save();
        }

        return Redirect::route('detailProduct', [
            'id' => $request->product_id
        ]);
    }

    public function keranjang(Request $request): Response
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::find($keranjang_id);
        $keranjangDetail = KeranjangDetail::where('id_keranjang', $keranjang_id)->with('product')->get();

        $textWa = $this->generateTextWa($keranjang, $keranjangDetail);

        if ($request->kodePelanggan) {
            $kodePelanggan = $request->kodePelanggan;
        }

        if ($request->noHp) {
            $noHp = $request->noHp;
            dd($noHp);
        }

        return Inertia::render('Market/Keranjang', [
            'keranjang' => $keranjang,
            'keranjangDetail' => $keranjangDetail,
            'textWa' => $textWa
        ]);
    }

    private function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    private function generateTextWa($keranjang, $keranjangDetail)
    {
        $countLengthName = 0;
        $countLengthPrice = 0;
        $countLengthTotal = 0;
        foreach ($keranjangDetail as $row) {
            $countLengthNameValue = strlen($row->item_name);
            if ($countLengthNameValue > $countLengthName) {
                $countLengthName = $countLengthNameValue;
            }

            $countLengthPriceValue = strlen($row->harga);
            if ($countLengthPriceValue > $countLengthPrice) {
                $countLengthPrice = $countLengthPriceValue;
            }

            $countLengthTotalValue = strlen($row->total);
            if ($countLengthTotalValue > $countLengthTotal) {
                $countLengthTotal = $countLengthTotalValue;
            }
        }
        $totalSpace = $countLengthName + $countLengthPrice + $countLengthTotal + 11;
        if ($totalSpace % 2 == 1) {
            $totalSpace++;
            $countLengthTotal++;
        }
        // dd($totalSpace);
        $stipeText = "";
        for ($i = 0; $i < $totalSpace; $i++) {
            $stipeText .= "-";
        }
        // dd($totalSpace);
        $textWa  = "https://web.whatsapp.com/send/?phone=6281313026017&text=%60%60%60";

        $nospaceJudul = $totalSpace - strlen("TOKO MAJU");
        $nospaceJudul = ($nospaceJudul / 2) - 2;
        $nospaceJudul = number_format($nospaceJudul, 0, '', '');
        $spaceJudul = "+";
        for ($i = 0; $i < $nospaceJudul; $i++) {
            $spaceJudul .= "+";
        }
        $textWa .= $spaceJudul . "TOKO MAJU" . $spaceJudul . "%0A";

        $nospaceJudul = $totalSpace - strlen("Fax%3A085777388308");
        $nospaceJudul = $nospaceJudul / 2;
        $nospaceJudul = number_format($nospaceJudul, 0, '', '');
        $spaceJudul = "+";
        for ($i = 0; $i < $nospaceJudul; $i++) {
            $spaceJudul .= "+";
        }
        $textWa .= $spaceJudul . "Fax%3A085777388308" . $spaceJudul . "%0A";
        $textWa .= $stipeText . "%0A";
        $textWa .= "No.%3A0004%2FPJ%2FUTM%2F0223++%0A";
        $textWa .= "Tgl:" . date("d F Y H:i:s") . "%0A";
        $textWa .= "Ksr%3AADMIN+++++++++++++%0A";
        $textWa .= "Pel%3AVID+00002+-+CUSTOM%0A";
        $textWa .= $stipeText . "%0A";
        // $textWa .= "45+MM++875%2C000+x1++%3D+++8%2C750%2C000%0A";
        // $textWa .= "BUSBAR+0+++++++x1++%3D+++++++++++0%0A";
        // $textWa .= "BLOK+E+0+++++++x1++%3D+++++++++++0%0A";

        $totalItem  = 0;
        $totalBrs   = 0;

        foreach ($keranjangDetail as $row) {
            $space1 = "+";
            $lengthName = $countLengthName - strlen($row->item_name);
            for ($i = 0; $i < $lengthName; $i++) {
                $space1 .= "+";
            }

            $space2 = "+";
            $lengthPrice = $countLengthPrice - strlen($row->harga);
            for ($i = 0; $i < $lengthPrice; $i++) {
                $space2 .= "+";
            }

            $space3 = "+";
            $lengthTotal = $countLengthTotal - strlen($row->total);
            for ($i = 0; $i < $lengthTotal; $i++) {
                $space3 .= "+";
            }
            $textWa .= $row->item_name . $space1 . $row->harga . $space2 . " x " . $row->qty . " = " . $space3 . $row->total . "0%0A";
            $totalItem+=$row->qty;
            $totalBrs++;
        }
        $textWa .= $stipeText . "%0A";
        $nospaceJudul = $totalSpace - strlen("BRS=".$totalBrs."++QTY=".$totalItem."+8.7500");
        $nospaceJudul = number_format($nospaceJudul, 0, '', '');
        $spaceJudul = "+";
        for ($i = 0; $i < $nospaceJudul; $i++) {
            $spaceJudul .= "+";
        }
        $textWa .= "BRS=".$totalBrs."++QTY=".$totalItem."+".$spaceJudul."8.750%0A";
        $textWa .= $stipeText . "%0A";

        $nospaceJudul = $totalSpace - strlen("Kekurangan++8.750");
        $nospaceJudul = number_format($nospaceJudul, 0, '', '');
        $spaceJudul = "";
        for ($i = 0; $i < $nospaceJudul; $i++) {
            $spaceJudul .= "+";
        }
        $textWa .= "Kekurangan++".$spaceJudul."8.750%0A";
        $textWa .= "+++++++++++++++++++++++++%0A";
        $textWa .= "Cetak:" . date("d F Y H:i:s");
        // %3A28%2F02%2F23+10%3A36%3A32
        $textWa .= "%60%60%60";

        return $textWa;
    }
}
