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
                display: flex;
                align-items: center;
                justify-content: center;
                box-sizing: border-box;
                overflow: hidden;
            }

            .container {
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            #qrcode {
                width: 120%;
                height: 120%;
                margin-top: 50px;
                justify-content: center;
                
            }

            .barcode-text {
                margin-top: 5px;
                font-size: 25px;
            }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="container">
        <div id="qrcode"></div>
        <p class="barcode-text"><strong>Produk:</strong> {{ $barcode->produk }}</p>
        {{-- <p class="barcode-text"><strong>Harga Jual:</strong> {{ $barcode->harga_jual }}</p> --}}
    </div>
</body>
</html>

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
