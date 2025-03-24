@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Barcode</h5>
        <a href="{{ route('barcode.create') }}">
            <button class="btn btn-primary" style="margin-top: 10px">Input Barcode</button>
          </a>
        <table class="table datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produk</th>
                    <th>Tanggal Beli</th>
                    <th>Harga Beli</th>
                    <th>Qty</th>
                    <th>Hpp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barcodes as $barcode)
                <tr>
                    <td>{{ $barcode->id }}</td>
                    <td>{{ $barcode->produk }}</td>
                    <td>{{ \Carbon\Carbon::parse($barcode->tanggal_beli)->translatedFormat('d F Y') }}</td>
                    <td>Rp. {{ number_format($barcode->harga_beli, 2, ',', '.') }}</td>
                    <td>{{ $barcode->qty }}</td>
                    <td>{{ $barcode->hpp }}</td>
                    <td>
                        <a href="{{ route('barcode.edit', $barcode->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $barcode->id }})">
                            Hapus
                        </button>
                        
                        <form id="delete-form-{{ $barcode->id }}" action="{{ route('barcode.destroy', $barcode->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <a href="{{ route('barcode.show', ['id' => $barcode->id]) }}" 
                            class="btn btn-success btn-sm" 
                            style="margin-top: 5px">
                            Cetak Barcode
                        </a>                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
