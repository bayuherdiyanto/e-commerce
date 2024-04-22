<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Gudang;
use App\Models\Setting;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    public function index()
    {
        $gudang = Gudang::get();
        $setting = Setting::where('id', 1)->first();
        if (!$setting) {
            $setting = new Setting;
        }
        return Inertia::render('Setting/Index', [
            'gudang' => $gudang,
            'setting' => $setting
        ]);
    }

    public function store(Request $request)
    {
        $setting = Setting::where('id', 1)->first();
        if (!$setting) {
            $setting = new Setting;
        }
        $setting->gudang = $request->gudang;
        $setting->no_wa = $request->no_wa;
        $setting->save();

        return redirect('/setting');
    }
}
