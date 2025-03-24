@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Detail Pembelian</h5>
        <table class="table table-bordered">
            <thead>
                <tr style="text-align: center">
                    <th>No</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php $no =1; ?>
            <tbody>
                @if($pembelian->detailPembelian->isNotEmpty())
                    @foreach($pembelian->detailPembelian as $key => $detail)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail->produk }}</td>
                        <td>Rp. {{ number_format($detail->harga, 2) }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>Rp. {{ number_format($detail->subtotal, 2) }}</td>
                        <td>
                            <a href="{{ route('pembelian.edit-detail', $detail->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            {{-- <form action="{{ route('detailPembelian.destroy', $detail->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                            </form> --}}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">Data tidak tersedia</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <a href="{{ route('pembelian.index') }}" class="btn btn-primary mt-3">Kembali</a>
    </div>
</div>
@endsection
