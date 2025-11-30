@extends('frontend.layouts.app')

@section('content')

<div class="thank-you-page d-flex flex-column justify-content-center align-items-center text-center py-5">

    <!-- Success Icon -->
    <div class="mb-4">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
    </div>

    <!-- Heading -->
    <h1 class="fw-bold mb-3">Thank You!</h1>

    <!-- Message -->
    <p class="lead mb-4 text-secondary">
        Your order has been successfully placed.<br>
        We will start preparing your delicious meal right away.
    </p>

    <!-- Call-to-action buttons -->
    <div>
        <a href="{{ route('user.home') }}" class="btn btn-primary btn-lg rounded-pill me-2">
            <i class="bi bi-house-door"></i> Back to Home
        </a>

        <a href="{{ route('user.orders') }}" class="btn btn-outline-primary btn-lg rounded-pill">
            <i class="bi bi-bag-check"></i> View My Orders
        </a>
    </div>

    <!-- Optional: Decorative image -->
    <div class="mt-5">
        <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=600&q=80"
             class="img-fluid rounded shadow-sm" style="max-width: 400px;">
    </div>

</div>

@endsection

<!-- Page Styles -->
<style>
.thank-you-page {
    min-height: 70vh;
}

.thank-you-page p {
    line-height: 1.6;
}

.thank-you-page a.btn {
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
}

@media (max-width: 576px) {
    .thank-you-page a.btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
