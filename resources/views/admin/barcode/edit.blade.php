@extends('admin.layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Barcode</h1>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Form Edit Barcode</h5>

        <form action="{{ route('barcode.update', $barcode->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="produk" class="form-label">Produk</label>
                <input type="text" class="form-control" id="produk" name="produk" value="{{ $barcode->produk }}" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_beli" class="form-label">Tanggal Beli</label>
                <input type="date" class="form-control" id="tanggal_beli" name="tanggal_beli" value="{{ $barcode->tanggal_beli }}" required>
            </div>

            <div class="form-group">
                <label for="harga_beli">Harga Beli</label>
                <input type="number" class="form-control" id="harga_beli" name="harga_beli" min="1" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
            </div>

            <div class="form-group">
                <label for="qty">Jumlah (Qty)</label>
                <input type="number" class="form-control" id="qty" name="qty" min="1"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 10px">Simpan Perubahan</button>
            <a href="{{ route('barcode.index') }}" class="btn btn-secondary" style="margin-top: 10px">Batal</a>
        </form>
    </div>
</div>
@endsection
