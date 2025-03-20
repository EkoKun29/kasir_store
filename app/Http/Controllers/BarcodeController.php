<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;

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
            'tanggal_beli' => 'required|date',
            'harga_beli' => 'required|numeric|min:1',
            'qty' => 'required|integer|min:1',
        ]);

        // Hitung HPP secara otomatis
        $hpp = $request->harga_beli / $request->qty;

        Barcode::create([
            'produk' => $request->produk,
            'tanggal_beli' => $request->tanggal_beli,
            'harga_beli' => $request->harga_beli,
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
            'tanggal_beli' => 'required|date',
            'harga_beli' => 'required|numeric|min:1',
            'qty' => 'required|integer|min:1',
        ]);

        // Hitung HPP secara otomatis
        $hpp = $request->harga_beli / $request->qty;

        $barcode = Barcode::findOrFail($id);
        $barcode->update([
            'produk' => $request->produk,
            'tanggal_beli' => $request->tanggal_beli,
            'harga_beli' => $request->harga_beli,
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
}
