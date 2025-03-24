@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Data Barcode</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('barcode.store') }}" method="POST" id="barcodeForm">
                        @csrf
                        <div class="form-group">
                            <label for="produk">Nama Produk</label>
                            <input type="text" class="form-control" id="produk" name="produk" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_beli">Tanggal Beli</label>
                            <input type="date" class="form-control" id="tanggal_beli" name="tanggal_beli" required>
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
                        
                        <button type="submit" class="btn btn-primary" style="margin-top: 30px">Simpan</button>
                    </form>
                    <a href="{{ route('barcode.index') }}">
                        <button class="btn btn-secondary" style="margin-top: 10px">Lihat Daftar Barcode</button>
                      </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
