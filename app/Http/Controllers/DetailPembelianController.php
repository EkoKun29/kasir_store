<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
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
    
    public function edit($id)
    {
        $detail = DetailPembelian::findOrFail($id);
        return view('admin.pembelian.edit_detail', compact('detail'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'qty' => 'required|integer',
            'subtotal' => 'required|numeric',
        ]);

        $detail = DetailPembelian::findOrFail($id);
        $detail->update($request->all());

        return redirect()->route('pembelian.index', $detail->pembelian_id)
                         ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $detail = DetailPembelian::findOrFail($id);
        $detail->delete();

        return redirect()->route('pembelian.index', $detail->pembelian_id)
                         ->with('success', 'Produk berhasil dihapus.');
    }

}
