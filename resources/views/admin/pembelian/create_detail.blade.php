@extends('admin.layouts.app')  

@section('content') 
<div class="container"> 
    <div class="card mb-4">
        <div class="card-header">
            <h5>Form Input Produk</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pembelian.detail.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pembelian_id" value="{{ $pembelian->id }}">
            <div id="product-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Produk:</label>

                            <select class="form-control js-example-basic-single" name="produk"
                                autocomplete="off" required>
                                <option value="" selected>Produk</option>
                                @foreach($produkKoperasi as $pk)
                                    <option value="{{$pk->nama_barang}}">{{$pk->nama_barang}}</option>
                                @endforeach
                                
                            </select>
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="harga">Harga Beli</label>
                            <input type="number" class="form-control" name="harga" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qty">Jumlah (Qty)</label>
                            <input type="number" class="form-control" name="qty" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual (Satuan)</label>
                            <input type="number" class="form-control" name="harga_jual" min="1">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3">Simpan</button>
            </div>
            <form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Detail Produk</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="products-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Harga Jual (Satuan)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailPembelians as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->produk }}</td>
                            <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{route('pembelian.hapusDetail', $item->id) }}" type="button" class="btn btn-danger btn-sm" >
                                    Hapus
                                </a>  
                                <a href="{{ route('barcode.show', $item->barcode_id) }}" 
                                    class="btn btn-success btn-sm" 
                                    style="margin-top: 5px">
                                    Cetak Barcode
                                </a>       
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>    
    <a href="{{ route('pembelian.nota', $pembelian->id) }}" 
        class="btn btn-primary mt-3"
        >
         Selesai & Cetak Nota
     </a>     
</div>






@endsection
@push('js')
<!-- jQuery (wajib untuk Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
});
</script>

@endpush