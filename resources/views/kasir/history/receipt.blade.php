<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $order->order_code }}</title>
    <style>
        body { font-family: monospace; width: 80mm; margin: 0 auto; padding: 10px; font-size: 12px; }
        .text-center { text-align: center; }
        .flex { display: flex; justify-content: space-between; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
    </style>
</head>

<body onload="window.print()">
    <div class="text-center">
        <h2 style="margin:0;">KOPERASI SEKOLAH</h2>
        <p style="margin:2px 0;">Seragam Berkualitas</p>
    </div>
    <div class="line"></div>
    <div>No: #{{ $order->order_code }}</div>
    <div>Siswa: {{ $order->user->name ?? 'Siswa' }}</div>
    <div>Tgl: {{ $order->updated_at->format('d/m/Y H:i') }}</div>
    <div class="line"></div>

    @foreach($order->items as $item)
        <div>{{ $order->product->name ?? 'Seragam' }} ({{ $item->size }})</div>
        <div class="flex">
            <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
        </div>
    @endforeach

    <div class="line"></div>
    <div class="flex" style="font-weight: bold;">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>Metode</span>
        <span>{{ strtoupper($order->payment_method) }}</span>
    </div>
    <div class="line"></div>
    <div class="text-center">
        <p>Terima Kasih</p>
        <p>Seragam Kualitas, Prestasi Bangsa</p>
    </div>
</body>
</html>