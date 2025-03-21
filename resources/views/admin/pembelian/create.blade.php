@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Pembelian</h1>
    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tanggal_beli">Tanggal Beli</label>
            <input type="date" class="form-control" name="tanggal_beli" required>
        </div>
        <div class="form-group">
            <label for="supplier">Supplier</label>
            <input type="text" class="form-control" name="supplier" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
