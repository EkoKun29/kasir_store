<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KiosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\HargaJualController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\DetailPenjualanController;


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

    //CRUD barcode
    Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');
    Route::get('/barcode/create', [BarcodeController::class, 'create'])->name('barcode.create');
    Route::post('/barcode/store', [BarcodeController::class, 'store'])->name('barcode.store');
    Route::get('/barcode/{id}/edit', [BarcodeController::class, 'edit'])->name('barcode.edit');
    Route::put('/barcode/{id}/update', [BarcodeController::class, 'update'])->name('barcode.update');
    Route::delete('/barcode/{id}/delete', [BarcodeController::class, 'destroy'])->name('barcode.destroy');
    //Route::get('/barcode/{id}', [BarcodeController::class, 'showBarcode'])->name('barcode.show');
    Route::get('/barcode/show/{id}', [BarcodeController::class, 'showBarcode'])->name('barcode.show');

    //harga jual
    Route::get('/hargajual', [HargaJualController::class, 'index'])->name('hargajual.index');
    Route::get('/hargajual/create', [HargaJualController::class, 'create'])->name('hargajual.create');
    Route::post('/hargajual/store', [HargaJualController::class, 'store'])->name('hargajual.store');
    Route::get('/hargajual/{id}/edit', [HargaJualController::class, 'edit'])->name('hargajual.edit');
    Route::put('/hargajual/{id}', [HargaJualController::class, 'update'])->name('hargajual.update');
    Route::delete('/hargajual/{id}', [HargaJualController::class, 'destroy'])->name('hargajual.destroy');

    //penjualan
    Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/edit/{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/update/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/delete/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    //detailpenjualan
    Route::get('/detail-penjualan/create/{penjualan_id}', [DetailPenjualanController::class, 'create'])->name('detail_penjualan.create');
    Route::post('/detail-penjualan/store', [DetailPenjualanController::class, 'store'])->name('detail_penjualan.store');
    Route::get('/detail-penjualan/{penjualan_id}', [DetailPenjualanController::class, 'show'])->name('detail_penjualan.show');

    //scan barcode
    // Route::get('/get-barcode-details', [DetailPenjualanController::class, 'getBarcodeDetails'])->name('get.barcode.details');
    // Route::get('/get-barcode-details/{barcode_id}', [BarcodeController::class, 'getDetails']);

    //user
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //kios
    Route::get('/kios', [KiosController::class, 'index'])->name('kios.index');
    Route::get('/kios/create', [KiosController::class, 'create'])->name('kios.create');
    Route::post('/kios', [KiosController::class, 'store'])->name('kios.store');
    Route::get('/kios/{id}/edit', [KiosController::class, 'edit'])->name('kios.edit');
    Route::put('/kios/{id}', [KiosController::class, 'update'])->name('kios.update');
    Route::delete('/kios/{id}', [KiosController::class, 'destroy'])->name('kios.destroy');
});



// //penjualan kasir
// Route::prefix('kasir/penjualan')->group(function () {
//     Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
//     Route::get('/create', [PenjualanController::class, 'create'])->name('penjualan.create');
//     Route::post('/store', [PenjualanController::class, 'store'])->name('penjualan.store');
//     Route::get('/edit/{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
//     Route::put('/update/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
//     Route::delete('/delete/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
// });

// //detailpenjualan
// Route::get('/detail-penjualan/create/{penjualan_id}', [DetailPenjualanController::class, 'create'])->name('detail_penjualan.create');
// Route::post('/detail-penjualan/store', [DetailPenjualanController::class, 'store'])->name('detail_penjualan.store');
// Route::get('/detail-penjualan/{penjualan_id}', [DetailPenjualanController::class, 'show'])->name('detail_penjualan.show');

// //scan barcode
// // Route::get('/get-barcode-details', [DetailPenjualanController::class, 'getBarcodeDetails'])->name('get.barcode.details');
Route::get('/get-barcode-details/{barcode_id}', [BarcodeController::class, 'getDetails']);

//print nota
Route::get('/penjualan/nota/{id}', [PenjualanController::class, 'nota'])->name('penjualan.nota');
Route::post('/penjualan/{id}/update-potongan', [PenjualanController::class, 'updatePotongan'])->name('penjualan.update-potongan');
Route::post('/penjualan/{id}/update-totals', [PenjualanController::class, 'updateTotals'])->name('penjualan.update-totals');

//nota pembelian
Route::get('/pembelian/{id}/nota', [PembelianController::class, 'nota'])->name('pembelian.nota');
