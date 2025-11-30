@extends('frontend.layouts.app')

@section('content')
    <div class="container">
        <h2 class="fw-bold mb-4 text-center">My Orders</h2>
        <hr>

        <style>
            .card {
                border-radius: 14px;
                /* border: 2px solid black; */
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .card:hover {
                transform: translateY(-5px);
                /* box-shadow: 0 12px 24px rgba(0,0,0,0.15); */
            }

            .badge {
                font-size: 0.8rem;
                padding: 0.5em 0.7em;
            }

            .btn-outline-primary {
                transition: all 0.2s;
            }

            .btn-outline-primary:hover {
                background-color: #0d6efd;
                color: #fff;
            }
        </style>

        @if($orders->isEmpty())
            <div class="alert alert-info text-center">You have no orders yet.</div>
        @else
            <div class="row g-4">
                @foreach($orders as $order)
                    <div class="col-lg-6 col-md-12">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Order #{{ $order->id }}</h5>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    <span
                                        class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }} text-uppercase">
                                        {{ $order->status }}
                                    </span>
                                </div>

                                <p class="mb-2"><i class="bi bi-currency-dollar"></i> <strong>Total:</strong>
                                    ${{ number_format($order->total, 2) }}</p>

                                <div class="mt-3 text-end">
                                    <a href="{{ route('user.order.view', $order->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="bi bi-eye"></i> View Details
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