@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Audit Bulanan</h5>
    <table class="table datatable" style="margin-top: 15px">
        <thead class="thead-dark">
            <tr style="text-align: center">
                <th>No</th>
                <th>Supplier</th>
                <th>Tanggal Beli</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php $no =1; ?>
        <tbody>
            @foreach($pembelian as $p)
            <tr style="text-align: center">
                <td>{{ $no++ }}</td>
                <td>{{ $p->supplier }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_beli)->translatedFormat('d-m-Y') }}</td>
                <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('pembelian.detail', $p->id) }}" class="btn btn-primary btn-sm">Detail </a>
                    <a href="{{ route('pembelian.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $p->id }})">
                        Hapus
                    </button>
                    
                    <form id="delete-form-{{ $p->id }}" action="{{ route('pembelian.destroy', $p->id) }}" method="POST" style="display: none;">
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