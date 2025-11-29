@extends('frontend.layouts.app')

@section('content')

<h3 class="fw-bold mb-3">All Foods</h3>

<div class="row">
@foreach($foods as $food)
    <div class="col-md-3 mb-4">
        <div class="card p-2 shadow-sm border-0">
            <img src="{{ asset('uploads/foods/'.$food->image) }}" class="food-img">

            <h6 class="mt-2 fw-bold">{{ $food->name }}</h6>

            <p class="text-primary fw-bold">$ {{ number_format($food->price,2) }}</p>

            <a href="{{ route('user.food-details', $food->id) }}" class="btn btn-outline-primary w-100">
                Order Now
            </a>
        </div>
    </div>
@endforeach
</div>

{{ $foods->links() }}

@endsection
