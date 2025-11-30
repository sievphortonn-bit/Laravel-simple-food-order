<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Food Order System</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: #f1f3f9;
            font-family: 'Segoe UI', sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #1e1f26;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: width 0.3s;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        /* BRAND SECTION */
        .brand-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 35px;
            transition: 0.3s;
        }

        .brand-logo {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            transition: 0.3s;
        }

        .brand-text {
            margin-left: 12px;
            font-size: 20px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            transition: 0.3s;
        }

        /* Collapse: hide brand text */
        .sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            margin: 0;
        }

        /* Collapse: reduce logo size */
        .sidebar.collapsed .brand-logo {
            width: 40px;
            height: 40px;
        }

        .sidebar ul {
            padding-left: 0;
        }

        .sidebar ul li {
            list-style: none;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            color: #cfd2d4;
            font-size: 15px;
            transition: 0.2s;
            border-radius: 6px;
            margin: 6px 10px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar ul li a i {
            font-size: 20px;
            margin-right: 12px;
            min-width: 20px;
            text-align: center;
        }

        /* Hide text when collapsed */
        .sidebar.collapsed .menu-text {
            display: none;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #0d6efd;
            color: white;
        }

        /* MAIN CONTENT */
        .main {
            margin-left: 260px;
            transition: margin-left 0.3s;
        }

        .main.collapsed {
            margin-left: 80px;
        }

        .topbar {
            background: white;
            padding: 15px 25px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toggle-btn {
            font-size: 22px;
            cursor: pointer;
            color: #333;
        }

        .content {
            padding: 25px;
        }
    </style>

    <style>
    /* Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
       
    }

    /* Card-card Styling */
    .card-card {
        width: 300px;
        height: 420px; /* Fixed height for the card-card */
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background-color: white;
        margin: 20px auto;
        text-align: left; /* Align text to the left */
        border: 1px solid #e0e0e0;
    }

    /* Header Styling */
    .card-card-header {
        background-color: rgb(225, 35, 46);
        color: white;
        padding: 12px;
        font-size: 20px;
        font-weight: bold;
        position: relative;
    }

    /* Right-Angle Cutout */
    .card-card-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 60px;
        background-color: rgb(225, 35, 46);
         clip-path: polygon(100% 0, 100% 100%, 0 40%);
        transform: translate(20px, 20px); /* Adjust positioning to align the angle */
    }

    /* Card-card Content */
    .card-card-content {
        padding: 20px;
        color: #333;
        font-family: 'Nunito Sans', sans-serif;
    }

    .card-card-content .name {
        font-size: calc(0.03 * 330px); /* 3% of the card-card height */
        font-weight: 600;
        margin-bottom: 8px;
        font-family: 'Nunito Sans', sans-serif;
    }

    .card-card-content .amount {
        font-size: calc(0.065 * 330px); /* 6.5% of the card-card height */
        font-weight: bold;
        color: #000;
        margin: 5px 0;
        font-family: 'Nunito Sans', sans-serif;
    }

    .card-card-content .currency {
        font-size: calc(0.03 * 340px); /* 3% of the card-card height */
        color: rgb(0, 0, 0);
        font-family: 'Nunito Sans', sans-serif;
    }

    /* Divider Styling */
    .divider {
        height: 1px;
        width: 90%;
        margin: 10px auto;
        background-image: linear-gradient(to right, #ccc 33%, rgba(255, 255, 255, 0) 0%);
        background-position: bottom;
        background-size: 10px 1px;
        background-repeat: repeat-x;
    }

    /* QR Code Section */
    .qr-code {
        padding: 20px;
        background-color: #ffffff;
        display: flex;
        justify-content: center;
    }

    .qr-code img {
        width: 210px;
        height: 210px;
        border-radius: 8px;
    }
   

  </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div id="sidebar" class="sidebar">

        <!-- BRAND -->
        <div class="brand-container">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Food_Network_New_Logo.png?20181129223131" class="brand-logo" alt="Logo">
            <span class="brand-text">Food Admin</span>
        </div>

        <ul>
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span class="menu-text">Dashboard</span>
            </a></li>

            <li><a href="{{ route('admin.users') }}" class="{{ request()->is('admin/bakong') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span class="menu-text">Users</span>
            </a></li>
            <li><a href="{{ route('admin.bakong') }}" class="{{ request()->is('admin/bakong') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i> <span class="menu-text">Bakong</span>
            </a></li>
            <li><a href="{{ route('admin.bakongView') }}" class="{{ request()->is('admin/bakongView') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i> <span class="menu-text">View Qr Bakong</span>
            </a></li>
            <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->is('admin/categories') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i> <span class="menu-text">Categories</span>
            </a></li>

            <li><a href="{{ route('admin.foods.index') }}" class="{{ request()->is('admin/foods') ? 'active' : '' }}">
                <i class="bi bi-egg-fried"></i> <span class="menu-text">Foods</span>
            </a></li>

            <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->is('admin/orders') ? 'active' : '' }}">
                <i class="bi bi-bag"></i> <span class="menu-text">Orders</span>
            </a></li>

            <li><a href="{{ route('admin.reports') }}" class="{{ request()->is('admin/reports') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> <span class="menu-text">Reports</span>
            </a></li>

            {{-- <li><a href="{{ route('admin.logout') }}">
                <i class="bi bi-box-arrow-right"></i> <span class="menu-text">Logout</span>
            </a></li> --}}
        </ul>
    </div>
    

    <!-- MAIN CONTENT -->
    <div id="main" class="main">

        <!-- TOPBAR -->
        <div class="topbar d-flex align-items-center justify-content-between px-3">
    
            <!-- Sidebar Toggle -->
            <span class="toggle-btn" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </span>

            <!-- Title -->
            <h5 class="m-0 fw-semibold">Food Order Admin</h5>

            <!-- Admin Dropdown -->
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="adminDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                    
                    <i class="bi bi-person-circle me-2"></i>

                    <!-- Admin Name -->
                    {{ auth()->user()->name ?? 'Admin' }}

                </button>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person-lines-fill me-2"></i> Profile
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>

                </ul>
            </div>

        </div>


        <!-- PAGE CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
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


<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('collapsed');
}
</script>

@stack('scripts')
</body>
</html>
