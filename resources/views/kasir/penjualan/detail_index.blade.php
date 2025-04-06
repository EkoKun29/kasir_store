@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Detail Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nomor Surat:</strong> {{ $penjualan->nomor_surat ?? '-' }}</p>
                    <p><strong>ID Kios:</strong> {{ $penjualan->id_kios ?? '-' }}</p>
                    <p><strong>Status Penjualan:</strong> {{ $penjualan->status_penjualan ?? '-' }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>Tanggal:</strong> 
                        {{ $penjualan->created_at 
                            ? $penjualan->created_at->format('d M Y H:i') 
                            : '-' }}
                    </p>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        {{-- <th>Barcode</th> --}}
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Pcs</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalSubtotal = 0;
                    @endphp
                    @forelse($detailPenjualans as $item)
                        @php
                            $subtotal = $item->barcode->harga_jual * $item->pcs;
                            $totalSubtotal += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->barcode->produk }}</td>
                            <td>Rp. {{ number_format($item->barcode->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $item->pcs }}</td>
                            <td>Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Potongan:</strong></td>
                        <td><strong>Rp. {{ number_format($penjualan->potongan ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total Bayar:</strong></td>
                        <td>
                            <strong>
                                Rp. {{ number_format($totalSubtotal - ($penjualan->potongan ?? 0), 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                </tfoot>                
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
