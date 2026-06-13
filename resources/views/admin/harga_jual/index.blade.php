@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Daftar Harga Jual</h5>
        {{-- <a href="{{ route('hargajual.create') }}" class="btn btn-primary mb-3">Input Harga Jual</a> --}}

        <table class="table datatable" style="margin-top: 15px">
        <thead class="thead-dark">
            <tr style="text-align: center">
                    <th>No</th>
                    <th>Produk</th>
                    <th>Tanggal Beli</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual (Satuan)</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barcodes as $barcode)
                <tr style="text-align: center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barcode->produk }}</td>
                    <td>{{ $barcode->tanggal_beli ? \Carbon\Carbon::parse($barcode->tanggal_beli)->translatedFormat('d-m-Y') : 'Null' }}</td>
                    <td>Rp. {{ number_format($barcode->harga_beli, 0, ',', '.') }}</td>
                    <td> {{ $barcode->harga_jual ? 'Rp. ' . number_format($barcode->harga_jual, 0, ',', '.') : '' }}</td>
                    <td>Stok</td>
                    <td>
                        <a href="{{ route('hargajual.edit', $barcode->id) }}" class="btn btn-warning btn-sm">Revisi Harga Jual</a>
                        {{-- <form action="{{ route('hargajual.destroy', $barcode->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form> --}}
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $barcode->id }})">
                            Hapus
                        </button>
                        
                        <form id="delete-form-{{ $barcode->id }}" action="{{ route('hargajual.destroy', $barcode->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('js')
<script>
$(document).ready(function () {

    $('.datatable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        }
    });

});
</script>
@endpush
