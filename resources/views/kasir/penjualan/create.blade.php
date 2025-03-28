@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Penjualan</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('penjualan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="id_kios" class="form-label">ID Kios</label>
                            <input type="number" name="id_kios" class="form-control" required 
                                   placeholder="Masukkan ID Kios" 
                                   value="{{ old('id_kios') }}">
                        </div>
                        <div class="mb-3">
                            <label for="status_penjualan" class="form-label">Status Penjualan</label>
                            <select name="status_penjualan" class="form-control">
                                <option>Cash</option>
                                <option>Piutang</option>
                                <option>Transfer</option>
                            
                            </select>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="potongan" class="form-label">Potongan (Jika Ada)</label>
                            <input type="number" name="potongan" class="form-control" 
                                   placeholder="Masukkan potongan jika ada"
                                   value="{{ old('potongan') }}">
                        </div> --}}
                        <button type="submit" class="btn btn-primary">Buat Penjualan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection