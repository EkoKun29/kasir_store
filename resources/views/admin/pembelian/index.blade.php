@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Detail Pembelian</h5>
        <a href="{{ route('pembelian.create') }}">
            <button class="btn btn-primary">Input Pembelian</button>
          </a>
    <table class="table datatable" style="margin-top: 15px">
        <thead class="thead-dark">
            <tr style="text-align: center">
                <th>ID</th>
                <th>Supplier</th>
                <th>Tanggal Beli</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Barcode ID</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian as $detail)
            <tr>
                <td>{{ $detail->id }}</td>
                <td>{{ $detail->pembelian->supplier ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->pembelian->tanggal_beli)->translatedFormat('d F Y') }}</td>
                <td>{{ $detail->produk }}</td>
                <td>Rp. {{ number_format($detail->harga, 2) }}</td>
                <td>{{ $detail->qty }}</td>
                <td>Rp. {{ number_format($detail->subtotal, 2) }}</td>
                <td>
                    @if($detail->barcode_id)
                        {{ $detail->barcode_id }}
                    @else
                        <span class="text-danger">Belum Tersedia</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('pembelian.edit', $detail->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $detail->id }})">
                        Hapus
                    </button>
                    
                    <form id="delete-form-{{ $detail->id }}" action="{{ route('pembelian.destroy', $detail->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
