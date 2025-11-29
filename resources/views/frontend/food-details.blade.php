@extends('frontend.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-5">
        <img src="{{ asset('uploads/foods/'.$food->image) }}" class="img-fluid rounded shadow-sm">
    </div>

    <div class="col-md-7">
        <h2 class="fw-bold">{{ $food->name }}</h2>
        <p class="text-muted">{{ $food->description }}</p>

        <h3 class="text-primary fw-bold">$ {{ number_format($food->price,2) }}</h3>

        <form action="{{ route('user.cart.add', $food->id) }}" method="POST">
            @csrf
            <input type="hidden" name="food_id" value="{{ $food->id }}">

            <!-- Quantity Input -->
            <div class="mt-3">
                <label class="fw-semibold">Quantity</label>
                <input type="number" name="quantity" min="1" value="1" class="form-control w-25" required>
            </div>

            <button class="btn btn-primary btn-lg mt-3" type="submit">
                <i class="bi bi-cart-plus"></i> Add to Cart
            </button>
        </form>
    </div>
</div>

@endsection
