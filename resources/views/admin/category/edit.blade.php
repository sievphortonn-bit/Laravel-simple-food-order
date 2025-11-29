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
        background: linear-gradient(90deg, #20c997, #0ca678);
        color: white;
        font-weight: 600;
        padding: 18px;
        font-size: 18px;
    }

    .custom-input {
        border-radius: 10px;
        padding: 12px;
    }

    .btn-success {
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-secondary {
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
    }
</style>

<div class="col-md-6">

    <div class="card shadow custom-card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-pencil-square me-2"></i> Edit Category</span>

            <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There are some errors!</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">Category Name</label>
                    <input type="text" value="{{ $category->name }}" 
                           class="form-control custom-input"
                           name="name"
                           onkeyup="generateSlug(this.value)">
                </div>

                <div class="mb-4">
                    <label class="form-label">Slug</label>
                    <input type="text" value="{{ $category->slug }}" 
                           class="form-control custom-input" name="slug" id="slug">
                </div>

                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea class="form-control custom-input" name="description" rows="4">{{ $category->description }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Update Category
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
