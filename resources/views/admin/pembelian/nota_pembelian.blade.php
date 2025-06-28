<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pembelian</title>
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
            border-collapse: collapse;
        }
        .content th, .content td {
            text-align: left;
            padding: 2px 0;
            font-size: 12px;
        }
        .total {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }
        .print-btn {
            display: none;
        }
        thead th {
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }

        @media print {
            body {
                width: 80mm;
                margin: 0 auto;
            }

            .print-btn {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>MadinQu Store</h3>
        {{-- <p>Jl. KESATRIA</p>
        <p>Telp: (021) 123-4567</p> --}}
        <p><strong>Nota Pembelian</strong></p>
    </div>

    <div class="content">
        <table>
            <tr><td><strong>Nomor Surat:</strong></td><td>{{ $pembelian->nomor_surat ?? '-' }}</td></tr>
            <tr><td><strong>Supplier:</strong></td><td>{{ $pembelian->supplier ?? '-' }}</td></tr>
            <tr><td><strong>Status Pembayaran:</strong></td><td>{{ $pembelian->status ?? '-' }}</td></tr>
            <tr><td><strong>Tanggal:</strong></td><td>{{ \Carbon\Carbon::parse($pembelian->tanggal_beli)->format('d-m-Y') }}</td></tr>
        </table>

        <hr>

        <table>
            <thead style="border-bottom: 1px solid #000;">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($pembelian->detailPembelian as $item)
                    @php
                        $harga = $item->harga ?? 0;
                        $jumlah = $item->qty ?? 0;
                        $sub = $harga * $jumlah;
                        $total += $sub;
                    @endphp
                    <tr>
                        <td>{{ $item->barcode->produk ?? '-' }}</td>
                        <td>{{ number_format($harga, 0, ',', '.') }}</td>
                        <td>{{ $jumlah }}</td>
                        <td>{{ number_format($sub, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p>Total Beli: Rp. {{ number_format($total, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="footer">
        <p>Terima Kasih!</p>
    </div>

    <button class="print-btn" onclick="window.print()">Cetak Ulang</button>

    <script>
        window.onload = function () {
            window.print();

            window.onafterprint = function () {
                
                setTimeout(function () {
                    window.location.href = "{{ route('pembelian.index') }}";
                }, 10000);
            };
        };
    </script>

</body>
</html>
