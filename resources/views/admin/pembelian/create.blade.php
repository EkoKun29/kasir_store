@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Pembelian</h3>
                </div>
                <div class="card-body">
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
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px">Simpan</button>
                </form>
            </div>
                </div>
            </div>
        </div>
        </div>
        </div>
@endsection
