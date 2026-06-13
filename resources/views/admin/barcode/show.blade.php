@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Barcode</h5>
        <div class="row mb-3 mt-3">

    <div class="col-md-3">
        <a href="{{ route('barcode.create') }}" class="btn btn-primary">
            Input Barcode
        </a>
    </div>

    <div class="col-md-4">
        <form action="{{ route('barcode.index') }}" method="GET">
            <div class="input-group">

                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Cari produk..."
                       value="{{ request('search') }}">

                <button class="btn btn-primary">
                    Cari
                </button>

            </div>
        </form>
    </div>

</div>
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
