<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\Api\ConvertApiController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\Api\PembelianGabunganController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//barcodes API
Route::get('/barcodes', [BarcodeController::class, 'apiIndex']);
//penjualan API
Route::get('/penjualans', [PenjualanController::class, 'apiIndex']);
//pembelian API
Route::get('/pembelians', [PembelianController::class, 'apiIndex']);
//detail pembelian API
Route::get('/detailpembelians', [DetailPembelianController::class, 'apiIndex']);
//detail penjualan API
Route::get('/detailpenjualans', [DetailPenjualanController::class, 'apiIndex']);

// Route untuk API penjualan gabungan
Route::get('/combined-penjualan', [ConvertApiController::class, 'combinedPenjualan']);

//gabungan pembelian APi
Route::get('/combined-pembelian', [PembelianGabunganController::class, 'combinedPembelian']);
