@extends('frontend.layouts.app')

@section('content')

<div class="container py-4">

    <div class="row g-4">

        <!-- IMAGE LEFT -->
        <div class="col-lg-5">
            <div class="food-image-container shadow-sm rounded overflow-hidden border">
                <img src="{{ asset('uploads/foods/'.$food->image) }}"
                     class="img-fluid food-detail-img">
            </div>
        </div>

        <!-- DETAILS RIGHT -->
        <div class="col-lg-7">

            <div class="p-4 rounded shadow-sm bg-white">

                <!-- Back button -->
                <a href="{{ route('user.home') }}" class="btn btn-light btn-sm mb-3 rounded-pill">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

                <!-- Title -->
                <h1 class="fw-bold mb-2">{{ $food->name }}</h1>

                <!-- Availability -->
                @if($food->is_active)
                    <span class="badge bg-success mb-3 px-3 py-2">Available</span>
                @else
                    <span class="badge bg-secondary mb-3 px-3 py-2">Unavailable</span>
                @endif

                <!-- Category -->
                <p class="text-muted mb-2">
                    <i class="bi bi-tag"></i> 
                    <strong>{{ $food->category->name ?? 'No Category' }}</strong>
                </p>

                <!-- Description -->
                <p class="text-secondary lh-lg">
                    {{ $food->description }}
                </p>

                <!-- Price box -->
                <div class="price-box p-3 rounded mb-3">
                    <h3 class="fw-bold m-0 text-primary">
                        ${{ number_format($food->price, 2) }}
                    </h3>
                </div>

                <!-- Add to Cart -->
                <form action="{{ route('user.cart.add') }}" method="POST">
                    @csrf

                    <input type="hidden" name="food_id" value="{{ $food->id }}">

                    <label class="fw-semibold">Quantity</label>
                    <input type="number" name="quantity" min="1" value="1"
                        class="form-control w-25 mb-3" required>

                    <button type="submit" 
                            class="btn btn-primary btn-lg w-100 rounded-pill"
                            {{ $food->is_active ? '' : 'disabled' }}>
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

@endsection

<!-- PAGE STYLE -->
<style>
.food-detail-img {
    height: 430px;
    width: 100%;
    object-fit: cover;
    border-radius: 12px;
}

.price-box {
    background: #f0f7ff;
    border-left: 5px solid #0d6efd;
}

.food-image-container {
    border-radius: 14px;
    overflow: hidden;
}

@media (max-width: 768px) {
    .food-detail-img {
        height: 320px;
    }
}
</style>
