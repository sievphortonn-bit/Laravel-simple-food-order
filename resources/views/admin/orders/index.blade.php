@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-4">All Orders</h3>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 d-flex gap-2">
        <select name="status" class="form-select w-auto">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    @if($orders->isEmpty())
        <div class="alert alert-info">No orders found.</div>
    @else
        <div class="row g-4">
            @foreach($orders as $order)
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">Order #{{ $order->id }}</h5>
                                <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <p class="mb-2"><strong>User:</strong> {{ $order->user->name ?? $order->customer_name }}</p>
                        <p class="mb-2"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                        <p class="mb-2"><strong>Payment:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>

                        <div class="mt-auto d-flex justify-content-between">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info rounded-pill">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-sm btn-secondary rounded-pill">
                                <i class="bi bi-download"></i> Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
