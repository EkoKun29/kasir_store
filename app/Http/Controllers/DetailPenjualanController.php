<?php
namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;

class DetailPenjualanController extends Controller 
{
    public function show($penjualan_id)
    {
     
        $penjualan = Penjualan::findOrFail($penjualan_id);
        $detailPenjualans = DetailPenjualan::where('penjualan_id', $penjualan_id)->get();
        $totalPenjualan = $detailPenjualans->sum('subtotal');
        
        return view('kasir.penjualan.detail_index', compact('penjualan', 'detailPenjualans', 'totalPenjualan'));
    }

    public function create($penjualan_id) 
    {
        $penjualan = Penjualan::findOrFail($penjualan_id);
        $detailPenjualans = DetailPenjualan::where('penjualan_id', $penjualan_id)->get();

        return view('kasir.penjualan.create_detail', compact('penjualan', 'detailPenjualans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|integer',
            'products' => 'required|array',
            'products.*.barcode' => 'required|string',
            'products.*.produk' => 'required|string',
            'products.*.harga' => 'required|numeric',
            'products.*.pcs' => 'required|integer',
            'products.*.subtotal' => 'required|numeric',
        ]);
    
        // Proses multiple produk
        foreach ($request->products as $product) {
            DetailPenjualan::create([
                'penjualan_id' => $request->penjualan_id,
                'barcode_id' => $product['barcode'],
                'produk' => $product['produk'],
                'harga' => $product['harga'],
                'pcs' => $product['pcs'],
                'subtotal' => $product['subtotal'],
            ]);
        }
        // Ambil data penjualan untuk digunakan pada redirect
        $penjualan = Penjualan::find($request->penjualan_id);
         // Validasi jika data penjualan tidak ditemukan
        if (!$penjualan) {
            return redirect()->route('penjualan.index')
                            ->with('error', 'Data penjualan tidak ditemukan.');
        }

        // Redirect ke halaman cetak nota dengan membawa data
        return redirect()->route('penjualan.nota', ['id' => $penjualan->id]);
        // return redirect()->route('penjualan.index')
        //                  ->with('success', 'Detail penjualan berhasil ditambahkan.');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'penjualan_id' => 'required|integer',
    //         'products' => 'required|array',
    //         'products.*.barcode' => 'required|string',
    //         'products.*.produk' => 'required|string',
    //         'products.*.harga' => 'required|numeric',
    //         'products.*.pcs' => 'required|integer',
    //         'products.*.subtotal' => 'required|numeric',
    //     ]);

    //     // Proses multiple produk
    //     foreach ($request->products as $product) {
    //         DetailPenjualan::create([
    //             'penjualan_id' => $request->penjualan_id,
    //             'barcode_id' => $product['barcode'],
    //             'produk' => $product['produk'],
    //             'harga' => $product['harga'],
    //             'pcs' => $product['pcs'],
    //             'subtotal' => $product['subtotal'],
    //         ]);
    //     }

    //     // Ambil data penjualan untuk digunakan pada redirect
    //     $penjualan = Penjualan::find($request->penjualan_id);

    //     // Validasi jika data penjualan tidak ditemukan
    //     if (!$penjualan) {
    //         return redirect()->route('penjualan.index')
    //                         ->with('error', 'Data penjualan tidak ditemukan.');
    //     }

    //     // Redirect ke halaman cetak nota dengan membawa data
    //     return redirect()->route('penjualan.nota', ['id' => $penjualan->id]);
    // }


    public function showDetailPenjualan($id) 
    {
       
        $penjualan = Penjualan::findOrFail($id);
        $detailPenjualans = DetailPenjualan::where('penjualan_id', $id)->get();
        $detailPenjualans = $detailPenjualans->map(function($item) {
            $barcode = Barcode::where('id', $item->barcode_id)->first();

            if ($barcode) {
                $item->produk = $barcode->produk ?? $item->produk;
                $item->harga_jual = $barcode->harga_jual ?? $item->harga;
            }

            return $item;
        });

        $totalPenjualan = $detailPenjualans->sum('subtotal');

        return view('kasir.penjualan.detail_index', compact('penjualan', 'detailPenjualans', 'totalPenjualan'));
    }
}