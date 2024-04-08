<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         // 'canRegister' => Route::has('register'),
//         // 'laravelVersion' => Application::VERSION,
//         // 'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [MarketController::class, 'index'])->name('market');
Route::get('/kategori', [MarketController::class, 'kategory'])->name('kategori');
Route::get('/produk', [MarketController::class, 'product'])->name('produk');
Route::get('/promo', [MarketController::class, 'promo'])->name('promo');
Route::get('/detail/{id}', [MarketController::class, 'detailProduct'])->name('detailProduct');
Route::get('/image/{jenis}/{id}', [ProductController::class, 'image'])->name('image');
Route::post('/addKeranjang', [MarketController::class, 'addKeranjang'])->name('addKeranjang');
Route::get('/keranjang', [MarketController::class, 'keranjang'])->name('keranjang');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/product', [ProductController::class, 'list'])->name('listProduct');
    Route::post('/saveProductImage', [ProductController::class, 'store'])->name('saveProductImage');
    Route::post('/changeStatusOnline', [ProductController::class, 'changeStatusOnline'])->name('changeStatusOnline');
    Route::post('/changeStatusAtas', [ProductController::class, 'changeStatusAtas'])->name('changeStatusAtas');

    Route::get('/category', [CategoryController::class, 'list'])->name('listCategory');
    Route::post('/categoryStatusOnline', [CategoryController::class, 'changeStatusOnline'])->name('categoryStatusOnline');
    Route::post('/categoryStatusAtas', [CategoryController::class, 'changeStatusAtas'])->name('categoryStatusAtas');
    Route::post('/categoryWarna', [CategoryController::class, 'categoryWarna'])->name('categoryWarna');
});


require __DIR__ . '/auth.php';
