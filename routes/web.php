<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

//login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
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