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

    <!-- LEFT SIDEBAR CATEGORY -->
    <div class="col-md-3 mb-4">
        <div class="sticky-sidebar p-3 shadow-sm rounded bg-white">

            <h5 class="fw-bold mb-3">
                <i class="bi bi-list-ul text-primary"></i> Categories
            </h5>

            <ul class="list-group">
                <a href="{{ route('user.home') }}" 
                   class="list-group-item {{ request('category') ? '' : 'active' }}">
                    All Categories
                </a>

                @foreach($categories as $cat)
                <a href="{{ route('user.home', ['category' => $cat->id]) }}"
                   class="list-group-item {{ request('category') == $cat->id ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
                @endforeach
            </ul>

        </div>
    </div>

    <!-- RIGHT SIDE FOODS -->
    <div class="col-md-9">

        <!-- SEARCH BAR -->
        <form method="GET" action="{{ route('user.home') }}">
            <div class="input-group mb-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search foods..."
                       value="{{ request('search') }}">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button class="btn btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <h3 class="mb-4 fw-bold">
            <i class="bi bi-fire text-danger"></i> Foods
        </h3>

        <div class="row">
            @forelse($foods as $food)
            <div class="col-md-4 col-sm-6 mb-4">
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

                        <p class="text-muted small mb-1">
                            {{ $food->category->name ?? 'No Category' }}
                        </p>

                        <p class="text-primary fw-bold mb-2">
                            $ {{ number_format($food->price, 2) }}
                        </p>

                        <a href="{{ route('user.food-details', $food->slug) }}"
                           class="btn btn-primary w-100 rounded-pill">
                            View Details
                        </a>
                    </div>

                </div>
            </div>
            @empty
                <p class="text-muted">Oop! No foods found!</p>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center">
            {{ $foods->links() }}
        </div>

    </div>
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
