<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Try login
        if (auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {

            $user = auth()->user();

            // Check if admin
            if ($user->is_admin == 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.home');
            }

            // return redirect()->route('user.home');
        }

        return back()->with('error', 'Invalid login');
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'orderCount' => Order::count(),
            'revenue' => Order::where('status', 'Completed')->sum('total'),
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }

    // Handle register
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed'
        ]);

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'is_admin' => 1, // IMPORTANT
        ]);

        return redirect()->route('admin.login')->with('success', 'Admin registered successfully.');
    }
}
