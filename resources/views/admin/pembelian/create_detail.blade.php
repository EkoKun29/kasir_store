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
                            <label for="produk">Produk</label>
                            <input type="text" class="form-control" name="produk" required>
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
                            <label for="harga_jual">Harga Jual</label>
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
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailPembelians as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->produk }}
                                </td>
                                <td>
                                   Rp. {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ $item->qty }}
                                </td>
                                <td>
                                    <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </td>
                                <td>
                                    <td>Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>         
                                </td>
                            </tr>
                        @endforeach
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
    
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">Selesai</a>
</div>



<script>
    // Array to store all products
    let products = [];
    
    // Calculate subtotal when inputs change
    document.getElementById('harga-input').addEventListener('input', calculateSubtotal);
    document.getElementById('qty-input').addEventListener('input', calculateSubtotal);
    
    function calculateSubtotal() {
        const harga = parseFloat(document.getElementById('harga-input').value) || 0;
        const qty = parseInt(document.getElementById('qty-input').value) || 0;
        const subtotal = harga * qty;
        
        document.getElementById('subtotal-input').value = 
            subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    }
    
    // Add product to table
    document.getElementById('add-to-table').addEventListener('click', function() {
        const produk = document.getElementById('produk-input').value;
        const harga = parseFloat(document.getElementById('harga-input').value);
        const qty = parseInt(document.getElementById('qty-input').value);
        
        // Validate inputs
        if (!produk || isNaN(harga) || isNaN(qty) || harga <= 0 || qty <= 0) {
            alert('Mohon isi semua data produk dengan benar');
            return;
        }
        
        const subtotal = harga * qty;
        
        // Add to products array
        products.push({
            produk: produk,
            harga: harga,
            qty: qty,
            subtotal: subtotal
        });
        
        // Update the table
        updateProductsTable();
        
        // Clear the form
        document.getElementById('produk-input').value = '';
        document.getElementById('harga-input').value = '';
        document.getElementById('qty-input').value = '';
        document.getElementById('subtotal-input').value = '';
        
        // Focus on the product input
        document.getElementById('produk-input').focus();
    });
    
    // Update the products table
    function updateProductsTable() {
        const tbody = document.querySelector('#products-table tbody');
        tbody.innerHTML = '';
        
        let totalAmount = 0;
        
        products.forEach((product, index) => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${product.produk}</td>
                <td>${product.harga.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                <td>${product.qty}</td>
                <td>${product.subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger delete-product" data-index="${index}">
                        Hapus
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
            totalAmount += product.subtotal;
        });
        
        // Update total
        document.getElementById('total-amount').textContent = 
            totalAmount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    }
    
    // Delete product from table
    document.querySelector('#products-table tbody').addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-product')) {
            const index = parseInt(event.target.getAttribute('data-index'));
            
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                products.splice(index, 1);
                updateProductsTable();
            }
        }
    });
    
    // Submit all products
    document.getElementById('submit-form').addEventListener('submit', function(event) {
        if (products.length === 0) {
            event.preventDefault();
            alert('Tidak ada produk yang ditambahkan');
            return;
        }
        
        // Clear previous hidden inputs
        const hiddenInputs = document.querySelectorAll('.hidden-product-input');
        hiddenInputs.forEach(input => input.remove());
        
        // Add hidden inputs for all products
        products.forEach((product, index) => {
            const form = document.getElementById('submit-form');
            
            const produkInput = document.createElement('input');
            produkInput.type = 'hidden';
            produkInput.name = produk[];
            produkInput.value = product.produk;
            produkInput.classList.add('hidden-product-input');
            form.appendChild(produkInput);
            
            const hargaInput = document.createElement('input');
            hargaInput.type = 'hidden';
            hargaInput.name = harga[];
            hargaInput.value = product.harga;
            hargaInput.classList.add('hidden-product-input');
            form.appendChild(hargaInput);
            
            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = qty[];
            qtyInput.value = product.qty;
            qtyInput.classList.add('hidden-product-input');
            form.appendChild(qtyInput);
            
            const subtotalInput = document.createElement('input');
            subtotalInput.type = 'hidden';
            subtotalInput.name = subtotal[];
            subtotalInput.value = product.subtotal;
            subtotalInput.classList.add('hidden-product-input');
            form.appendChild(subtotalInput);
        });
    });
</script>
@endsection