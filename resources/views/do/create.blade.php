@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div id="content">
<div class="container mt-4">

<div class="container mt-4">

    <h3>DO MADINQU FASHION</h3>

@if(!$do)
<div class="card p-4 mb-4">

    <form method="POST" action="{{ route('do.store') }}">
        @csrf

        <div class="mb-3">
            <label>Nama Personil</label>
            <input type="text" name="nama_personil" class="form-control" required>
        </div>

        </div>

        <button class="btn btn-primary">
            Mulai DO
        </button>

    </form>

</div>

@else

<div class="alert alert-success">
    <strong>Lokasi:</strong> {{ $do->lokasi }}
</div>

{{-- FORM INPUT --}}
<div class="card shadow-sm mb-4">

    <div class="card-header bg-primary text-white">
        <b>Tambah Barang</b>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>Nama Barang</label>

                <select id="barang" class="form-control">
                    <option value=""></option>

                    @foreach($db as $b)
                        <option value="{{ $b->produk }}">
                            {{ $b->produk }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-2 mb-3">
                <label>Qty</label>
                <input type="number" id="qty" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Satuan</label>

                <select id="satuan" name="satuan" class="form-control">
                    <option value=""></option>
                    <option value="PCS">PCS</option>
                </select>
            </div>

            <div class="col-md-2 mb-3">
                <label>Harga</label>
                <input type="number" id="harga" class="form-control">
            </div>

        </div>

        <button onclick="addItem()" class="btn btn-success w-100">
            💾 Simpan Item
        </button>

    </div>

</div>

{{-- TABEL --}}
<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">
        <b>Data MADINQU FASHION</b>
    </div>

    <div class="card-body">
        <div class="table-responsive">

        <table id="tableDo" class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($detail as $i => $d)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $d->produk }}</td>
                    <td>{{ $d->qty }}</td>
                    <td>{{ $d->satuan }}</td>
                    <td>{{ $d->harga }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm btn-delete"
                                data-id="{{ $d->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach

            </tbody>

        </table>
        <div class="mt-3 text-end">

            <form action="{{ route('do.finish', $do->id) }}" method="POST">
                @csrf

                <button type="submit"
                        class="btn btn-primary"
                        onclick="return confirm('Selesaikan DO ini?')">
                    <i class="fa fa-check"></i> Selesai DO
                </button>

            </form>

        </div>
        </div>
    </div>

</div>

@endif

</div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
.select2-container {
    width: 100% !important;
}

.select2-container .select2-selection--single {
    height: 38px !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    display: flex;
    align-items: center;
    padding: 6px 10px !important;
}

.select2-selection__rendered {
    line-height: normal !important;
}

.select2-selection__arrow {
    height: 100% !important;
}

.select2-container--bootstrap-5 .select2-selection {
    border: 1px solid #ced4da !important;
}
</style>
@endpush
@push('js')
<script>

let table;

$(document).ready(function () {

    // Select2
    $("#barang").select2({
        width: "100%"
    });

    $("#nama_personil").select2({
        width: "100%"
    });

    $("#satuan").select2({
        width: "100%"
    });

    // DataTable
    table = $("#tableDo").DataTable({
        responsive: true,
        autoWidth: false
    });

    // Auto nomor
    table.on("order.dt search.dt", function () {
        table.column(0).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

});


// ==========================
// TAMBAH DATA
// ==========================
function addItem() {

    let barang      = $("#barang").val();
    let qty         = $("#qty").val();
    let satuan      = $("#satuan").val();
    let harga       = $("#harga").val();

    if (!barang || !qty || !satuan || !harga) {
        alert("Lengkapi data!");
        return;
    }

    $.ajax({

        url: "{{ route('do.store-detail') }}",
        type: "POST",

        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },

        data: {
            barang: barang,
            qty: qty,
            satuan: satuan,
            harga: harga
        },

        success: function (res) {

            table.row.add([
                "",
                res.data.produk,
                res.data.qty,
                res.data.satuan,
                res.data.harga,
                `
                <button class="btn btn-danger btn-sm btn-delete"
                        data-id="${res.data.id}">
                    Hapus
                </button>
                `
            ]).draw(false);

            // Reset Form
            $("#barang").val(null).trigger("change");
            $("#qty").val("");
            $("#satuan").val("");
            $("#harga").val("");

        },

        error: function (xhr) {

            console.log(xhr.responseJSON ?? xhr.responseText);

            if (xhr.responseJSON && xhr.responseJSON.message) {
                alert(xhr.responseJSON.message);
            } else {
                alert("Gagal menyimpan data.");
            }

        }

    });

}


// ==========================
// HAPUS DATA
// ==========================
$(document).on("click", ".btn-delete", function () {

    if (!confirm("Hapus data?")) return;

    let id = $(this).data("id");
    let row = $(this).closest("tr");

    $.ajax({

        url: "{{ url('do/detail/delete') }}/" + id,
        type: "POST",

        data: {
            _token: "{{ csrf_token() }}",
            _method: "DELETE"
        },

        success: function () {

            table.row(row).remove().draw(false);

        },

        error: function (xhr) {

            console.log(xhr.responseJSON ?? xhr.responseText);
            alert("Gagal menghapus data.");

        }

    });

});

</script>
@endpush