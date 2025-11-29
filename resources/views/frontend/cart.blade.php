@extends('frontend.layouts.app')

@section('content')

<h3 class="fw-bold mb-3">Your Cart</h3>

@if($cart->isEmpty())
    <p>Your cart is empty.</p>
@else

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Food</th>
            <th>Price</th>
            <th width="160">Qty</th>
            <th>Total</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @php $grandTotal = 0; @endphp

        @foreach($cart as $item)
        @php
            $total = $item->food->price * $item->quantity;
            $grandTotal += $total;
        @endphp

        <tr>
            <td>{{ $item->food->name }}</td>

            <td>$ {{ number_format($item->food->price, 2) }}</td>

            <td>

                <!-- Quantity Update Form -->
                <form action="{{ route('user.cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                    @csrf

                    <button type="submit" name="action" value="decrease" class="btn btn-sm btn-secondary">-</button>

                    <input type="text" name="quantity" value="{{ $item->quantity }}" 
                           class="form-control text-center mx-1" 
                           style="width: 50px;" readonly>

                    <button type="submit" name="action" value="increase" class="btn btn-sm btn-secondary">+</button>
                </form>

            </td>

            <td>$ {{ number_format($total, 2) }}</td>

            <td>
                <a href="{{ route('user.cart.remove', $item->id) }}"
                   class="btn btn-danger btn-sm">
                   Remove
                </a>
            </td>
        </tr>
        @endforeach

        <tr>
            <th colspan="3" class="text-end">Grand Total:</th>
            <th>$ {{ number_format($grandTotal, 2) }}</th>
            <th></th>
        </tr>
    </tbody>
</table>

<a href="{{ route('user.checkout') }}" class="btn btn-success btn-lg">
    Proceed to Checkout
</a>

@endif

@endsection
