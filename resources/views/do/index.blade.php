@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">DO MADINQU FASHION</h5>

            <a href="{{ route('do.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </a>
        </div>

        @if(Auth::user()->role == 'admin')
        <div class="mb-3">
            <a href="{{ route('do.index',['mode'=>'detail']) }}"
                class="btn {{ request('mode')=='detail' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-list-ul"></i>
                Detail Barang
            </a>
        </div>
        @endif

        <div class="table-responsive">
            <table id="tableDo" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th style="width:50px">No</th>
                        <th>No DO</th>
                        <th>Tanggal</th>
                        <th>Personil</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th style="width:170px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($doStore as $do)
                    <tr>

                        <td></td>

                        <td>{{ $do->no_do }}</td>

                        <td>{{ \Carbon\Carbon::parse($do->created_at)->format('d-m-Y') }}</td>

                        <td>{{ $do->penginput }}</td>

                        <td>{{ $do->lokasi }}</td>

                        <td>
                            @if($do->status == 0)
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>

                        <td class="text-nowrap">

                            <a href="{{ route('do.detail',$do->id) }}"
                                class="btn btn-primary btn-sm">
                                Detail
                            </a>

                            <a href="{{ route('delete-do',$do->id) }}"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus {{ $do->no_do }} ?')">
                                Hapus
                            </a>

                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>
@endsection

@push('css')
<style>
.table td,
.table th{
    white-space: nowrap;
}

.card-title{
    font-weight:600;
}

@media(max-width:768px){

    table{
        font-size:12px;
    }

    .btn{
        font-size:11px;
        padding:2px 6px;
    }

}
</style>
@endpush

@push('js')
<script>
$(function () {

    let table = $('#tableDo').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false
            },
            {
                targets: -1,
                orderable: false,
                searchable: false
            }
        ],
        order: [[2, 'desc']]
    });

    // Nomor otomatis
    table.on('order.dt search.dt draw.dt', function () {
        table.column(0).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

});
</script>
@endpush