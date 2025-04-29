<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;

class ConvertApiController extends Controller
{
     /**
     * Menggabungkan data penjualan dengan detail penjualannya
     */
    public function combinedPenjualan()
    {
        try {
            // Ambil semua data penjualan dengan relasi detail penjualan
            $penjualans = Penjualan::with('detailPenjualans')->get();
            
            // Format data sesuai kebutuhan dan model yang tersedia
            $formattedData = $penjualans->map(function ($penjualan) {
                return [
                    'id' => $penjualan->id,
                    'nomor_surat' => $penjualan->nomor_surat,
                    'id_user' => $penjualan->id_user,
                    'id_kios' => $penjualan->id_kios,
                    'potongan' => $penjualan->potongan,
                    'status_penjualan' => $penjualan->status_penjualan,
                    'created_at' => $penjualan->created_at,
                    'updated_at' => $penjualan->updated_at,
                    'detail_penjualan' => $penjualan->detailPenjualans->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'barcode_id' => $detail->barcode_id,
                            'nama_produk' => $detail->barcode ? $detail->barcode->produk : 'Produk tidak ditemukan',
                            'harga_jual' => $detail->barcode ? $detail->barcode->harga_jual : 'Tidak ada harga jual',
                            'pcs' => $detail->pcs,
                            'subtotal' => $detail->subtotal,
                            'diskon' => $detail->diskon,
                            'penjualan_id' => $detail->penjualan_id,
                            'created_at' => $detail->created_at,
                            'updated_at' => $detail->updated_at
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data Penjualan dengan Detail',
                'data' => $formattedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }


}