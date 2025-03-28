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
                    <th>No</th>
                    <th>Produk</th>
                    <th>Tanggal Beli</th>
                    <th>Harga Beli</th>
                    <th>Qty</th>
                    <th>Hpp</th>
                    {{-- <th>Harga Jual</th> --}}
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php $no =1; ?>
            <tbody>
                @foreach ($barcodes as $barcode)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $barcode->produk }}</td>
                    <td>{{ $barcode->tanggal_beli ? \Carbon\Carbon::parse($barcode->tanggal_beli)->translatedFormat('d-m-Y') : 'Null' }}</td>
                    <td>Rp. {{ number_format($barcode->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ $barcode->qty }}</td>
                    <td>{{ number_format($barcode->hpp, 2, ',', '.') }}</td>
                    {{-- <td>Rp. {{ number_format($barcode->harga_jual, 0, ',', '.') }}</td> --}}
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
