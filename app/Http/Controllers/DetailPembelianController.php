<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
//use Milon\Barcode\DNS1D;
//use Milon\Barcode\DNS2D;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DetailPembelianController extends Controller
{
    public function index()
    {
        $pembelian = DetailPembelian::all();
        return view('admin.pembelian.index', compact('pembelian'));
    }

    public function create($pembelian_id)
    {
        $pembelian = Pembelian::findOrFail($pembelian_id);
        $detailPembelians = DetailPembelian::where('pembelian_id', $pembelian_id)->get();
        return view('admin.pembelian.create_detail', compact('pembelian'));
    }

    // public function store(Request $request, $pembelian_id)
    // {
    //     $request->validate([
    //         'produk' => 'required|string',
    //         'harga' => 'required|numeric|min:1',
    //         'qty' => 'required|integer|min:1',
    //     ]);

    //     $pembelian = Pembelian::findOrFail($pembelian_id);

    //     $subtotal = $request->harga * $request->qty;

    //     // Simpan data barcode
    //     $barcode = Barcode::create([
    //         'produk' => $request->produk,
    //         'tanggal_beli' => $pembelian->tanggal_beli,
    //         'harga_beli' => $request->harga,
    //         'qty' => $request->qty,
    //         'hpp' => $request->harga / $request->qty,
    //         'barcode' => $produkId,
    //     ]);

    //     // Simpan data detail pembelian
    //     DetailPembelian::create([
    //         'pembelian_id' => $pembelian_id,
    //         'produk' => $request->produk,
    //         'harga' => $request->harga,
    //         'qty' => $request->qty,
    //         'subtotal' => $subtotal,
    //         'barcode_id' => $barcode->id
    //     ]);

    //     return redirect()->route('pembelian.create', $pembelian_id)
    //                      ->with('success', 'Detail pembelian dan barcode berhasil ditambahkan.');
    // }

    // public function showBarcode($id)
    // {
    //     $detail = DetailPembelian::with('barcode')->findOrFail($id);

    //     if (!$detail->barcode) {
    //         return back()->with('error', 'Data barcode tidak ditemukan');
    //     }

    //     return view('admin.pembelian.print_barcode', [
    //         'detail' => $detail,
    //     ]);
    // }
        

}
