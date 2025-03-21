<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;

class DetailPembelianController extends Controller
{
    public function index()
    {
        $pembelian = DetailPembelian::all(); 
        return view('admin.pembelian.index', compact('pembelian'));
    }
    // Menampilkan form tambah detail pembelian
    public function create($pembelian_id)
    {
        $pembelian = Pembelian::findOrFail($pembelian_id);
        return view('admin.pembelian.create_detail', compact('pembelian'));
    }
    

    // Menyimpan detail pembelian ke database
    public function store(Request $request, $pembelian_id)
    {
        $request->validate([
            'produk' => 'required|array',
            'produk.*' => 'required|string',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:1',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
        ]);
    
        // Ambil data tanggal_beli dari tabel pembelians
        $pembelian = Pembelian::findOrFail($pembelian_id);
    
        // Inisialisasi total harga
        $totalHarga = 0;

        $barcode = \App\Models\Barcode::create([
                    'produk' => $produk,
                    'tanggal_beli' => $pembelian->tanggal_beli, 
                    'harga_beli' => $request->harga,
                    'qty' => $request->qty,
                    'hpp' => $request->harga / $request->qty,
                ]);

        DetailPembelian::create([
                    'pembelian_id' => $pembelian_id,
                    'produk' => $produk,
                    'harga' => $request->harga[$key],
                    'qty' => $request->qty[$key],
                    'subtotal' => $subtotal,
                    'barcode_id' => $barcode->id,
                ]);


    
        // Loop untuk menyimpan data array dan menghitung total harga
        // foreach ($request->produk as $key => $produk) {
        //     $subtotal = $request->harga[$key] * $request->qty[$key];
        //     $totalHarga += $subtotal;
    
        //     // Simpan ke tabel `barcodes` terlebih dahulu
        //     $barcode = \App\Models\Barcode::create([
        //         'produk' => $produk,
        //         'tanggal_beli' => $pembelian->tanggal_beli, 
        //         'harga_beli' => $request->harga[$key],
        //         'qty' => $request->qty[$key],
        //         'hpp' => $request->harga[$key] / $request->qty[$key],
        //     ]);

        //     dd($barcode->id);
    
        //     // Simpan ke tabel `detail_pembelians` dengan barcode_id
        //     DetailPembelian::create([
        //         'pembelian_id' => $pembelian_id,
        //         'produk' => $produk,
        //         'harga' => $request->harga[$key],
        //         'qty' => $request->qty[$key],
        //         'subtotal' => $subtotal,
        //         'barcode_id' => $barcode->id,
        //     ]);
        // }
    
        // // Update total harga pada tabel pembelians
        // Pembelian::where('id', $pembelian_id)->update([
        //     'total_harga' => $totalHarga
        // ]);
    
        return redirect()->route('pembelian.create')->with('success', 'Detail pembelian dan barcode berhasil ditambahkan.');
    }    
    
    // Fungsi untuk menghasilkan barcode_id otomatis
    private function generateBarcodeId()
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    public function destroy($id)
    {
        $detail = DetailPembelian::findOrFail($id);
        $detail->delete();

        return redirect()->route('pembelian.index')->with('success', 'Data berhasil dihapus!');
    }
}
