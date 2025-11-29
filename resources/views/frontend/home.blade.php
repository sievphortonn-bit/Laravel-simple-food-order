@extends('frontend.layouts.app')

@section('content')

<!--  HERO CAROUSEL -->
<div id="heroCarousel" class="carousel slide mb-5 shadow" data-bs-ride="carousel">
    <div class="carousel-inner">

        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092"
                 class="d-block w-100 hero-img">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="fw-bold text-shadow">Delicious Foods Delivered To You</h2>
                <p class="text-shadow">Fresh, tasty, and fast delivery.</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1546069901-eacef0df6022"
                 class="d-block w-100 hero-img">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="fw-bold text-shadow">Taste The Best Dishes</h2>
                <p class="text-shadow">Order now and enjoy great meals.</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1551218808-94e220e084d2"
                 class="d-block w-100 hero-img">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="fw-bold text-shadow">Fresh Ingredients</h2>
                <p class="text-shadow">Quality food made with love.</p>
            </div>
        </div>

    </div>

    <!-- Carousel controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<hr>
<!--  Popular Foods -->
<h3 class="mb-4 fw-bold"><i class="bi bi-fire text-danger"></i> Popular Foods</h3>

<div class="row">
@foreach($foods as $food)
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="food-card card border-0 shadow-sm h-100">

            <div class="position-relative">
                <img src="{{ $food->image ? asset('uploads/foods/'.$food->image) : 'https://via.placeholder.com/400' }}"
                     class="card-img-top food-img">

                @if($food->is_active)
                <span class="badge bg-success position-absolute top-0 end-0 m-2">Available</span>
                @else
                <span class="badge bg-secondary position-absolute top-0 end-0 m-2">Unavailable</span>
                @endif
            </div>

            <div class="card-body text-center">
                <h5 class="fw-bold">{{ $food->name }}</h5>
                <p class="text-primary fw-bold mb-2">$ {{ number_format($food->price,2) }}</p>

                <a href="{{ route('user.food-details', $food->slug) }}"
                   class="btn btn-primary w-100 rounded-pill">
                    View Details
                </a>
            </div>

        </div>
    </div>
@endforeach
</div>

@endsection


<!-- CUSTOM STYLE -->
<style>
.hero-img {
    height: 420px;
    object-fit: cover;
    filter: brightness(70%);
}

.text-shadow {
    text-shadow: 0 3px 8px rgba(0,0,0,0.8);
}

.food-img {
    height: 180px;
    width: 100%;
    object-fit: cover;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.food-card {
    border-radius: 12px;
    transition: transform .3s, box-shadow .3s;
}

.food-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}
</style>
