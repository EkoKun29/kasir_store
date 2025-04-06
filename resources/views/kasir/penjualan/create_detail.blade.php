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
            <form id="submit-form" action="{{ route('detail_penjualan.store') }}" method="POST" target="_blank">
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
                                    <button type="button" class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot id="invoice-footer" class="d-none">
                        <tr>
                            <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                            <td id="subtotal-amount" class="text-right font-weight-bold"></td>
                            <td></td>
                        </tr>
                        <tr id="potongan-row" class="d-none">
                            <td colspan="5" class="text-right"><strong>Potongan:</strong></td>
                            <td class="text-right">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="number" id="potongan-input" name="potongan_input" class="form-control text-right" value="0" min="0">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td id="total-amount" class="text-right font-weight-bold"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div id="empty-cart-message" class="alert alert-info text-center">
                    <i class="fa fa-shopping-cart mr-2"></i> Belum ada produk yang ditambahkan
                </div>
            
                <!-- Hidden inputs for calculated values -->
                <input type="hidden" name="subtotal" id="subtotal-hidden">
                <input type="hidden" name="potongan" id="potongan-hidden">
                <input type="hidden" name="total" id="total-hidden">
            
                <button type="submit" class="btn btn-success mt-3" id="submit-button" disabled>Simpan & Cetak</button>
            </form>            
        </div>
    </div>
    
    {{-- <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">Selesai</a> --}}
</div>

<script>
    // Array untuk menyimpan semua produk
    let products = [];
    
    // Format angka ke format Rupiah
    function formatRupiah(angka) {
        // Pastikan angka adalah number
        angka = Number(angka);
        return angka.toLocaleString('id-ID');
    }
    
    // Tambahkan event listener untuk input barcode
    document.getElementById('barcode-input').addEventListener('keypress', function(event) {
        // Hanya jalankan jika tombol Enter ditekan
        if (event.key === 'Enter') {
            event.preventDefault(); // Mencegah form submit
            const barcodeId = this.value;
            
            if (!barcodeId.trim()) {
                return;
            }
            
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
        const invoiceFooter = document.getElementById('invoice-footer');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const submitButton = document.getElementById('submit-button');
        const potonganRow = document.getElementById('potongan-row');
        
        // Hapus baris dinamis yang sudah ada
        const dynamicRows = tableBody.querySelectorAll('tr[data-dynamic="true"]');
        dynamicRows.forEach(row => row.remove());
        
        // Cek apakah keranjang kosong
        if (products.length === 0) {
            emptyCartMessage.classList.remove('d-none');
            invoiceFooter.classList.add('d-none');
            submitButton.disabled = true;
            return;
        }
        
        // Tampilkan footer dan sembunyikan pesan keranjang kosong
        emptyCartMessage.classList.add('d-none');
        invoiceFooter.classList.remove('d-none');
        potonganRow.classList.remove('d-none');
        submitButton.disabled = false;
        
        // Hitung ulang subtotal
        let subtotalAmount = 0;
        
        // Tambahkan baris baru untuk produk yang ditambahkan
        products.forEach((product, index) => {
            const row = document.createElement('tr');
            row.setAttribute('data-dynamic', 'true');
            
            // Pastikan produk.harga dan produk.subtotal adalah angka
            const harga = Number(product.harga);
            const subtotal = Number(product.subtotal);
            
            // Hitung nomor baris (termasuk baris yang sudah ada)
            const rowNumber = index + 1; // Perbaikan nomor urut
            
            row.innerHTML = `
                <td>${rowNumber}</td>
                <td>${product.barcode}</td>
                <td>${product.produk}</td>
                <td class="text-right">Rp. ${formatRupiah(harga)}</td>
                <td class="text-center">${product.pcs}</td>
                <td class="text-right">Rp. ${formatRupiah(subtotal)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteProduct(${index})">
                        Hapus
                    </button>
                </td>
            `;
            
            tableBody.appendChild(row);
            
            // Tambahkan ke subtotal - pastikan sebagai angka
            subtotalAmount += subtotal;
        });
        
        // Update subtotal amount dengan format yang benar
        const subtotalElement = document.getElementById('subtotal-amount');
        subtotalElement.textContent = `Rp. ${formatRupiah(subtotalAmount)}`;
        
        // Hitung total setelah potongan
        const potonganInput = document.getElementById('potongan-input');
        // Pastikan potongan adalah number
        const potongan = Number(potonganInput.value) || 0;
        const totalAmount = subtotalAmount - potongan;
        
        // Update total amount dengan format yang benar
        const totalElement = document.getElementById('total-amount');
        totalElement.textContent = `Rp. ${formatRupiah(totalAmount)}`;
        
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

    const penjualanId = "{{ $penjualan->id }}";

    document.getElementById('submit-form').addEventListener('submit', function() {
        setTimeout(() => {
            window.location.href = `/penjualan/nota/${penjualanId}`;
        }, 500); 
    });

    // Fungsi untuk menginisialisasi produk dari data database
    function initializeProductsFromDatabase() {
        // Ambil semua baris produk dari database
        const tableRows = document.querySelectorAll('#products-table tbody tr:not([data-dynamic="true"])');
        
        tableRows.forEach(row => {
            // Ambil data dari setiap baris
            const barcode = row.cells[1].textContent.trim();
            const produk = row.cells[2].textContent.trim();
            const hargaText = row.cells[3].textContent.trim().replace('Rp. ', '').replace(/\./g, '');
            const pcs = parseInt(row.cells[4].textContent.trim(), 10);
            const subtotalText = row.cells[5].textContent.trim().replace('Rp. ', '').replace(/\./g, '');
            
            // Konversi harga dan subtotal ke angka
            const harga = parseInt(hargaText, 10);
            const subtotal = parseInt(subtotalText, 10);
            
            // Tambahkan ke array produk
            products.push({
                barcode: barcode,
                produk: produk,
                harga: harga,
                pcs: pcs,
                subtotal: subtotal
            });
        });
    }

    // Inisialisasi data dari database jika ada
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('#products-table tbody tr:not([data-dynamic="true"])');
        
        if (tableRows.length > 0) {
            // Inisialisasi produk dari data database
            initializeProductsFromDatabase();
            
            // Ada data dari database, sembunyikan pesan keranjang kosong
            document.getElementById('empty-cart-message').classList.add('d-none');
            document.getElementById('invoice-footer').classList.remove('d-none');
            document.getElementById('potongan-row').classList.remove('d-none');
            document.getElementById('submit-button').disabled = false;
        }
        
        // Panggil updateProductsTable untuk menginisialisasi total
        updateProductsTable();
    });
</script>
@endsection