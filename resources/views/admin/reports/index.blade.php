@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-4">Order Reports</h3>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.reports') }}" class="mb-4 d-flex flex-wrap gap-2 align-items-center">
        <select name="status" class="form-select w-auto">
            <option value="all">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <select name="date_filter" class="form-select w-auto">
            <option value="all">All Dates</option>
            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
            <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
            <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
            <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.reports.export', request()->query()) }}" class="btn btn-secondary">Download PDF</a>
    </form>

    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>Total Orders</h5>
                <h3 class="text-primary">{{ $totalOrders }}</h3>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>Total Revenue</h5>
                <h3 class="text-success">$ {{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card shadow-sm p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>$ {{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($order->payment_method ?? 'N/A') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
