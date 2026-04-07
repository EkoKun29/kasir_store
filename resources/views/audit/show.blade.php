@extends('admin.layouts.app')

@section('content')
<div id="content">
<div class="container">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Detail Audit Stok Bulanan</h4>

    <a href="{{ route('audit.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

<div class="card mb-3">
<div class="card-body">

<div class="row">

<div class="col-md-3">
<label><b>Kode Audit</b></label>
<p>{{ $audit->kode }}</p>
</div>

<div class="col-md-3">
<label><b>Kampus</b></label>
<p>{{ $audit->kampus }}</p>
</div>

<div class="col-md-3">
<label><b>Petugas</b></label>
<p>{{ $audit->user->name ?? '-' }}</p>
</div>

<div class="col-md-3">
<label><b>Tanggal Audit</b></label>
<p>{{ $audit->created_at->format('d-m-Y H:i') }}</p>
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
</tr>
</thead>

<tbody>

@forelse($audit->detail as $d)

<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $d->produk }}</td>
<td>{{ $d->qty }}</td>
</tr>

@empty

<tr>
<td colspan="4" class="text-center">
Belum ada data audit
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