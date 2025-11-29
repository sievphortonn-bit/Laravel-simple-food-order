@extends('frontend.layouts.app')

@section('content')
<style>
        body {
            background: #f2f4f7;
        }
        .login-card {
            max-width: 420px;
            margin: 70px auto;
            padding: 25px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 30px;
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
    <div class="login-card">
        <div class="text-center mb-3">
           
            {{-- <div class="logo">Login</div> --}}
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Food_Network_New_Logo.png?20181129223131" class=" mt-2" width="100" alt="">
            <br>
            <br>
            <p>login to start your order</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="admin@example.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>

        </form>
    </div>


<script>
function togglePassword() {
    let pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>
@endsection

