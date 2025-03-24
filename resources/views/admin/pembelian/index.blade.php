@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Pembelian</h5>
        {{-- <a href="{{ route('pembelian.create') }}">
            <button class="btn btn-primary">Input Pembelian</button>
          </a> --}}
    <table class="table datatable" style="margin-top: 15px">
        <thead class="thead-dark">
            <tr style="text-align: center">
                <th>No</th>
                <th>Supplier</th>
                <th>Tanggal Beli</th>
                <th>Total</th>
                {{-- <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th> --}}
                {{-- <th>Barcode ID</th> --}}
                <th>Aksi</th>
            </tr>
        </thead>
        <?php $no =1; ?>
        <tbody>
            @foreach($pembelian as $detail)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $detail->supplier }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->tanggal_beli)->translatedFormat('d-m-Y') }}</td>
                <td>Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('pembelian.edit', $detail->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $detail->id }})">
                        Hapus
                    </button>
                    
                    <form id="delete-form-{{ $detail->id }}" action="{{ route('pembelian.destroy', $detail->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                     
                    <a href="{{ route('pembelian.detail', $detail->id) }}" 
                        class="btn btn-success btn-sm" 
                        style="margin-top: 5px">
                        Detail
                     </a>
                      
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
