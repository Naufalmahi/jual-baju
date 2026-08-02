<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcodes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 10mm; }
        .barcodes-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 3mm;
            justify-content: flex-start;
        }
        .barcode-label {
            width: 56mm;
            height: 25mm;
            padding: 2mm;
            text-align: center;
            border: 1px dashed #ccc;
            page-break-inside: avoid;
        }
        .barcode-label img {
            max-width: 100%;
            height: 12mm;
            object-fit: contain;
        }
        .barcode-label .code {
            font-size: 8pt;
            font-family: monospace;
            margin-top: 1mm;
        }
        .barcode-label .name {
            font-size: 7pt;
            margin-top: 1mm;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .barcode-label .price {
            font-size: 9pt;
            font-weight: bold;
            margin-top: 1mm;
            color: #000;
        }
        @media print {
            body { padding: 0; }
            .barcode-label { border: none; }
            @page { size: A4; margin: 10mm; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="barcodes-grid">
        @foreach($products as $product)
        @php
            $qty = request('qty') ?? 1;
            for($i = 0; $i < $qty; $i++):
        @endphp
        <div class="barcode-label">
            <img src="{{ asset('storage/' . $product->barcode_image) }}" alt="{{ $product->barcode }}">
            <div class="code">{{ $product->barcode }}</div>
            <div class="name">{{ $product->name }}</div>
            <div class="price">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
        </div>
        @php
            endfor;
        @endphp
        @endforeach
    </div>
</body>
</html>
