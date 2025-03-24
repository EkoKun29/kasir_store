<!DOCTYPE html>
<html>
<head>
    <title>Detail Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .container {
            border: 2px solid #4CAF50;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Produk</h1>
        <p><strong>Nama Produk:</strong> {{ $detail->produk }}</p>
        <p><strong>Harga:</strong> Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</p>
    </div>
</body>
</html>
