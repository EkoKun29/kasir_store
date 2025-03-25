<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\HargaJualController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DetailPembelianController;


//Route::get('/', function () {
//    return view('welcome');
//});

//login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }
    });

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('role:admin');
    Route::get('/kasir/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard')->middleware('role:kasir');
});

//CRUD barcode
Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');
Route::get('/barcode/create', [BarcodeController::class, 'create'])->name('barcode.create');
Route::post('/barcode/store', [BarcodeController::class, 'store'])->name('barcode.store');
Route::get('/barcode/{id}/edit', [BarcodeController::class, 'edit'])->name('barcode.edit');
Route::put('/barcode/{id}/update', [BarcodeController::class, 'update'])->name('barcode.update');
Route::delete('/barcode/{id}/delete', [BarcodeController::class, 'destroy'])->name('barcode.destroy');
//Route::get('/barcode/{id}', [BarcodeController::class, 'showBarcode'])->name('barcode.show');
Route::get('/barcode/show/{id}', [BarcodeController::class, 'showBarcode'])->name('barcode.show');

//Pembelian
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');

    Route::get('pembelian/{pembelian_id}/detail/create', [PembelianController::class, 'createDetail'])->name('pembelian.detail.create');
    Route::post('pembelian/detail/store', [PembelianController::class, 'storeDetail'])->name('pembelian.detail.store');
    //Route::put('pembelian/{id}/detail/update', [DetailPembelianController::class, 'update'])->name('pembelian.detail.update');
    Route::delete('/pembelian/detail/{id}', [PembelianController::class, 'destroyDetail'])->name('pembelian.detail.destroy');
    Route::get('/pembelian/{id}/detail', [PembelianController::class, 'show'])->name('pembelian.detail');
    Route::get('pembelian/{id}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit');
    Route::put('pembelian/{id}', [PembelianController::class, 'update'])->name('pembelian.update');
    Route::get('pembelian-edit-detail/{id}', [PembelianController::class, 'editDetail'])->name('pembelian.edit-detail');
    Route::put('pembelian-update-detail/{id}', [PembelianController::class, 'updateDetail'])->name('pembelian.updateDetail');
    Route::delete('pembelian/{id}', [PembelianController::class, 'destroy'])->name('pembelian.destroy');
    Route::delete('pembelian-detail/{id}', [PembelianController::class, 'destroyDetail'])->name('pembelian.destroy-detail');
});

//harga jual
Route::get('/hargajual', [HargaJualController::class, 'index'])->name('hargajual.index');
Route::get('/hargajual/create', [HargaJualController::class, 'create'])->name('hargajual.create');
Route::post('/hargajual/store', [HargaJualController::class, 'store'])->name('hargajual.store');
Route::get('/hargajual/{id}/edit', [HargaJualController::class, 'edit'])->name('hargajual.edit');
Route::put('/hargajual/{id}', [HargaJualController::class, 'update'])->name('hargajual.update');
Route::delete('/hargajual/{id}', [HargaJualController::class, 'destroy'])->name('hargajual.destroy');






