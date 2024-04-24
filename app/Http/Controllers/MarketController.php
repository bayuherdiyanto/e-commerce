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
use App\Models\Customers;
use App\Models\SellingOrder;
use App\Models\SellingOrderDetail;
use App\Models\OrderRef;
use App\Models\Setting;
use App\Models\WareHouse;
use App\Models\SmaSetting;
use App\Models\ProductPrices;
use App\Models\PromoDetail;

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


        $smaSetting = SmaSetting::find(1);
        return Inertia::render('Market/Main', [
            'product' => $product,
            'search' => $keyword,
            'status_online' => $status_online,
            'category' => $category,
            'category_id' => $category_id,
            'smaSetting' => $smaSetting
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

        $smaSetting = SmaSetting::find(1);
        return Inertia::render('Market/Category', [
            'smaSetting' => $smaSetting,
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
        $smaSetting = SmaSetting::find(1);
        return Inertia::render('Market/Product', [
            'product' => $product,
            'search' => $keyword,
            'status_online' => $status_online,
            'category' => $category,
            'category_name' => $category_name,
            'category_id' => $category_id,
            'smaSetting' => $smaSetting
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

        $smaSetting = SmaSetting::find(1);
        return Inertia::render('Market/Detail', [
            'product' => $product,
            'qty' => 0,
            'smaSetting' => $smaSetting
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

        $smaSetting = SmaSetting::find(1);
        return Inertia::render('Market/Promo', [
            'promo' => $promo,
            'smaSetting' => $smaSetting
        ]);
    }

    public function addKeranjang(Request $request)
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::where("id", $keranjang_id)->where("status", 0)->first();
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

    public function deleteKeranjang(Request $request)
    {
        $keranjang_id = session()->get('keranjang');
        $product_id = $request->product_id;
        KeranjangDetail::where("id_keranjang", $keranjang_id)->where("item_id", $product_id)->delete();

        return Redirect::route('keranjang');
    }

    public function keranjang(Request $request): Response
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::where("id", $keranjang_id)->where("status", 0)->first();
        $keranjangDetail = [];
        $qtyItem = [];
        // dd($keranjang);
        if (!$keranjang) {
            $keranjang['total'] = 0;
        } else {
            $keranjangDetail = KeranjangDetail::where('id_keranjang', $keranjang_id)->with('product')->get();
            foreach ($keranjangDetail as $row) {
                $qtyItem[$row->id] = $row->qty;
            }
        }


        $setting = Setting::find(1);
        $textWa = $this->generateTextWa($keranjang, $keranjangDetail, $setting);

        $kodePelanggan = "";
        if ($request->kodePelanggan) {
            $kodePelanggan = $request->kodePelanggan;
            session()->put('kodePelanggan', $kodePelanggan);
        } else {
            $kodePelanggan = session()->get('kodePelanggan');
        }

        $noHp = "";
        if ($request->noHp) {
            $noHp = $request->noHp;
            session()->put('noHp', $noHp);
        } else {
            $noHp = session()->get('noHp');
        }

        $promo = "";
        if ($request->promo) {
            $promo = $request->promo;
            session()->put('promo', $promo);
        } else {
            $promo = session()->get('promo');
        }

        if ($promo) {
            $dataPromo = Promo::where("name", $promo)->first();
            $itemPromo = PromoDetail::join('com-detail_keranjang', 'com-promo_detail.product_id', '=', 'com-detail_keranjang.item_id')
                ->where("diskon_bertingkat_id", $dataPromo->id)
                ->where('id_keranjang', $keranjang_id)
                ->get();
            foreach ($itemPromo as $row) {
                $diskon1 = 0;
                $diskon2 = 0;
                $diskon3 = 0;
                $diskon4 = 0;

                if ($row->harga > 0) {
                    if ($dataPromo->discount > 0) {
                        $diskon1 = ($row->harga * $dataPromo->discount);
                    }

                    if ($dataPromo->discount2 > 0) {
                        $diskon2 = ($row->harga * $dataPromo->discount2);
                    }

                    if ($dataPromo->discount2 > 0) {
                        $diskon3 = ($row->harga * $dataPromo->discount3);
                    }

                    if ($dataPromo->discount2 > 0) {
                        $diskon4 = ($row->harga * $dataPromo->discount4);
                    }

                    $row->diskon = $row->harga - $diskon1 - $diskon2 - $diskon3 - $diskon4;
                    $row->save();
                }
            }
        }

        $customer = [];
        $tipeCustomer = "";
        if ($kodePelanggan && $noHp && $keranjangDetail) {
            $customer = Customers::where("kode_customer", $kodePelanggan)
                ->where("phone", $noHp)->first();
            if ($customer) {
                $tipeCustomer = $customer->name;

                // price_group_id

                foreach ($keranjangDetail as $row) {
                    $productPrice = ProductPrices::where("price_group_id", $customer->price_group_id)
                        ->where('product_id', $row->item_id)
                        ->first();

                    if ($productPrice) {
                        $row->harga = $productPrice->price;
                        $row->save();
                    } else {
                        $row->harga = 0;
                        $row->save();
                    }
                }
                $keranjangDetail = KeranjangDetail::where('id_keranjang', $keranjang_id)->with('product')->get();
            }
        }

        $smaSetting = SmaSetting::find(1);
        return Inertia::render('Market/Keranjang', [
            'keranjang' => $keranjang,
            'keranjangDetail' => $keranjangDetail,
            'textWa' => $textWa,
            'kodePelanggan' => $kodePelanggan,
            'noHp' => $noHp,
            'promo' => $promo,
            'customer' => $customer,
            'tipeCustomer' => $tipeCustomer,
            'smaSetting' => $smaSetting,
            'qtyItem' => $qtyItem
        ]);
    }

    public function kurangQty(Request $request)
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::where("id", $keranjang_id)->where("status", 0)->first();

        $detail_id = $request->detail_id;
        $detail = KeranjangDetail::where("id", $detail_id)->where("id_keranjang", $keranjang->id)->first();
        $detail->qty = $detail->qty - 1;
        $detail->total = $detail->qty * $detail->harga;
        $detail->save();

        $totalBelanja = KeranjangDetail::selectRaw('sum(total) as total')->where('id_keranjang', $keranjang->id)->first();
        if ($totalBelanja) {
            $keranjang->total = $totalBelanja->total;
            $keranjang->save();
        }

        return Redirect::route('keranjang');
    }

    public function changeQty(Request $request)
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::where("id", $keranjang_id)->where("status", 0)->first();

        $detail_id = $request->detail_id;
        $detail = KeranjangDetail::where("id", $detail_id)->where("id_keranjang", $keranjang->id)->first();
        $detail->qty = $request->qtyItem;
        $detail->total = $detail->qty * $detail->harga;
        $detail->save();

        $totalBelanja = KeranjangDetail::selectRaw('sum(total) as total')->where('id_keranjang', $keranjang->id)->first();
        if ($totalBelanja) {
            $keranjang->total = $totalBelanja->total;
            $keranjang->save();
        }

        return Redirect::route('keranjang');
    }

    public function tambahQty(Request $request)
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::where("id", $keranjang_id)->where("status", 0)->first();

        $detail_id = $request->detail_id;
        $detail = KeranjangDetail::where("id", $detail_id)->where("id_keranjang", $keranjang->id)->first();
        $detail->qty = $detail->qty + 1;
        $detail->total = $detail->qty * $detail->harga;
        $detail->save();

        $totalBelanja = KeranjangDetail::selectRaw('sum(total) as total')->where('id_keranjang', $keranjang->id)->first();
        if ($totalBelanja) {
            $keranjang->total = $totalBelanja->total;
            $keranjang->save();
        }

        return Redirect::route('keranjang');
    }

    public function checkout(Request $request)
    {
        $keranjang_id = session()->get('keranjang');
        $keranjang = Keranjang::find($keranjang_id);
        $keranjangDetail = KeranjangDetail::where("id_keranjang", $keranjang_id)->get();

        $orderRef = OrderRef::find(1);
        if ($request->kodePelanggan && $request->noHp) {
            $customer = Customers::where("kode_customer", $request->kodePelanggan)
                ->where("phone", $request->noHp)->first();
        } else {
            $customer = Customers::find(1);
        }

        $setting = Setting::find(1);
        $warehouse = WareHouse::find($setting->gudang);

        $data = [
            'date' => date("Y-m-d H:i:s"),
            'reference_no' => "SO/WEB/" . $warehouse->id . "/" . date("Y") . '/' . sprintf('%04s', $orderRef->so_web),
            'customer_id' => $customer->id,
            'customer' => $customer->company && $customer->company != '-' ? $customer->company : $customer->name,
            'warehouse_id' => $warehouse->id,
            'warehouse' => $warehouse->name != '' ? $warehouse->name : '',
            'biller_id' => $customer->id,
            'biller' => $customer->company && $customer->company != '-' ? $customer->company : $customer->name,
            'note' => '',
            'internal_note' => '',
            'total' => $keranjang->total,
            'grand_total' => $keranjang->total,
            'created_by' => 1,
            'type' => 'so',
            'marketing' => '',
            'tgl_po_kirim' => date("Y-m-d H:i:s"),
            'tax_metod_pembelian' => 0,
            'tax_metod_penjualan' => 0,
            'id_tax_pembelian' => 1,
            'id_tax_penjualan' => 1,
            'footer_pmp_info_purchase' => '',
            'term_condition' => '',
            'alamat_kirim_supplier' => '',
            'ekspedisi' => '',
            'purchase_reference_no' => ''
        ];
        $sellingOrder = SellingOrder::create($data);

        $orderRef = OrderRef::find(1);
        $orderRef->so_web = $orderRef->so_web + 1;
        $orderRef->save();

        foreach ($keranjangDetail as $row) {
            $produk = Product::find($row->item_id);
            $detail = [
                'quote_id' => $sellingOrder->id,
                'product_id' => $produk->id,
                'product_code' => $produk->code,
                'product_name' => $produk->name,
                'product_type' => $produk->tipe,
                'net_unit_price' => $produk->price,
                'unit_price' => $produk->price,
                'real_unit_price' => $produk->price,
                'quantity' => $row->qty,
                'discount2' => '',
                'discount3' => '',
                'discount4' => '',
                'subtotal' => $row->total,
                'unit_quantity' => $row->qty,
                'unit_quantity_order' => $row->qty,
            ];

            SellingOrderDetail::create($detail);
        }

        $keranjang->status = 1;
        $keranjang->save();

        return Redirect::route('keranjang');
    }

    private function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    private function generateTextWa($keranjang, $keranjangDetail, $setting)
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
        $textWa  = "https://api.whatsapp.com/send/?phone=" . $setting->no_wa . "&text=%60%60%60";

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
            $totalItem += $row->qty;
            $totalBrs++;
        }
        $textWa .= $stipeText . "%0A";
        $nospaceJudul = $totalSpace - strlen("BRS=" . $totalBrs . "++QTY=" . $totalItem . "+8.7500");
        $nospaceJudul = number_format($nospaceJudul, 0, '', '');
        $spaceJudul = "+";
        for ($i = 0; $i < $nospaceJudul; $i++) {
            $spaceJudul .= "+";
        }
        $textWa .= "BRS=" . $totalBrs . "++QTY=" . $totalItem . "+" . $spaceJudul . "8.750%0A";
        $textWa .= $stipeText . "%0A";

        $nospaceJudul = $totalSpace - strlen("Kekurangan++8.750");
        $nospaceJudul = number_format($nospaceJudul, 0, '', '');
        $spaceJudul = "";
        for ($i = 0; $i < $nospaceJudul; $i++) {
            $spaceJudul .= "+";
        }
        $textWa .= "Kekurangan++" . $spaceJudul . "8.750%0A";
        $textWa .= "+++++++++++++++++++++++++%0A";
        $textWa .= "Cetak:" . date("d F Y H:i:s");
        // %3A28%2F02%2F23+10%3A36%3A32
        $textWa .= "%60%60%60";

        return $textWa;
    }
}
