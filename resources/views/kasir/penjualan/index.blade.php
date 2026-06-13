@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Penjualan</h5>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
    <table class="table datatable" style="margin-top: 15px">
        <thead class="thead-dark">
            <tr style="text-align: center">
                <th>No</th>
                <th>Nomor Surat</th>
                <th>ID Kios</th>
                <th>Status Penjualan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php $no =1; ?>
        <tbody>
            @foreach($penjualans as $penjualan)
                <tr style="text-align: center">
                    <td>{{ $no++ }}</td>
                    <td>{{ $penjualan->nomor_surat }}</td>
                    <td>{{ $penjualan->id_kios }}</td>
                    <td>{{ $penjualan->status_penjualan }}</td>
                    <td>
                        <a href="{{ route('detail_penjualan.show', $penjualan->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $penjualan->id }})">
                            Hapus
                        </button>
                        
                        <form id="delete-form-{{ $penjualan->id }}" action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <a href="{{ route('penjualan.nota', $penjualan->id) }}" class="btn btn-success btn-sm">Cetak Ulang Nota</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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