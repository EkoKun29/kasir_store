@extends('admin.layouts.app')  

@section('content') 
<div class="container"> 
    <div class="card mb-4">
        <div class="card-header">
            <h5>Form Input Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nomor Surat:</strong> {{ $penjualan->nomor_surat ?? '-' }}</p>
                    <p><strong>ID Kios:</strong> {{ $penjualan->id_kios ?? '-' }}</p>
                    <p><strong>Status Penjualan:</strong> {{ $penjualan->status_penjualan ?? '-' }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>Tanggal:</strong> 
                        {{ $penjualan->created_at 
                            ? $penjualan->created_at->format('d M Y H:i') 
                            : '-' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="product-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="barcode">Barcode ID</label>
                            <input type="text" class="form-control" id="barcode-input" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="produk">Nama Produk</label>
                            <input type="text" class="form-control" id="produk-input" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="harga">Harga Jual</label>
                            <input type="number" class="form-control" id="harga-input" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="pcs">Jumlah (pcs)</label>
                            <input type="number" class="form-control" id="pcs-input" min="1" required readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="subtotal">Subtotal</label>
                            <input type="text" class="form-control" id="subtotal-input" readonly>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-to-table" class="btn btn-primary mt-3">Tambah Produk</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Detail Penjualan</h5>
        </div>
        <div class="card-body">
            <form id="submit-form" action="{{ route('detail_penjualan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="penjualan_id" value="{{ $penjualan->id }}">
                <table class="table table-striped" id="products-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barcode</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Pcs</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailPenjualans as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->barcode }}</td>
                                <td>{{ $item->produk }}</td>
                                <td>Rp. {{ $item->harga }}</td>
                                <td>{{ $item->pcs }}</td>
                                <td>Rp. {{ $item->subtotal }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td id="total-amount">Rp. 0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Potongan:</strong></td>
                            <td>
                                <input type="number" 
                                       class="form-control" 
                                       id="potongan-input" 
                                       name="potongan" 
                                       min="0" 
                                       value="0" 
                                       style="width: 120px;"
                                >
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total Setelah Potongan:</strong></td>
                            <td id="total-setelah-potongan">Rp. 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <button type="submit" class="btn btn-success mt-3">Simpan Penjualan</button>
            </form>
        </div>
    </div>
    
    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">Selesai</a>
</div>

<script>
    // Array to store all products
    let products = [];
    
    // Fetch barcode details when barcode input changes
    document.getElementById('barcode-input').addEventListener('change', function() {
        const barcodeId = this.value;
        
        fetch(`/get-barcode-details/${barcodeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    this.value = '';
                    document.getElementById('produk-input').value = '';
                    document.getElementById('harga-input').value = '';
                } else {
                    document.getElementById('produk-input').value = data.produk;
                    document.getElementById('harga-input').value = data.harga_jual;
                    
                    // Add default quantity if not set
                    if (!document.getElementById('pcs-input').value) {
                        document.getElementById('pcs-input').value = 1;
                    }
                    
                    calculateSubtotal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data produk');
            });
    });
    
    // Calculate subtotal when quantity changes
    document.getElementById('pcs-input').addEventListener('input', calculateSubtotal);
    
    function calculateSubtotal() {
        const harga = parseFloat(document.getElementById('harga-input').value) || 0;
        const pcs = parseInt(document.getElementById('pcs-input').value) || 1;
        const subtotal = harga * pcs;
        
        document.getElementById('subtotal-input').value = 
            subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    }
    
    // Add product to table
    document.getElementById('add-to-table').addEventListener('click', function() {
        const barcode = document.getElementById('barcode-input').value;
        const produk = document.getElementById('produk-input').value;
        const harga = parseFloat(document.getElementById('harga-input').value);
        const pcs = parseInt(document.getElementById('pcs-input').value);
        
        // Validate inputs
        if (!barcode || !produk || isNaN(harga) || isNaN(pcs) || harga <= 0 || pcs <= 0) {
            alert('Mohon isi semua data produk dengan benar');
            return;
        }
        
        // Check if product with same barcode already exists
        const existingProductIndex = products.findIndex(p => p.barcode === barcode);
        
        if (existingProductIndex !== -1) {
            // If product exists, update its quantity
            products[existingProductIndex].pcs += pcs;
            products[existingProductIndex].subtotal = 
                products[existingProductIndex].harga * products[existingProductIndex].pcs;
        } else {
            // Add new product
            products.push({
                barcode: barcode,
                produk: produk,
                harga: harga,
                pcs: pcs,
                subtotal: harga * pcs
            });
        }
        
        // Update the table
        updateProductsTable();
        
        // Clear the form
        document.getElementById('barcode-input').value = '';
        document.getElementById('produk-input').value = '';
        document.getElementById('harga-input').value = '';
        document.getElementById('pcs-input').value = '';
        document.getElementById('subtotal-input').value = '';
        
        // Focus on the barcode input
        document.getElementById('barcode-input').focus();
    });
    
    function updateProductsTable() {
        const tableBody = document.querySelector('#products-table tbody');
        
        // Clear existing dynamic rows (keep existing rows from backend)
        const dynamicRows = tableBody.querySelectorAll('tr[data-dynamic="true"]');
        dynamicRows.forEach(row => row.remove());
        
        // Recalculate total
        let totalAmount = 0;
        
        // Add new rows for dynamically added products
        products.forEach((product, index) => {
            const row = document.createElement('tr');
            row.setAttribute('data-dynamic', 'true');
            
            // Calculate row number (include existing rows)
            const rowNumber = tableBody.querySelectorAll('tr').length + 1;
            
            row.innerHTML = `
                <td>${rowNumber}</td>
                <td>${product.barcode}</td>
                <td>${product.produk}</td>
                <td>Rp. ${product.harga.toLocaleString('id-ID')}</td>
                <td>${product.pcs}</td>
                <td>Rp. ${product.subtotal.toLocaleString('id-ID')}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteProduct(${index})">
                        Hapus
                    </button>
                </td>
            `;
            
            tableBody.appendChild(row);
            
            // Add to total
            totalAmount += product.subtotal;
        });
        
        // Update total amount
        document.getElementById('total-amount').textContent = 
            `Rp. ${totalAmount.toLocaleString('id-ID')}`;
        
        // Calculate total after discount
        calculateTotalSetelahPotongan(totalAmount);
        
        // Add hidden inputs for form submission
        const submitForm = document.getElementById('submit-form');
        
        // Remove any existing dynamic hidden inputs
        submitForm.querySelectorAll('input[data-dynamic="true"]').forEach(input => input.remove());
        
        // Add new hidden inputs for each product
        products.forEach((product, index) => {
            const barcodeInput = document.createElement('input');
            barcodeInput.type = 'hidden';
            barcodeInput.name = `products[${index}][barcode]`;
            barcodeInput.value = product.barcode;
            barcodeInput.setAttribute('data-dynamic', 'true');
            submitForm.appendChild(barcodeInput);
            
            const produkInput = document.createElement('input');
            produkInput.type = 'hidden';
            produkInput.name = `products[${index}][produk]`;
            produkInput.value = product.produk;
            produkInput.setAttribute('data-dynamic', 'true');
            submitForm.appendChild(produkInput);
            
            const hargaInput = document.createElement('input');
            hargaInput.type = 'hidden';
            hargaInput.name = `products[${index}][harga]`;
            hargaInput.value = product.harga;
            hargaInput.setAttribute('data-dynamic', 'true');
            submitForm.appendChild(hargaInput);
            
            const pcsInput = document.createElement('input');
            pcsInput.type = 'hidden';
            pcsInput.name = `products[${index}][pcs]`;
            pcsInput.value = product.pcs;
            pcsInput.setAttribute('data-dynamic', 'true');
            submitForm.appendChild(pcsInput);
            
            const subtotalInput = document.createElement('input');
            subtotalInput.type = 'hidden';
            subtotalInput.name = `products[${index}][subtotal]`;
            subtotalInput.value = product.subtotal;
            subtotalInput.setAttribute('data-dynamic', 'true');
            submitForm.appendChild(subtotalInput);
        });
    }

    // Fungsi untuk menghapus produk dari tabel
    function deleteProduct(index) {
        // Hapus produk dari array
        products.splice(index, 1);
        
        // Update tabel
        updateProductsTable();
    }

    // Fungsi untuk menghitung total setelah potongan
    function calculateTotalSetelahPotongan(totalAmount) {
        const potonganInput = document.getElementById('potongan-input');
        const totalSetelahPotonganElement = document.getElementById('total-setelah-potongan');
        
        // Tambahkan event listener untuk input potongan
        potonganInput.addEventListener('input', updatePotongan);
        
        function updatePotongan() {
            const potongan = parseFloat(potonganInput.value) || 0;
            const totalSetelahPotongan = Math.max(totalAmount - potongan, 0);
            
            totalSetelahPotonganElement.textContent = 
                `Rp. ${totalSetelahPotongan.toLocaleString('id-ID')}`;
        }
        
        // Perhitungan awal
        updatePotongan();
    }

    // Inisialisasi perhitungan potongan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const initialTotal = 0;
        calculateTotalSetelahPotongan(initialTotal);
    });
</script>
@endsection