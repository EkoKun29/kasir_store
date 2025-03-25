@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Harga Jual</h5>

        <form action="{{ route('hargajual.update', $barcode->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="produk">Produk</label>
                <input type="text" name="produk" class="form-control" value="{{ $barcode->produk }}" readonly>
            </div>

            <div class="form-group">
                <label for="qty">Qty</label>
                <input type="number" name="qty" class="form-control" value="{{ $barcode->qty }}" readonly>
            </div>

            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" value="{{ $barcode->harga_jual }}" placeholder="Masukkan harga jual">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
            <a href="{{ route('hargajual.index') }}" class="btn btn-secondary mt-3">Batal</a>
        </form>
    </div>
</div>
@endsection
