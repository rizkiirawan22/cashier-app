<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('dashboard', [
        'item_count' => Item::count(),
        'purchase_count' => Purchase::count(),
    ]);
});

Route::resource('barang', ItemController::class);
Route::post('pembelian/cart/remove', [PurchaseController::class, 'removeCart'])->name('pembelian.removeCart');
Route::post('pembelian/cart/tambah', [PurchaseController::class, 'addCart'])->name('pembelian.addCart');
Route::post('pembelian/detail', [PurchaseController::class, 'detail'])->name('pembelian.detail');
Route::resource('pembelian', PurchaseController::class);
