<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-details div {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals div {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Nota Penjualan</h1>
        <div>
            <strong>Tanggal:</strong> {{ date('d/m/Y') }}
        </div>
    </div>

    <div class="invoice-details">
        <div>
            <strong>Nomor Surat:</strong> {{ $penjualan->nomor_surat ?? '-' }}<br>
            <strong>ID Kios:</strong> {{ $penjualan->id_kios ?? '-' }}<br>
            <strong>Status Penjualan:</strong> {{ $penjualan->status_penjualan ?? '-' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Barcode</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detailPenjualans as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->produk }}</td>
                <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ $item->pcs }}</td>
                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div>
            <strong>Subtotal:</strong> Rp. {{ number_format($detailPenjualans->sum('subtotal'), 0, ',', '.') }}
        </div>
        <div>
            <strong>Potongan:</strong> Rp. {{ number_format($penjualan->potongan ?? 0, 0, ',', '.') }}
        </div>
        <div>
            <strong>Total:</strong> Rp. {{ number_format(($detailPenjualans->sum('subtotal') - ($penjualan->potongan ?? 0)), 0, ',', '.') }}
        </div>
    </div>
</body>
</html>