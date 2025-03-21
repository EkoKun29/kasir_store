@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Detail Pembelian</h1>

    <form action="{{ route('pembelian.detail.store', $pembelian->id) }}" method="POST">
        @csrf
        
        <div id="product-list">
            <div class="product-item">
                <div class="form-group">
                    <label for="produk">Produk</label>
                    <input type="text" class="form-control" name="produk[]" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control harga-input" name="harga[]" min="1" required>
                </div>
                <div class="form-group">
                    <label for="qty">Jumlah (Qty)</label>
                    <input type="number" class="form-control qty-input" name="qty[]" min="1" required>
                </div>
                <div class="form-group">
                    <label for="subtotal">Subtotal</label>
                    <input type="text" class="form-control subtotal-input" name="subtotal[]" readonly>
                </div>
                <button type="button" class="btn btn-danger remove-product">Hapus Produk</button>
            </div>
        </div>

        <button type="button" id="add-product" class="btn btn-primary mt-3">Tambah Produk</button>
        <button type="submit" class="btn btn-success mt-3">Simpan</button>
    </form>

    <hr>

    <a href="{{ route('pembelian.index') }}" class="btn btn-primary">Selesai</a>
</div>

<script>
    // Fungsi untuk menghitung subtotal
    function calculateSubtotal(productItem) {
        const hargaInput = productItem.querySelector('.harga-input');
        const qtyInput = productItem.querySelector('.qty-input');
        const subtotalInput = productItem.querySelector('.subtotal-input');

        const harga = parseFloat(hargaInput.value) || 0;
        const qty = parseInt(qtyInput.value) || 0;
        const subtotal = harga * qty;

        subtotalInput.value = subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    }

    // Hitung subtotal saat harga atau qty diubah
    document.getElementById('product-list').addEventListener('input', function (event) {
        const productItem = event.target.closest('.product-item');
        if (productItem) {
            calculateSubtotal(productItem);
        }
    });

    // Tambah produk baru
    document.getElementById('add-product').addEventListener('click', function () {
        const productList = document.getElementById('product-list');
        const productItem = document.querySelector('.product-item').cloneNode(true);

        // Bersihkan input pada clone
        productItem.querySelectorAll('input').forEach(input => input.value = '');

        productList.appendChild(productItem);
    });

    // Hapus produk yang dipilih
    document.getElementById('product-list').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-product')) {
            const productItems = document.querySelectorAll('.product-item');

            // Cegah semua produk terhapus
            if (productItems.length > 1) {
                event.target.closest('.product-item').remove();
            } else {
                alert('Minimal harus ada satu produk.');
            }
        }
    });
</script>
@endsection
