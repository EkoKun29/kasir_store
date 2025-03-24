@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Produk</h5>

        <form action="{{ route('pembelian.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="produk" class="form-label">Produk</label>
                <input type="text" class="form-control" id="produk" name="produk" value="{{ $detail->produk }}" required>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" value="{{ $detail->harga }}" required>
            </div>

            <div class="mb-3">
                <label for="qty" class="form-label">Qty</label>
                <input type="number" class="form-control" id="qty" name="qty" value="{{ $detail->qty }}" required>
            </div>

            <div class="mb-3">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="number" class="form-control" id="subtotal" name="subtotal" value="{{ $detail->subtotal }}" required>
            </div>

            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('pembelian.index', $detail->pembelian_id) }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
