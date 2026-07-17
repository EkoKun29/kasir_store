@extends('admin.layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

            <div>
                <h4 class="fw-bold text-primary mb-1">
                    DO MADINQU FASHION
                </h4>

                <small class="text-muted">
                    Monitoring Detail Barang DO
                </small>
            </div>

            @if(Auth::user()->role == 'admin')
            <div class="btn-group">

                <a href="{{ route('do.index') }}"
                    class="btn btn-outline-primary">
                    <i class="fa fa-list"></i> Induk
                </a>

                <a href="{{ route('do.index',['mode'=>'detail']) }}"
                    class="btn {{ request('mode')=='detail' ? 'btn-primary':'btn-outline-primary' }}">
                    <i class="fa fa-table"></i> Detail Barang
                </a>

            </div>
            @endif

        </div>

        <div class="table-responsive">

            <table id="tableDetailDo"
                class="table table-striped table-bordered table-hover align-middle"
                style="width:100%">

                <thead class="table-dark">

                    <tr>

                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>No DO</th>
                        <th>Penginput</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        {{-- <th>Harga</th> --}}
                        <th width="90">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($detailDos as $d)

                    <tr>

                        <td></td>

                        <td>
                            {{ \Carbon\Carbon::parse($d->created_at)->format('d-m-Y H:i') }}
                        </td>

                        <td>
                            {{ $d->doStore?->no_do }}
                        </td>

                        <td>
                            {{ $d->doStore?->penginput }}
                        </td>

                        <td>
                            {{ $d->doStore?->lokasi }}
                        </td>

                        <td>

                            @if($d->doStore?->status)

                                <span class="badge bg-success">
                                    Selesai
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </td>

                        <td>{{ $d->produk }}</td>

                        <td class="text-center">
                            {{ number_format($d->qty) }}
                        </td>

                        <td class="text-center">
                            {{ $d->satuan }}
                        </td>

                        {{-- <td class="text-end">
                            Rp {{ number_format($d->harga,0,',','.') }}
                        </td> --}}

                        <td class="text-center">

                            <a href="{{ route('delete-do',$d->doStore?->id) }}"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus DO {{ $d->doStore?->no_do }} ?')">

                                <i class="fa fa-trash">Hapus</i>

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
    vertical-align: middle;
    white-space: nowrap;
}

.card{
    border-radius:10px;
}

.table-responsive{
    overflow-x:auto;
}

.table-responsive::-webkit-scrollbar{
    height:8px;
}

.table-responsive::-webkit-scrollbar-thumb{
    background:#c7c7c7;
    border-radius:10px;
}

.badge{
    font-size:12px;
    padding:6px 10px;
}

@media(max-width:768px){

    table{
        font-size:12px;
    }

    .btn{
        font-size:11px;
    }

}

</style>

@endpush


@push('js')

<script>

$(function () {

    let table = $('#tableDetailDo').DataTable({

        responsive:true,
        autoWidth:false,
        pageLength:10,

        columnDefs:[
            {
                targets:0,
                orderable:false,
                searchable:false
            },
            {
                targets:-1,
                orderable:false,
                searchable:false
            }
        ],

        order:[[1,'desc']]

    });

    // Nomor otomatis
    table.on('order.dt search.dt draw.dt',function(){

        table.column(0).nodes().each(function(cell,i){

            cell.innerHTML=i+1;

        });

    }).draw();

});

</script>

@endpush