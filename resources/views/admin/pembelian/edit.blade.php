@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Data Pembelian</h5>
    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="supplier" class="form-label">Supplier</label>
            <input type="text" id="supplier" name="supplier" value="{{ old('supplier', $pembelian->supplier) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_beli" class="form-label">Tanggal Beli</label>
            <input type="date" id="tanggal_beli" name="tanggal_beli" value="{{ old('tanggal_beli', $pembelian->tanggal_beli) }}" class="form-control" required>
        </div>

        {{-- <div class="mb-3">
            <label for="total_harga" class="form-label">Total Harga</label>
            <input type="number" id="total_harga" name="total_harga" value="{{ old('total_harga', $pembelian->total_harga) }}" class="form-control" readonly>
        </div> --}}

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
