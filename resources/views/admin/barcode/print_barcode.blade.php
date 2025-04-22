<!DOCTYPE html>
<html>
<head>
    <title>Barcode Produk</title>
    <style>
        @media print {
            @page {
                size: 65mm 40mm;
                margin: 0;
            }
            
            body, html {
                width: 65mm;
                height: 40mm;
                margin: 4mm 4mm 4mm 6mm;
                padding: 0;
                background: white;
                font-family: Arial, sans-serif;
            }
            
            * {
                box-sizing: border-box;
            }
            
            .container {
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-start;
                padding: 4mm 4mm 4mm 8mm;
                gap: 5px;
            }
            
            #qrcode canvas {
                width: 100px !important;
                height: 100px !important;
            }
            
            .barcode-info {
                display: flex;
                flex-direction: column;
                justify-content: center;
                max-width: calc(100% - 85px);
            }
            
            .barcode-text {
                font-size: 12px;
                font-weight: bold;
                text-align: left;
                margin-bottom: 5px;
                margin-right: 1px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
        
        body, html {
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .container {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            padding: 3mm;
            gap: 5px;
        }
        
        .barcode-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: calc(100% - 85px);
        }
        
        .barcode-text {
            font-size: 18px;
            font-weight: bold;
            text-align: left;
            margin-bottom: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="container">
        <div id="qrcode"></div>
        <div class="barcode-info">
            <div class="barcode-text">{{ $barcode->id }}</div>
            <div class="barcode-text"><strong> {{ $barcode->produk }} </strong> </div>
            <div class="barcode-text">
                {{ $barcode->harga_jual ? 'Rp. ' . number_format($barcode->harga_jual, 0, ',', '.') : '' }}
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
        crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"
        integrity="sha512-NFUcDlm4V+a2sjPX7gREIXgCSFja9cHtKPOL1zj6QhnE0vcY695MODehqkaGYTLyL2wxe/wtr4Z49SvqXq12UQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
        jQuery('#qrcode').qrcode({
            width: 80,
            height: 80,
            text: "{{ $barcode->id }}"
        });
    </script>
</body>
</html>