<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;

class BarcodeController extends Controller
{
    // Menampilkan seluruh data barcode
    public function index()
    {
        $barcodes = Barcode::all();
        return view('admin.barcode.show', compact('barcodes'));
    }

    // Menampilkan form input barcode
    public function create()
    {
        return view('admin.barcode.create');
    }

    // Proses penyimpanan data barcode
    public function store(Request $request)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'tanggal_beli' => 'nullable|date',
            'harga_beli' => 'nullable|numeric|min:1',
            'harga_jual' => 'nullable|numeric|min:1',
            'qty' => 'required|integer|min:1',
        ]);

        // Hitung HPP secara otomatis
        $hpp = $request->harga_beli / $request->qty;

        Barcode::create([
            'produk' => $request->produk,
            'tanggal_beli' => $request->tanggal_beli,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'qty' => $request->qty,
            'hpp' => $hpp,
        ]);

        return redirect()->route('barcode.create')->with('success', 'Data barcode berhasil disimpan!');
    }

    public function edit($id)
    {
        $barcode = Barcode::findOrFail($id);
        return view('admin.barcode.edit', compact('barcode'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk' => 'required|string',
            'tanggal_beli' => 'nullable|date',
            'harga_beli' => 'nullable|numeric|min:1',
            'harga_jual' => 'nullable|numeric|min:1',
            'qty' => 'nullable|integer|min:1',
        ]);

        // Hitung HPP secara otomatis
        $hpp = $request->harga_beli / $request->qty;

        $barcode = Barcode::findOrFail($id);
        $barcode->update([
            'produk' => $request->produk,
            'tanggal_beli' => $request->tanggal_beli,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'qty' => $request->qty,
            'hpp' => $hpp,
        ]);

        return redirect()->route('barcode.index')->with('success', 'Data barcode berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barcode = Barcode::findOrFail($id);
        $barcode->delete();

        return redirect()->route('barcode.index')->with('success', 'Data barcode berhasil dihapus!');
    }
    
    public function showBarcode($id)
    {
        $barcode = Barcode::findOrFail($id);

        // dd($detail);

        if (!$barcode) {
            return back()->with('error', 'Data barcode tidak ditemukan');
        }

        return view('admin.barcode.print_barcode', compact('barcode'));
    }

    public function showDetail($id)
    {
        $detail = DetailPembelian::findOrFail($id);

        return view('admin.barcode.detail_produk', compact('detail'));
    }

    public function penjualan(Request $request){
        $barcode = Barcode::where('id', )->first();
        $jual = New Pembelian;
        $jual->id_barcode = $barcode->id;
        $jual->produk = $barcode->produk;
        $jual->harga_beli = $barcode->harga_beli;
        $jual->hpp = $barcode->hpp;
        $jual->harga_jual = $barcode->harga_jual;
        $jual->qty = 1;
        $jual->save();

    }

    public function getDetails($barcode_id)
{
    $barcode = Barcode::where('id', $barcode_id)->first();
    
    if (!$barcode) {
        return response()->json(['error' => 'Barcode tidak ditemukan']);
    }
    
    return response()->json([
        'produk' => $barcode->produk,
        'harga_jual' => $barcode->harga_jual
    ]);
}
}
