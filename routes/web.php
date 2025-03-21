<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DetailPembelianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

//Pembelian
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');

    Route::get('pembelian/{id}/detail/create', [PembelianController::class, 'createDetail'])->name('pembelian.detail.create');
    Route::post('pembelian/{id}/detail/store', [PembelianController::class, 'storeDetail'])->name('pembelian.detail.store');
});

Route::get('/pembelian/{pembelian_id}/detail/create', [DetailPembelianController::class, 'create'])->name('pembelian.detail.create');
Route::post('/pembelian/{pembelian_id}/detail/store', [DetailPembelianController::class, 'store'])->name('pembelian.detail.store');