<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConvertApiController extends Controller
{
     /**
     * Menggabungkan data penjualan dengan detail penjualannya
     */
    public function combinedPenjualan()
    {
        // Ambil semua data penjualan dengan relasi detail penjualan
        $penjualans = Penjualan::with('detailPenjualan')->get();
        
        // Format data sesuai kebutuhan
        $formattedData = $penjualans->map(function ($penjualan) {
            return [
                'id' => $penjualan->id,
                'tanggal_jual' => $penjualan->tanggal_jual,
                'pelanggan' => $penjualan->pelanggan,
                'total_harga' => $penjualan->total_harga,
                'nomor_faktur' => $penjualan->nomor_faktur,
                'id_user' => $penjualan->id_user,
                'status_penjualan' => $penjualan->status_penjualan,
                'detail_penjualan' => $penjualan->detailPenjualan->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'penjualan_id' => $detail->penjualan_id,
                        'produk' => $detail->produk,
                        'harga' => $detail->harga,
                        'qty' => $detail->qty,
                        'subtotal' => $detail->subtotal,
                        'barcode_id' => $detail->barcode_id,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Penjualan dengan Detail',
            'data' => $formattedData
        ]);
    }

    /**
     * Alternative API endpoint yang mengambil data terpisah dan menggabungkannya
     */
    public function combinedPenjualanAlt()
    {
        // Ambil semua data penjualan
        $penjualans = Penjualan::all();
        $detailPenjualans = DetailPenjualan::all();
        
        // Kelompokkan detail penjualan berdasarkan penjualan_id
        $detailsByPenjualanId = [];
        foreach ($detailPenjualans as $detail) {
            if (!isset($detailsByPenjualanId[$detail->penjualan_id])) {
                $detailsByPenjualanId[$detail->penjualan_id] = [];
            }
            $detailsByPenjualanId[$detail->penjualan_id][] = [
                'id' => $detail->id,
                'penjualan_id' => $detail->penjualan_id,
                'produk' => $detail->produk,
                'harga' => $detail->harga,
                'qty' => $detail->qty,
                'subtotal' => $detail->subtotal,
                'barcode_id' => $detail->barcode_id,
            ];
        }
        
        // Format data sesuai kebutuhan
        $formattedData = $penjualans->map(function ($penjualan) use ($detailsByPenjualanId) {
            return [
                'id' => $penjualan->id,
                'tanggal_jual' => $penjualan->tanggal_jual,
                'pelanggan' => $penjualan->pelanggan,
                'total_harga' => $penjualan->total_harga,
                'nomor_faktur' => $penjualan->nomor_faktur,
                'id_user' => $penjualan->id_user,
                'status_penjualan' => $penjualan->status_penjualan,
                'detail_penjualan' => $detailsByPenjualanId[$penjualan->id] ?? [],
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Penjualan dengan Detail (Alternative)',
            'data' => $formattedData
        ]);
    }
}
