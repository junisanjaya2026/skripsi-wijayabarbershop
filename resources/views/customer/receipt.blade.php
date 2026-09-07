<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resi {{ $order->order_code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #222; }
        h2 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 6px 4px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        .text-right { text-align: right; }
        .totals td { border: none; }
        .totals .label { text-align: right; font-weight: bold; }
        .header-info p { margin: 2px 0; }
        hr { border: none; border-top: 1px solid #ccc; }
    </style>
</head>
<body>

    <h2>Resi Pembayaran</h2>
    <p>Order: {{ $order->order_code }}</p>

    <hr>

    <div class="header-info">
        <p>Status: {{ strtoupper($order->status) }}</p>
        <p>Metode Pembayaran: {{ strtoupper($order->payment_method) }}</p>
        <p>Nomor Antrian: A-{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</p>
        <p>Estimasi: {{ \Carbon\Carbon::parse($order->queue_time)->translatedFormat('d F Y H:i') }}</p>
        <p>Tanggal Order: {{ $order->created_at->translatedFormat('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $detail)
                <tr>
                    <td>{{ $detail->item->item_name ?? '-' }}</td>
                    <td class="text-right">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tbody class="totals">
            <tr>
                <td colspan="3" class="label">Subtotal</td>
                <td class="text-right">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="label">Pajak</td>
                <td class="text-right">Rp{{ number_format($order->tax, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="label">Grand Total</td>
                <td class="text-right"><strong>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <hr>

    <p>Terima kasih atas pesanan Anda.</p>

</body>
</html>