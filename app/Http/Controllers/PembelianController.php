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
        $pembelian = Pembelian::all();
        return view('admin.pembelian.index', compact('pembelian'));
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
            'total_harga' => 0,
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
        $detailPembelians = DetailPembelian::where('pembelian_id', $id)->get();
        return view('admin.pembelian.create_detail', compact('pembelian', 'detailPembelians'));
    }

    // Proses penyimpanan data detail pembelian
    public function storeDetail(Request $request)
    {

        $pembelian = Pembelian::find($request->pembelian_id);

            if (!$pembelian) {
                return redirect()->back()->with('error', 'Data pembelian tidak ditemukan!');
            }

            // Simpan detail pembelian
            $detailPembelian = new DetailPembelian;
            $detailPembelian->pembelian_id = $request->pembelian_id;
            $detailPembelian->produk = $request->produk;
            // $detailPembelian->tanggal_beli = $pembelian->tanggal_beli; 
            $detailPembelian->harga = $request->harga;
            $detailPembelian->qty = $request->qty;
            $detailPembelian->subtotal = $request->harga * $request->qty;
            $detailPembelian->save();

            $pembelian->total_harga +=$detailPembelian->subtotal;
            $pembelian->save();

            $dataBarcode = new Barcode;
            $dataBarcode->produk = $detailPembelian->produk;
            $dataBarcode->tanggal_beli = $pembelian->tanggal_beli;
            $dataBarcode->harga_beli = $detailPembelian->harga ;
            $dataBarcode->qty = $detailPembelian->qty;
            $dataBarcode->hpp = $dataBarcode->harga_beli / $dataBarcode->qty;
            $dataBarcode->save();

            $detailPembelian->barcode_id = $dataBarcode->id;
            $detailPembelian->save(); 


        return redirect()->route('pembelian.detail.create', $detailPembelian->pembelian->id)
            ->with('success', 'Detail pembelian berhasil ditambahkan dengan beberapa produk!');
    }

}
