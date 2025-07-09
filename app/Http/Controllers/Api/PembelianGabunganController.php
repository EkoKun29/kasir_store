<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\DB;


class PembelianGabunganController extends Controller
{
    public function combinedPembelian()
    {
        try {
            $pembelians = Pembelian::with('detailPembelian.barcode')->get();

            $formattedData = $pembelians->map(function ($pembelian) {
                return [
                    'id' => $pembelian->id,
                    'tanggal_beli' => $pembelian->tanggal_beli,
                    'supplier' => $pembelian->supplier,
                    'total_harga' => $pembelian->total_harga,
                    'nomor_surat' => $pembelian->nomor_surat,
                    'id_user' => $pembelian->id_user,
                    'status_pembelian' => $pembelian->status_pembelian,
                    'created_at' => $pembelian->created_at,
                    'updated_at' => $pembelian->updated_at,
                    'detail_pembelian' => $pembelian->detailPembelian->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'barcode_id' => $detail->barcode_id,
                            'nama_produk' => optional($detail->barcode)->produk ?? $detail->produk,
                            'harga' => $detail->harga,
                            'harga_jual' => $detail->harga_jual,
                            'qty' => $detail->qty,
                            'subtotal' => $detail->subtotal,
                            'created_at' => $detail->created_at,
                            'updated_at' => $detail->updated_at,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data Pembelian dengan Detail',
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

    public function pembelian_tanggal($start, $end){
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end));

        $beli = DetailPembelian::with('pembelian')->whereHas('pembelian', function ($q) use ($startDate, $endDate) {
            $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        })
            ->get();

        return response()->json($beli);
    }
}
