<!DOCTYPE html>
<html>
<head>
    <title>Barcode Produk</title>
    <style>
        @page {
            size: 60mm 40mm;
            margin: 5mm;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 5px;
        }
        .barcode-container {
            margin: 5px 0;
            padding: 5px;
            border: 1px dashed #ccc;
        }
        .barcode-image {
            max-width: 100%;
            height: 40px; 
        }
        .barcode-text {
            margin-top: 5px;
            font-family: monospace;
            font-size: 10px;
        }
        .footer {
            margin-top: 10px;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="container">
        <div class="barcode-container">
           
            <div id="qrcode"></div>
            <p class="barcode-text"><strong>Produk:</strong> {{ $detail->produk }}</p>
        </div>        
        
        <div class="footer">
            <p>Dicetak pada: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js" integrity="sha512-NFUcDlm4V+a2sjPX7gREIXgCSFja9cHtKPOL1zj6QhnE0vcY695MODehqkaGYTLyL2wxe/wtr4Z49SvqXq12UQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    jQuery('#qrcode').qrcode({
        width: 100,
        height: 100,
        text: "{{ $detail->id_barcode }}"
    });
</script>
