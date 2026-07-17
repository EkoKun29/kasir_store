@extends('admin.layouts.app')

@section('content')
<div id="content">
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Detail DO MADINQU FASHION</h4>

    <div>
        <form action="{{ route('do.resync', $do->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Resync ke Google Sheets?')">
            @csrf

            <button type="submit" class="btn btn-success">
                Resync Sheet
            </button>
        </form>
        <a href="{{ route('do.index') }}" class="btn btn-secondary ms-2">
            Kembali
        </a>
    </div>
</div>

<div class="card mb-3">
<div class="card-body">

<div class="row">

<div class="col-md-3">
<label><b>No DO</b></label>
<p>{{ $do->no_do }}</p>
</div>

<div class="col-md-3">
<label><b>Lokasi</b></label>
<p>{{ $do->lokasi }}</p>
</div>

<div class="col-md-3">
<label><b>Penginput</b></label>
<p>{{ $do->penginput }}</p>
</div>

<div class="col-md-3">
<label><b>Tanggal & Waktu Do</b></label>
<p>{{ $do->created_at->format('d-m-Y H:i') }}</p>
</div>

</div>

</div>
</div>


<div class="card">
<div class="card-body">

<h5 class="mb-3">Detail Barang</h5>

<table id="tableDetailAudit" class="table table-bordered table-striped">

<thead class="table-dark">
<tr>
<th width="60">No</th>
<th>Barang</th>
<th width="120">Qty</th>
<th width="150">Satuan</th>
{{-- <th width="150">Harga</th> --}}
<th width="150">Aksi</th>
</tr>
</thead>

<tbody>

@forelse($do->detailDos as $d)

<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $d->produk }}</td>
<td>{{ $d->qty }}</td>
<td>{{ $d->satuan }}</td>
{{-- <td>{{ $d->harga }}</td> --}}
<td>
    <form action="{{ route('delete-do-detail', $d->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
    </form>
</td>
</tr>

@empty

<tr>
<td colspan="4" class="text-center">
Belum ada data do
</td>
</tr>

@endforelse

</tbody>

</table>

</div>
</div>

</div>
</div>

@endsection


@push('css')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

@endpush


@push('js')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>

$(document).ready(function(){

$('#tableDetailAudit').DataTable({

pageLength:10,

order:[[1,'asc']],

language:{
search:"Cari Barang:",
lengthMenu:"Tampilkan _MENU_ data",
info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
paginate:{
next:"Next",
previous:"Prev"
},
zeroRecords:"Barang tidak ditemukan"
}

});

});

</script>

@endpush