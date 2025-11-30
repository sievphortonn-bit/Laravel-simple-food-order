<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding: 8px; border-bottom: 1px solid #eee; text-align: left; }
    th { background: #f8f8f8; }
</style>
</head>
<body>
<div class="invoice-box">
    <h2>Invoice #{{ $order->id }}</h2>
    <p>Date: {{ $order->created_at->format('d M Y H:i') }}</p>

    <h4>Customer</h4>
    <p>
        {{ $order->customer_name }}<br>
        {{ $order->customer_email }}<br>
        {{ $order->customer_phone }}<br>
        {{ $order->shipping_address }}
    </p>

    <h4>Items</h4>
    <table>
        <thead>
            <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->foods->name ?? 'Item' }}</td>
                <td>{{ $item->qty }}</td>
                <td>$ {{ number_format($item->price, 2) }}</td>
                <td>$ {{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px; text-align:right;">
        <p>Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
        <p>Tax: ${{ number_format($order->tax, 2) }}</p>
        <p>Shipping: ${{ number_format($order->shipping_fee, 2) }}</p>
        <h3>Total: ${{ number_format($order->total, 2) }}</h3>
    </div>
</div>
</body>
</html>
