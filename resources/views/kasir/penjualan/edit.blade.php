@extends('kasir.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Penjualan</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control" value="{{ $penjualan->nomor_surat }}" required readonly>
                    </div>
                    {{-- <div class="mb-3">
                        <label>ID User</label>
                        <input type="number" name="id_user" class="form-control" value="{{ $penjualan->id_user }}" required>
                    </div> --}}
                    <div class="mb-3">
                        <label>ID Kios</label>
                        <input type="number" name="id_kios" class="form-control" value="{{ $penjualan->id_kios }}" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label>Potongan</label>
                        <input type="number" name="potongan" class="form-control" value="{{ $penjualan->potongan }}">
                    </div> --}}
                    <div class="mb-3">
                        <label>Status Penjualan</label>
                        <input type="text" name="status_penjualan" class="form-control" value="{{ $penjualan->status_penjualan }}">
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
</div>
@endsection
