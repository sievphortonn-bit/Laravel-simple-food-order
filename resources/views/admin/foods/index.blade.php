@extends('admin.layouts.app')

@section('content')
<style>
/* CARD WRAPPER */
.food-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.35s ease;
    position: relative;
    border: 1px solid #f1f1f1;
}

.food-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12);
}

/* IMAGE */
.food-image-wrapper {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.food-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.food-card:hover .food-image {
    transform: scale(1.08);
}

/* STATUS BADGE */
.food-status {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    color: #fff;
    font-weight: 600;
}

.food-status.active {
    background: #28a745;
}

.food-status.inactive {
    background: #6c757d;
}

/* BODY */
.food-body {
    padding: 15px;
}

.food-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 6px;
    color: #333;
}

.food-category {
    color: #999;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.food-price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #0d6efd;
}

/* FOOTER */
.food-footer {
    padding: 12px 15px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    border-top: 1px solid #f1f1f1;
}

/* BUTTONS */
.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
}

.edit-btn {
    background: #e7f1ff;
    color: #0d6efd;
}

.edit-btn:hover {
    background: #0d6efd;
    color: #fff;
}

.delete-btn {
    background: #ffe7e7;
    color: #dc3545;
}

.delete-btn:hover {
    background: #dc3545;
    color: #fff;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-egg-fried"></i> Foods</h3>
    <a href="{{ route('admin.foods.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle"></i> Add Food
    </a>
</div>
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        showConfirmButton: true
    });
</script>
@endif

<!-- SEARCH + FILTER -->
<div class="card shadow-sm mb-4">
    <div class="card-body">

        <form action="" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search food name...">
            </div>

            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Filter by Category</option>
                    @foreach(\App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex">
                <button class="btn btn-dark w-50 me-2"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.foods.index') }}" class="btn btn-light w-50">
                    Reset
                </a>
            </div>
        </form>

    </div>
</div>

<!-- FOOD GRID -->
<div class="row">

@foreach($foods as $food)
    <div class="col-md-4 col-lg-3 mb-4">

        <div class="food-card shadow-sm border-0">

            <!-- Image -->
            <div class="food-image-wrapper">
                <img src="{{ $food->image ? asset('uploads/foods/'.$food->image) : 'https://via.placeholder.com/300' }}"
                     class="food-image">

                <span class="food-status {{ $food->is_active ? 'active' : 'inactive' }}">
                    {{ $food->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <!-- Content -->
            <div class="food-body">
                <h5 class="food-title">{{ $food->name }}</h5>

                <p class="food-category">
                    <i class="bi bi-tag-fill me-1 text-warning"></i>
                    {{ $food->category->name }}
                </p>

                <div class="food-price">
                    $ {{ number_format($food->price, 2) }}
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="food-footer">

                <a href="{{ route('admin.foods.edit', $food->id) }}"
                   class="action-btn edit-btn">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST"
                      onsubmit="return confirm('Delete this food?')" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="action-btn delete-btn">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>

            </div>

        </div>

    </div>
@endforeach

</div>


<!-- PAGINATION -->
<div class="mt-3">
    {{ $foods->links('pagination::bootstrap-5') }}
</div>


<style>
.food-card {
    transition: 0.3s;
    border-radius: 12px;
}
.food-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.food-img {
    height: 180px;
    object-fit: cover;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
