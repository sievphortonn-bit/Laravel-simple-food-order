@extends('frontend.layouts.app')

@section('content')

<style>
    .cart-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    /* .qty-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        font-weight: bold;
    }
    .qty-display {
        width: 55px;
        border-radius: 10px;
    } */
    .remove-btn {
        border-radius: 50px;
    }
</style>

<h3 class="fw-bold mb-4">Your Cart</h3>

@if($cart->isEmpty())
    <div class="alert alert-info p-4 text-center fs-5">
        Your cart is empty.
    </div>
@else

<div class="card cart-card mb-4">
    <div class="card-body p-0">
        <table class="table table-borderless align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="py-3 ps-4">Food</th>
                    <th>Price</th>
                    <th width="200">Quantity</th>
                    <th>Total</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @php $grandTotal = 0; @endphp

                @foreach($cart as $item)
                    @php
                        $total = $item->food->price * $item->quantity;
                        $grandTotal += $total;
                    @endphp

                    <tr class="border-bottom">
                        <td class="ps-4">
                            <strong>{{ $item->food->name }}</strong>
                        </td>

                        <td class="fw-semibold">
                            $ {{ number_format($item->food->price, 2) }}
                        </td>

                        <td>
                            <form action="{{ route('user.cart.update', $item->id) }}" method="POST" class="d-flex align-items-center justify-content-center">
                                @csrf

                                <button type="submit" name="action" value="decrease"
                                    class="btn btn-outline-secondary qty-btn">−</button>

                                <input type="text" value="{{ $item->quantity }}"
                                       class="form-control text-center mx-2 qty-display" readonly>

                                <button type="submit" name="action" value="increase"
                                    class="btn btn-outline-secondary qty-btn">+</button>
                            </form>
                        </td>

                        <td class="fw-semibold">
                            $ {{ number_format($total, 2) }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('user.cart.remove', $item->id) }}"
                               class="btn btn-danger btn-sm px-3 remove-btn">
                                Remove
                            </a>
                        </td>
                    </tr>
                @endforeach

                <tr class="bg-light">
                    <th colspan="3" class="text-end py-3 fs-5 pe-3">Grand Total:</th>
                    <th class="fs-5">$ {{ number_format($grandTotal, 2) }}</th>
                    <th></th>
                </tr>
            </tbody>

        </table>
    </div>
</div>

<div class="text-end">
    <a href="{{ route('user.checkout') }}" class="btn btn-success btn-lg px-5 py-2 rounded-pill shadow-sm">
        Proceed to Checkout →
    </a>
</div>

@endif

@endsection
