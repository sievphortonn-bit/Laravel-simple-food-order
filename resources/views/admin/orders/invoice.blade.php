<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        .text-right { text-align: right; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Invoice #{{ $order->id }}</h2>
    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
    <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Food</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->foods->name ?? 'Item' }}</td>
                <td>{{ $item->qty }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>${{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-right">Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
    <p class="text-right">Tax: ${{ number_format($order->tax, 2) }}</p>
    <p class="text-right">Shipping: ${{ number_format($order->shipping_fee, 2) }}</p>
    <h3 class="text-right">Total: ${{ number_format($order->total, 2) }}</h3>
</body>
</html>
