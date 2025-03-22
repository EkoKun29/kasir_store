<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barcode;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    // Menampilkan daftar pembelian
    public function index()
    {
        $pembelians = Pembelian::where('id_user', auth()->id())->get();
        return view('admin.pembelian.index', compact('pembelians'));
    }

    // Menampilkan form pembelian
    public function create()
    {
        return view('admin.pembelian.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal_beli' => 'required|date',
            'supplier' => 'required|string|max:255',
        ]);

        // Simpan data pembelian terlebih dahulu
        $pembelian = Pembelian::create([
            'tanggal_beli' => $request->tanggal_beli,
            'supplier' => $request->supplier,
            'total_harga' => 0, // Awalnya total harga di-set 0
            'id_user' => auth()->id(),
        ]);

        // Hitung total harga baru
        $totalHarga = DetailPembelian::where('pembelian_id', $pembelian->id)->sum('subtotal');

        // Update total harga pada tabel pembelian
        $pembelian->update([
            'total_harga' => $totalHarga
        ]);

        return redirect()->route('pembelian.detail.create', $pembelian->id)
                        ->with('success', 'Data pembelian berhasil disimpan.');
    }

    // Menampilkan form detail pembelian
    public function createDetail($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        return view('admin.pembelian.create_detail', compact('pembelian'));
    }

    // Proses penyimpanan data detail pembelian
    public function storeDetail(Request $request, $id)
    {
        // dd($request->harga);
        $pembelian = Pembelian::find($id);
        $request->validate([
            'produk.*' => 'required|string|max:255',
            'harga.*' => 'required|numeric|min:0',
            'qty.*' => 'required|integer|min:1',
        ]);

        $totalHarga = 0;

        // Iterasi setiap produk yang diinputkan
        

            // Simpan data ke tabel Barcode
            $barcode = Barcode::create([
                'produk' => $request->produk,
                'tanggal_beli' => $pembelian->tanggal_beli,
                'harga_beli' => $request->harga,
                'qty' => $request->qty,
                'hpp' => $request->harga / $request->qty,
            ]);

            // Simpan data ke tabel detail_pembelians
            DetailPembelian::create([
                'pembelian_id' => $id,
                'produk' => $barcode->produk,
                'harga' => $barcode->harga_beli,
                'qty' => $barcode->qty,
                'subtotal' => $barcode->harga_beli * $barcode->qty,
                'barcode_id' => $barcode->id,
            ]);

            // $totalHarga += $subtotal;
            // // Update total harga di tabel pembelian
            // $pembelian->update(['total_harga' => $totalHarga]);
        
        return redirect()->back()
            ->with('success', 'Detail pembelian berhasil ditambahkan dengan beberapa produk!');
    }

}
