<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barcode;

class HargaJualController extends Controller
{
 
    public function index()
    {
        $barcodes = Barcode::all();
        return view('admin.harga_jual.index', compact('barcodes'));
    }

    public function create()
    {
        $barcodes = Barcode::all();
        return view('admin.harga_jual.create', compact('barcodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode_id' => 'required|exists:barcodes,id',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barcode = Barcode::findOrFail($request->barcode_id);
        $barcode->update([
            'harga_jual' => $request->harga_jual
        ]);

        return redirect()->route('hargajual.index')->with('success', 'Harga jual berhasil disimpan.');
    }

    public function edit($id)
    {
        $barcode = Barcode::findOrFail($id);
        return view('admin.harga_jual.edit', compact('barcode'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barcode = Barcode::findOrFail($id);
        $barcode->update([
            'harga_jual' => $request->harga_jual
        ]);

        return redirect()->route('hargajual.index')->with('success', 'Harga jual berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barcode = Barcode::findOrFail($id);
        $barcode->delete();

        return redirect()->route('hargajual.index')->with('success', 'Harga jual berhasil dihapus.');
    }
}
