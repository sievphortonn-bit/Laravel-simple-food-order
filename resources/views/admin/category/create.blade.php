@extends('admin.layouts.app')

@section('content')

<style>
    .form-label {
        font-weight: 600;
    }

    .custom-card {
        border-radius: 12px;
        border: none;
        overflow: hidden;
    }

    .custom-card .card-header {
        background: linear-gradient(90deg, #0d6efd, #4dabf7);
        color: white;
        font-weight: 600;
        padding: 18px;
        font-size: 18px;
    }

    .custom-input {
        border-radius: 10px;
        padding: 12px;
    }

    .btn-primary {
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: #0b5ed7;
    }
</style>

<div class="col-md-6">

    <div class="card shadow custom-card">

        <div class="card-header d-flex align-items-center">
            <i class="bi bi-tags me-2"></i> Add New Category
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Some fields are invalid.
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Category Name</label>
                    <input type="text" class="form-control custom-input" 
                           name="name" placeholder="Enter category name..."
                           onkeyup="generateSlug(this.value)">
                </div>

                <div class="mb-4">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control custom-input" 
                           name="slug" id="slug" placeholder="auto-generated slug">
                </div>

                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea class="form-control custom-input" 
                              name="description" rows="4"
                              placeholder="Enter description (optional)..."></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Save Category
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
function generateSlug(text) {
    document.getElementById('slug').value =
        text.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
}
</script>

@endsection
