<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 80mm;
            margin: 0 auto;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .content {
            border-top: 2px dashed #000;
            border-bottom: 2px dashed #000;
            padding: 10px 0;
        }
        .content table {
            width: 100%;
        }
        .content th, .content td {
            text-align: left;
            padding: 2px 0;
        }
        .total {
            text-align: right;
            margin-top: 10px;
        }
        .print-btn {
            display: none;
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>Navisya Store</h3>
        <p>Jl. KESATRIA</p>
        <p>Telp: (021) 123-4567</p>
    </div>

    <div class="content">
        <table>
            <tr><td><strong>Nomor Nota:</strong></td><td>{{ $penjualan->nomor_surat ?? '-' }}</td></tr>
            <tr><td><strong>Kios:</strong></td><td>{{ $penjualan->id_kios ?? '-' }}</td></tr>
            <tr><td><strong>Status Pembayaran:</strong></td><td>{{ $penjualan->status_penjualan ?? '-' }}</td></tr>
        </table>

        <hr>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Pcs</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                @endphp
                @foreach($penjualan->detailPenjualans as $item)
                @php
                    $harga = $item->barcode->harga_jual ?? 0;
                    $pcs = $item->pcs;
                    $sub = $harga * $pcs;
                    $subtotal += $sub;
                @endphp
                <tr>
                    <td>{{ $item->barcode->produk ?? '-' }}</td>
                    <td>{{ number_format($harga, 0, ',', '.') }}</td>
                    <td>{{ $pcs }}</td>
                    <td>{{ number_format($sub, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p><strong>Subtotal:</strong> Rp. {{ number_format($subtotal, 0, ',', '.') }}</p>
            <p><strong>Potongan:</strong> Rp. {{ number_format($penjualan->potongan ?? 0, 0, ',', '.') }}</p>
            <p><strong>Total Bayar:</strong> Rp. {{ number_format($subtotal - ($penjualan->potongan ?? 0), 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="footer">
        <p>Terima Kasih Telah Berbelanja!</p>
    </div>

    <button class="print-btn" onclick="window.print()">Cetak Ulang</button>

    <script>
        window.onload = function () {
            window.print();
            window.onafterprint = function () {
                window.location.href = "{{ route('penjualan.index') }}";
            };
        };
    </script>

</body>
</html>
