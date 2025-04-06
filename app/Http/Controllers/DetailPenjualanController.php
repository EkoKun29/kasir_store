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
            'potongan' => 'nullable|numeric|min:0'
        ]);

        $potongan = $request->potongan ?? 0;
        $totalSebelumPotongan = 0;

        // Hitung total sebelum potongan
        foreach ($request->products as $product) {
            $subtotal = $product['harga'] * $product['pcs'];
            $totalSebelumPotongan += $subtotal;
        }

        // Hitung subtotal akhir
        $subtotalSetelahPotongan = $totalSebelumPotongan - $potongan;
        if ($subtotalSetelahPotongan < 0) {
            $subtotalSetelahPotongan = 0;
        }

        // Simpan detail penjualan
        foreach ($request->products as $product) {
            $subtotal = $product['harga'] * $product['pcs'];
            $subtotalPersentasePotongan = ($subtotal / $totalSebelumPotongan) * $potongan;
            $subtotalAkhir = $subtotal - $subtotalPersentasePotongan;

            DetailPenjualan::create([
                'penjualan_id' => $request->penjualan_id,
                'barcode_id' => $product['barcode'],
                'produk' => $product['produk'],
                'harga' => $product['harga'],
                'pcs' => $product['pcs'],
                'subtotal' => round($subtotalAkhir, 0), // simpan subtotal setelah dikurangi bagian potongan
            ]);
        }

        // Simpan potongan ke tabel penjualan
        $penjualan = Penjualan::find($request->penjualan_id);
        if (!$penjualan) {
            return redirect()->route('penjualan.index')
                            ->with('error', 'Data penjualan tidak ditemukan.');
        }

        $penjualan->potongan = $potongan;
        $penjualan->save();

        return view('kasir.penjualan.print_nota', compact('penjualan'));
    }


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