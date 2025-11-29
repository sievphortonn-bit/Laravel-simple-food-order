<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top user-navbar">
    <div class="container">

        <!-- BRAND -->
        <div class="brand-container">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Food_Network_New_Logo.png?20181129223131"
                 class="brand-logo" alt="Logo">
            <span class="brand-text">MyFood</span>
        </div>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- NAV ITEMS -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Foods -->
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('user.foods') }}">Foods</a>
                </li>

                <!-- Cart -->
                <li class="nav-item">
                    <a class="nav-link fw-semibold position-relative" href="{{ route('user.cart') }}">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="cart-badge" id="cartCount">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </a>
                </li>

                <!-- User Dropdown -->
                <!-- User Section -->
        @auth
        <li class="nav-item dropdown ms-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
            data-bs-toggle="dropdown">

                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}"
                    class="rounded-circle me-2" width="35" height="35" style="object-fit: cover;">

                <span class="fw-semibold">{{ Auth::user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                <li><a class="dropdown-item" href="">
                    <i class="bi bi-person"></i> Profile
                </a></li>

                <li><a class="dropdown-item" href="">
                    <i class="bi bi-receipt"></i> My Orders
                </a></li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <form action="{{ route('user.logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>

            </ul>
        </li>
        @endauth

        @guest
        <li class="nav-item ms-3">
            <a href="{{ route('login.form') }}" class="btn btn-primary px-3">Login</a>
        </li>
        <li class="nav-item ms-2">
            <a href="{{ route('register.form') }}" class="btn btn-outline-primary px-3">Register</a>
        </li>
        @endguest


                    </ul>

                </div>

            </div>
        </nav>

<!-- STYLE -->
<style>
.user-navbar {
    transition: background 0.3s, box-shadow 0.3s;
}

/* BRAND */
.brand-container {
    display: flex;
    align-items: center;
}

.brand-logo {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.brand-text {
    margin-left: 10px;
    font-size: 22px;
    font-weight: 700;
    color: #333; /* FIXED: brand text was white and invisible */
}

/* Cart badge */
.cart-badge {
    background: #ff4757;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
    position: absolute;
    top: 0;
    right: -5px;
}

/* Hover effect */
.nav-link {
    transition: 0.3s;
}

.nav-link:hover {
    color: #0d6efd !important;
}

.dropdown-menu {
    border-radius: 12px;
}
</style>
