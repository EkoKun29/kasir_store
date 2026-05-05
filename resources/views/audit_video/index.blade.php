@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Audit Harian</h5>

        <div class="table-responsive">
            <table id="tableAudit" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Tanggal</th>
                        <th>Penginput</th>
                        <th>Toko</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $dt)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $dt->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $dt->user->name }}</td>
                        <td>{{ $dt->toko }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('audit-video.show', $dt->id) }}" class="btn btn-primary btn-sm">Detail</a>

                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete({{ $dt->id }})">
                                Hapus
                            </button>

                            <form id="delete-form-{{ $dt->id }}"
                                action="{{ route('audit-video.delete', $dt->id) }}"
                                method="POST" style="display: none;">
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
</div>
@endsection

@push('css')
    <style>
        .table td, .table th {
    white-space: nowrap;
}

@media (max-width: 768px) {
    table {
        font-size: 12px;
    }

    .btn {
        font-size: 11px;
        padding: 2px 6px;
    }
}
    </style>
@endpush

@push('js')
<script>
$(document).ready(function () {

    let table = $('#tableAudit').DataTable({
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
        order: [[1, 'desc']]
    });

    // 🔥 auto nomor
    table.on('order.dt search.dt draw.dt', function () {
        table.column(0).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

});
</script>
@endpush