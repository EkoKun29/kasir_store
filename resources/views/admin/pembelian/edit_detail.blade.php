@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Produk</h5>

        <form action="{{ route('pembelian.updateDetail', $detailPembelian->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="beli_id" value="{{ $detailPembelian->pembelian->id }}">
            <div class="mb-3">
                <label for="produk" class="form-label">Produk</label>
                    <div class="form-group">
                        <label>Produk:</label>

                        <select class="form-control js-example-basic-single" name="produk"
                            autocomplete="off" required>
                            <option value="{{ $detailPembelian->produk }}" selected>{{$detailPembelian->produk}}</option>
                            @foreach($produkKoperasi as $pk)
                                <option value="{{$pk->nama_barang}}">{{$pk->nama_barang}}</option>
                            @endforeach
                            
                        </select>
                      </div>
                {{-- <input type="text" class="form-control" id="produk" name="produk" value="{{ $detailPembelian->produk }}" required> --}}
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" value="{{ $detailPembelian->harga }}" required>
            </div>

            <div class="mb-3">
                <label for="qty" class="form-label">Qty</label>
                <input type="number" class="form-control" id="qty" name="qty" value="{{ $detailPembelian->qty }}" required>
            </div>

            <div class="mb-3">
                <label for="harga_jual" class="form-label">Harga Jual</label>
                <input type="number" class="form-control" id="harga_jual" name="harga" value="{{ $detailPembelian->harga_jual }}" required>
            </div>

            {{-- <div class="mb-3">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="number" class="form-control" id="subtotal" name="subtotal" value="{{ $detailPembelian->subtotal }}" required>
            </div> --}}

            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('pembelian.index', $detailPembelian->pembelian_id) }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
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
