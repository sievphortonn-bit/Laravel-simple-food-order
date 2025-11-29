<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Food Order' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://github.com/davidhuotkeo/bakong-khqr/releases/download/bakong-khqr-1.0.6/khqr-1.0.6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/javascript/javascript.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/theme/base16-light.min.css">
  <script src="https://github.com/davidhuotkeo/bakong-khqr/releases/download/bakong-khqr-1.0.6/khqr-1.0.6.min.js"></script>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        body {
            background: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.4rem;
        }
        .food-img {
            height: 170px;
            object-fit: cover;
            border-radius: 12px;
        }
    </style>
</head>
<body>

@include('frontend.layouts.navbar')

<div class="container py-4">
    @yield('content')
</div>


<footer class="bg-dark text-white text-center py-3 mt-5">
    <small>© {{ date('Y') }} FoodOrder. All rights reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const toastSuccess = @json(session('success'));
    const toastError   = @json(session('error'));
    const toastInfo    = @json(session('info'));
    const toastWarning = @json(session('warning'));

    // SweetAlert2 Toast Config
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end", // TOP RIGHT
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (toastSuccess) {
            Toast.fire({
                icon: 'success',
                title: toastSuccess
            });
        }

        if (toastError) {
            Toast.fire({
                icon: 'error',
                title: toastError
            });
        }

        if (toastInfo) {
            Toast.fire({
                icon: 'info',
                title: toastInfo
            });
        }

        if (toastWarning) {
            Toast.fire({
                icon: 'warning',
                title: toastWarning
            });
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

@stack('scripts')
</body>
</html>
