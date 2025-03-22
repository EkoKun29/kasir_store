@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Edit Detail Pembelian</h1>


    <form action="{{ route('pembelian.detail.update', $detail->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="produk" class="form-label">Produk</label>
            <input type="text" id="produk" name="produk" value="{{ old('produk', $detail->produk) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" id="harga" name="harga" value="{{ old('harga', $detail->harga) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="qty" class="form-label">Jumlah (Qty)</label>
            <input type="number" id="qty" name="qty" value="{{ old('qty', $detail->qty) }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
