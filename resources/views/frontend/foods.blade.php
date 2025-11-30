@extends('frontend.layouts.app')

@section('content')

<div class="row">

    <!-- LEFT: CATEGORY SIDEBAR -->
    <div class="col-md-3 mb-4">
        <div class="p-3 bg-white rounded shadow-sm sticky-sidebar">

            <h5 class="fw-bold mb-3">
                <i class="bi bi-list-ul text-primary"></i> Categories
            </h5>

            <ul class="list-group">
                <a href="{{ route('user.foods') }}" 
                   class="list-group-item {{ request('category') ? '' : 'active' }}">
                    All Foods
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('user.foods', ['category' => $cat->id]) }}"
                       class="list-group-item {{ request('category') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </ul>

        </div>
    </div>

    <!-- RIGHT: FOOD LIST -->
    <div class="col-md-9">

        <!-- SEARCH BAR -->
        <form action="{{ route('user.foods') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Search foods..."
                       value="{{ request('search') }}">

                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <button class="btn btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <h3 class="fw-bold mb-3">All Foods</h3>

        <div class="row">
        @forelse($foods as $food)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <img src="{{ asset('uploads/foods/'.$food->image) }}" class="food-img">

                    <div class="p-3 text-center">
                        <h6 class="fw-bold">{{ $food->name }}</h6>

                        <p class="text-muted small mb-1">
                            {{ $food->category->name ?? 'No Category' }}
                        </p>

                        <p class="text-primary fw-bold">$ {{ number_format($food->price, 2) }}</p>

                        <a href="{{ route('user.food-details', $food->slug) }}"
                           class="btn btn-primary w-100 rounded-pill">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No foods found.</p>
        @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-3">
            {{ $foods->links() }}
        </div>

    </div>

</div>

@endsection

<!-- STYLES -->
<style>
.sticky-sidebar {
    position: sticky;
    top: 100px;
}
.food-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
}
</style>
