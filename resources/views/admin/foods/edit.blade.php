@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-pencil-square"></i> Edit Food</h3>
    <a href="{{ route('admin.foods.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="{{ route('admin.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">

                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Food Name</label>
                    <input type="text" name="name" value="{{ $food->name }}" class="form-control" required>
                </div>

                <!-- Slug -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" value="{{ $food->slug }}" class="form-control" id="slugInput">
                </div>

                <!-- Price -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ $food->price }}" class="form-control" required>
                </div>
                <!-- Quantity -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Quantity</label>
                    <input type="number" name="qty" value="{{ $food->qty }}" class="form-control" min="0" placeholder="Enter quantity" required>
                </div>
                <!-- Category -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category_id" class="form-select" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $food->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Active Status -->
               <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>

                    <!-- Always send 0 if not checked -->
                    <input type="hidden" name="is_active" value="0">

                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                            {{ $food->is_active ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>


                <!-- Description -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="3" class="form-control">{{ $food->description }}</textarea>
                </div>

                <!-- Upload Image -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Food Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Current Image Preview -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Current Image</label>
                    <div class="border rounded p-2" style="height:180px; display:flex; justify-content:center; align-items:center;">
                        <img id="preview"
                            src="{{ $food->image ? asset('uploads/foods/' . $food->image) : 'https://via.placeholder.com/180' }}"

                            class="img-fluid rounded"
                            style="max-height:160px;">
                    </div>
                </div>

                <!-- Save Button -->
                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-save"></i> Update Food
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

<script>
function previewImage(event) {
    document.getElementById('preview').src = URL.createObjectURL(event.target.files[0]);
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
