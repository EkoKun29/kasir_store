<!DOCTYPE html>
<html>
<head>
    <title>Barcode Produk</title>
    <style>
        @media print {
            @page {
                size: 60mm 40mm;
                margin: 0;
            }

            body, html {
                width: 60mm;
                height: 40mm;
                margin: 0;
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
                padding: 5mm;
                gap: 10px;
            }

            #qrcode canvas {
                width: 80px !important;
                height: 80px !important;
            }

            .barcode-text {
                font-size: 16px;
                font-weight: bold;
                text-align: left;
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
            padding: 5mm;
            gap: 10px;
        }

        .barcode-text {
            font-size: 16px;
            font-weight: bold;
            text-align: left;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="container">
        <div id="qrcode"></div>
        <div class="barcode-text">{{ $barcode->produk }}</div>
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
