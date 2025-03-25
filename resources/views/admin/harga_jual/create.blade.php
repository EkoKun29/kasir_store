@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Input Harga Jual</h5>

        <form action="{{ route('hargajual.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="barcode_id">Produk</label>
                <select name="barcode_id" class="form-control">
                    @foreach ($barcodes as $barcode)
                        <option value="{{ $barcode->id }}">{{ $barcode->produk }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" placeholder="Masukkan harga jual">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
</div>
@endsection
