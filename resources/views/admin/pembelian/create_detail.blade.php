@extends('admin.layouts.app')  

@section('content') 
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h1 class="m-0"><i class="fas fa-shopping-cart mr-2"></i> Tambah Detail Pembelian</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('pembelian.detail.store', $pembelian->id) }}" method="POST">
                @csrf
                
                <div id="product-list">
                    <div class="product-item card border-left-primary shadow-sm mb-3">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="produk"><i class="fas fa-box mr-1"></i> Produk</label>
                                <input type="text" class="form-control" name="produk" required>
                            </div>
                            <div class="form-group">
                                <label for="harga"><i class="fas fa-tag mr-1"></i> Harga</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" name="harga" min="1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qty"><i class="fas fa-cubes mr-1"></i> Jumlah (Qty)</label>
                                <input type="number" class="form-control" name="qty" min="1" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="add-product" class="btn btn-info">
                        <i class="fas fa-plus-circle"></i> Tambah Produk
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center">
                <a href="{{ route('pembelian.index') }}" class="btn btn-primary">
                    <i class="fas fa-check-circle"></i> Selesai
                </a>
            </div>
        </div>
    </div>
</div>

<script>
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