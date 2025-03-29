@extends('admin.layouts.app')  

@section('content') 
<div class="container"> 
    <div class="card mb-4">
        <div class="card-header">
            <h5>Form Input Penjualan | {{ $penjualan->nomor_surat ?? '-' }} | {{ $penjualan->id_kios ?? '-' }} | {{ $penjualan->status_penjualan ?? '-' }} </h5>
        </div>
        <div class="card-body">
            <div id="product-form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="barcode">Scan Barcode</label>
                            <input type="text" class="form-control" id="barcode-input" required autofocus>
                        </div>
                    </div>
                </div>
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
                            <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                            <td id="subtotal-amount">Rp. 0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right">
                                <strong>Potongan:</strong>
                            </td>
                            <td>
                                <input type="number" id="potongan-input" class="form-control" min="0" value="0">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td id="total-amount">Rp. 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <input type="hidden" name="subtotal" id="subtotal-hidden">
                <input type="hidden" name="potongan" id="potongan-hidden">
                <input type="hidden" name="total" id="total-hidden">
                <form id="submit-form" action="{{ route('detail_penjualan.store') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="penjualan_id" value="{{ $penjualan->id }}">
                    <button type="submit" class="btn btn-success mt-3">Simpan & Cetak</button>
                </form>
            </form>
        </div>
    </div>
    
    {{-- <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">Selesai</a> --}}
</div>

<script>
    // Array untuk menyimpan semua produk
    let products = [];
    
    // Tambahkan event listener untuk input barcode
    document.getElementById('barcode-input').addEventListener('keypress', function(event) {
        // Hanya jalankan jika tombol Enter ditekan
        if (event.key === 'Enter') {
            event.preventDefault(); // Mencegah form submit
            const barcodeId = this.value;
            
            fetch(`/get-barcode-details/${barcodeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        this.value = '';
                    } else {
                        // Cari apakah produk sudah ada di tabel
                        const existingProductIndex = products.findIndex(p => p.barcode === barcodeId);
                        
                        if (existingProductIndex !== -1) {
                            // Jika produk sudah ada, tambah jumlahnya
                            products[existingProductIndex].pcs += 1;
                            products[existingProductIndex].subtotal = 
                                products[existingProductIndex].harga * products[existingProductIndex].pcs;
                        } else {
                            // Tambah produk baru
                            products.push({
                                barcode: barcodeId,
                                produk: data.produk,
                                harga: data.harga_jual,
                                pcs: 1,
                                subtotal: data.harga_jual
                            });
                        }
                        
                        // Update tabel
                        updateProductsTable();
                        
                        // Kosongkan input dan fokus kembali
                        this.value = '';
                        this.focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data produk');
                });
        }
    });
    
    function updateProductsTable() {
        const tableBody = document.querySelector('#products-table tbody');
        
        // Hapus baris dinamis yang sudah ada
        const dynamicRows = tableBody.querySelectorAll('tr[data-dynamic="true"]');
        dynamicRows.forEach(row => row.remove());
        
        // Hitung ulang subtotal
        let subtotalAmount = 0;
        
        // Tambahkan baris baru untuk produk yang ditambahkan
        products.forEach((product, index) => {
            const row = document.createElement('tr');
            row.setAttribute('data-dynamic', 'true');
            
            // Hitung nomor baris (termasuk baris yang sudah ada)
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
            
            // Tambahkan ke subtotal
            subtotalAmount += product.subtotal;
        });
        
        // Update subtotal amount
        const subtotalElement = document.getElementById('subtotal-amount');
        subtotalElement.textContent = `Rp. ${subtotalAmount.toLocaleString('id-ID')}`;
        
        // Hitung total setelah potongan
        const potonganInput = document.getElementById('potongan-input');
        const potongan = parseFloat(potonganInput.value) || 0;
        const totalAmount = subtotalAmount - potongan;
        
        // Update total amount
        const totalElement = document.getElementById('total-amount');
        totalElement.textContent = `Rp. ${totalAmount.toLocaleString('id-ID')}`;
        
        // Update hidden inputs untuk form submit
        document.getElementById('subtotal-hidden').value = subtotalAmount;
        document.getElementById('potongan-hidden').value = potongan;
        document.getElementById('total-hidden').value = totalAmount;
        
        // Tambahkan input tersembunyi untuk submit form
        const submitForm = document.getElementById('submit-form');
        
        // Hapus input dinamis yang sudah ada
        submitForm.querySelectorAll('input[data-dynamic="true"]').forEach(input => input.remove());
        
        // Tambahkan input tersembunyi baru untuk setiap produk
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

    // Event listener untuk input potongan
    document.getElementById('potongan-input').addEventListener('input', function() {
        updateProductsTable();
    });

    document.getElementById('submit-form').addEventListener('submit', function() {
    setTimeout(() => {
        window.open(`/penjualan/nota/${penjualanId}`, '_blank');
    }, 500); // Delay agar data sudah tersimpan
});

    // Panggil updateProductsTable saat halaman dimuat untuk menginisialisasi total
    updateProductsTable();
</script>
@endsection