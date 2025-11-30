@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-4">Users Management</h3>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.users') }}" class="mb-4 d-flex gap-2">
        <input type="text" name="search" class="form-control w-auto" placeholder="Search by name/email/phone" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($users->isEmpty())
        <div class="alert alert-info">No users found.</div>
    @else
        <div class="row g-4">
            @foreach($users as $user)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="avatar" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h5 class="card-title mb-0">{{ $user->name }}</h5>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>

                        <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                        <p class="mb-1">
                            <strong>Role:</strong>
                            <span class="badge bg-{{ $user->is_admin == 1 ? 'primary' : ($user->is_admin == 2 ? 'success' : 'secondary') }}">
                                {{ $user->is_admin == 1 ? 'Admin' : ($user->is_admin == 2 ? 'Super Admin' : 'User') }}
                            </span>
                        </p>

                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info w-50 rounded-pill">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="w-50">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100 rounded-pill" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
