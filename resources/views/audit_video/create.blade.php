@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div id="content">
<div class="container mt-4">

    <h3>Audit Video</h3>

    @if(!$audit)

    <div class="card p-4">
        <form method="POST" action="{{ route('audit-video.store') }}">
            @csrf

            <button type="submit" class="btn btn-primary">
                Mulai Audit
            </button>
        </form>
    </div>

    @else

    {{-- FORM INPUT --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <b>Tambah Barang</b>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Nama Barang</label>
                    <select id="barang" class="form-control">
                        <option value="">-- pilih barang --</option>
                        @foreach($barang as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Qty</label>
                    <input type="number" id="qty" class="form-control">
                </div>
{{-- 
                <div class="col-md-3">
                <label>Tgl Exp</label>
                <input type="text" id="tgl_exp" name="tgl_exp" class="form-control" placeholder="Pilih tanggal expired">
                </div> --}}

            </div>

            <button onclick="addItem()" class="btn btn-success w-100">
                💾 Simpan Item
            </button>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <b>Data Audit</b>
        </div>

        <div class="card-body">
            <table id="tableAudit" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($detail as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $d->produk }}</td>
                        <td>{{ $d->qty }}</td>
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
        </div>
    </div>

    @endif

</div>
</div>
@endsection

@push('js')

<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
let table = null;
let fp = null; // 🔥 simpan instance flatpickr

document.addEventListener("DOMContentLoaded", function(){

    console.log("JS jalan");

    // ======================
    // FLATPICKR (FIX)
    // ======================
    // if (document.querySelector("#tgl_exp")) {
    //     fp = flatpickr("#tgl_exp", {
    //         dateFormat: "Y-m-d",
    //         altInput: true,
    //         altFormat: "d F Y"
    //     });
    // }

    // ======================
    // SELECT2
    // ======================
    if (window.jQuery && $.fn.select2 && $("#barang").length) {
        $("#barang").select2({ width: '100%' });
    }

    // ======================
    // DATATABLE (FIX NOMOR)
    // ======================
    if (window.jQuery && $.fn.DataTable && $('#tableAudit').length) {

        table = $('#tableAudit').DataTable({
            responsive: true,
            autoWidth: false,
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[1, 'asc']]
        });

        // 🔥 AUTO NOMOR FIX
        table.on('order.dt search.dt draw.dt', function () {
            table.column(0).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

    }

});


// ==========================
// TAMBAH DATA (FIX TOTAL)
// ==========================
function addItem(){

    if (typeof $ === 'undefined') {
        alert('jQuery belum load!');
        return;
    }

    let barang = $('#barang').val();
    let qty = $('#qty').val();
    let tgl_exp = $('#tgl_exp').val();

    if(!barang || !qty ){
        alert('Lengkapi data!');
        return;
    }

    $.ajax({
        url: "{{ route('audit-video.store-detail') }}",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        data: {
            barang: barang,
            qty: qty
        },
        success: function(res){

            let id = res.data.id;
            let no = $('#tableAudit tbody tr').length + 1;

            if (!table) {
                $('#tableAudit tbody').append(`
                    <tr>
                        <td>${no}</td>
                        <td>${res.data.produk}</td>
                        <td>${res.data.qty}</td>
                        <td>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${id}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                `);
            } else {
                table.row.add([
                    null, // 🔥 WAJIB null biar nomor jalan
                    res.data.produk,
                    res.data.qty,
                    `<button class="btn btn-danger btn-sm btn-delete" data-id="${id}">
                        Hapus
                    </button>`
                ]).draw(false);
            }

            // ======================
            // RESET INPUT (FIX)
            // ======================
            $('#barang').val(null).trigger('change');
            $('#qty').val('');

            if (fp) {
                fp.clear(); // 🔥 reset flatpickr
            }

            $('#barang').focus();

        },
        error: function(xhr){
            console.log(xhr.responseText);
            alert('Gagal simpan');
        }
    });
}


// ==========================
// DELETE (FIX FINAL)
// ==========================
$(document).on('click', '.btn-delete', function () {

    let id = $(this).data('id');
    let el = this;

    if(!confirm('Hapus data?')) return;

    $.ajax({
        url: "{{ url('audit-video/delete-detail') }}/" + id,
        method: "POST",
        data: {
            _method: "DELETE",
            _token: "{{ csrf_token() }}"
        },
        success: function(){

            if (table) {
                table.row($(el).closest('tr')).remove().draw();
            } else {
                $(el).closest('tr').remove();
            }

        },
        error: function(xhr){
            console.log(xhr.responseText);
            alert('Gagal hapus');
        }
    });

});
</script>

@endpush