@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-plus-circle"></i> Create Food</h3>
    <a href="{{ route('admin.foods.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Food Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter food name" required>
                </div>
                <!-- Slug -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" class="form-control" placeholder="auto-filled..." id="slugInput">
                </div>

                <!-- Price -->
                <!-- Price -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price" required>
                </div>

                <!-- Quantity -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Quantity</label>
                    <input type="number" name="qty" class="form-control" min="0" placeholder="Enter quantity" required>
                </div>


                <!-- Category -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Active status -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Write food description..."></textarea>
                </div>

                <!-- Image Upload -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Food Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Image Preview -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Preview</label>
                    <div class="border rounded p-2" style="height: 180px; display:flex; align-items:center; justify-content:center;">
                        <img id="preview" src="https://via.placeholder.com/180" class="img-fluid rounded"
                             style="max-height:160px;">
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-check-circle"></i> Save Food
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

<script>
function previewImage(event) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
}
</script>

<script>
document.querySelector('input[name="name"]').addEventListener('keyup', function() {
    let text = this.value.toLowerCase().trim();
    text = text.replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    document.getElementById('slugInput').value = text;
});
</script>


@endsection
