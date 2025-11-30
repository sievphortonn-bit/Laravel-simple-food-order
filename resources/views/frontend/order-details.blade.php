@extends('frontend.layouts.app')

@section('content')
<div class="container py-4">
    <a href="{{ route('user.orders') }}" class="btn btn-light mb-3">← Back to orders</a>

    <div class="card p-3">
        <h4>Order #{{ $order->id }}</h4>
        <p>Date: {{ $order->created_at->format('d M Y H:i') }}</p>

        <h5>Items</h5>
        <table class="table">
            <thead><tr><th>Food</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
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

        <div class="text-end">
            <p>Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
            <p>Tax: ${{ number_format($order->tax, 2) }}</p>
            <p>Shipping: ${{ number_format($order->shipping_fee, 2) }}</p>
            <h4>Total: ${{ number_format($order->total, 2) }}</h4>

            <a href="{{ route('user.invoice.download', $order->id) }}" class="btn btn-secondary">Download Invoice (PDF)</a>
        </div>
    </div>
</div>
@endsection
