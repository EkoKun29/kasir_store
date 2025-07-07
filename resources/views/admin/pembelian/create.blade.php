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
                        <select class="form-control js-example-basic-single" name="supplier"
                                autocomplete="off" required>
                                <option value="" selected>Supplier</option>
                                @foreach($kios as $pk)
                                    <option value="{{$pk->toko}}">{{$pk->toko}}</option>
                                @endforeach
                                
                            </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_pembelian" class="form-label">Status Pembelian</label>
                        <select name="status_pembelian" class="form-control">
                            <option>Cash</option>
                            <option>Hutang</option>
                            <option>Transfer</option>
                        </select>
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
