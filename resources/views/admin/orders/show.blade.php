@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <a href="{{ route('admin.orders.index') }}" class="btn btn-light mb-3">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    <div class="row g-4">
        {{-- Customer Info --}}
        <div class="col-lg-4">
            <div class="card shadow-sm p-3">
                <h5 class="mb-3">Customer Info</h5>
                <p><strong>Name:</strong> {{ $order->user->name ?? $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? $order->customer_email }}</p>
                <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Shipping:</strong> {{ $order->shipping_address }}</p>

                {{-- Update Status --}}
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mt-3">
                    @csrf
                    <label class="form-label"><strong>Status:</strong></label>
                    <select name="status" class="form-select mb-2">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>

                {{-- Download Invoice --}}
                <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-download"></i> Download Invoice
                </a>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="col-lg-8">
            <div class="card shadow-sm p-3">
                <h5 class="mb-3">Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
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
                                <td>$ {{ number_format($item->price, 2) }}</td>
                                <td>$ {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div class="d-flex justify-content-end flex-column align-items-end mt-3">
                    <p class="mb-1"><strong>Subtotal:</strong> ${{ number_format($order->subtotal, 2) }}</p>
                    <p class="mb-1"><strong>Tax:</strong> ${{ number_format($order->tax, 2) }}</p>
                    <p class="mb-1"><strong>Shipping:</strong> ${{ number_format($order->shipping_fee, 2) }}</p>
                    <h5 class="mt-2">Total: ${{ number_format($order->total, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
