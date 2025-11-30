@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-4">Edit User</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="is_admin" class="form-select">
                            <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>User</option>
                            <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $user->is_admin == 2 ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary rounded-pill">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="bi bi-check2-circle"></i> Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
