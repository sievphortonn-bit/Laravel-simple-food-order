<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminFoodController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BakongController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
 // USERS
    // USER SIDE
    Route::get('/', [UserController::class, 'index'])->name('user.home');
    Route::get('/foods', [UserController::class, 'foods'])->name('user.foods');
    // Route::get('/food/{id}', [UserController::class, 'foodDetails'])->name('user.food-details');
    Route::get('/food/{slug}', [UserController::class, 'foodDetails'])->name('user.food-details');


    // Cart
    // Route::post('/cart/add', [UserController::class, 'addToCart'])->name('user.cart.add');
    Route::post('/cart/add/{id}', [UserController::class, 'addToCart'])->name('user.cart.add');
    Route::get('/clear-cart', function () {
        session()->forget('cart');
        return 'Cart cleared';
    });
    Route::post('/cart/update/{id}', [UserController::class, 'updateCart'])->name('user.cart.update');

    Route::get('/cart', [UserController::class, 'cart'])->name('user.cart');
    Route::get('/cart/remove/{id}', [UserController::class, 'removeItem'])->name('user.cart.remove');

    // Checkout
    Route::get('/checkout', [UserController::class, 'checkout'])->name('user.checkout');
    Route::post('/checkout', [UserController::class, 'submitOrder'])->name('user.order.submit');
    Route::post('/checkout/process', [UserController::class, 'process'])
        ->name('user.checkout.process');
    Route::get('/order/success/{id}', [UserController::class, 'success'])->name('order.success');


// ---------------------------
// ADMIN LOGIN
// ---------------------------
Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
Route::get('/login', [UserController::class, 'loginForm'])->name('login.form');
Route::post('/logout', [UserController::class, 'Userlogout'])->name('user.logout');

Route::get('/register', [UserController::class, 'registerForm'])->name('register.form');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

Route::get('/admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register.form');
Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register');
// ---------------------------
// ADMIN DASHBOARD (Protected)
// ---------------------------
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

   

    // CATEGORIES
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}/update', [CategoryController::class, 'update'])->name('categories.update');

    Route::delete('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.delete');

    // FOODS
    // Route::resource('foods', App\Http\Controllers\Admin\FoodController::class);
    Route::get('foods', [FoodController::class, 'index'])->name('foods.index');
    Route::get('foods/create', [FoodController::class, 'create'])->name('foods.create');
    Route::get('foods/{food}/edit', [FoodController::class, 'edit'])->name('foods.edit');
    Route::post('foods/store', [FoodController::class, 'store'])->name('foods.store');
    Route::put('foods/{food}/update', [FoodController::class, 'update'])->name('foods.update');
    Route::delete('foods/{food}/delete', [FoodController::class, 'destroy'])->name('foods.destroy');

    // ORDERS
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/status/{id}', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // REPORTS
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::post('reports/filter', [AdminReportController::class, 'filter'])->name('reports.filter');


    Route::get('/bakongkhqr', [BakongController::class, 'bakong'])->name('bakong');
    Route::post('/save-decoded-data', [BakongController::class, 'saveDecodedData'])->name('saveDecodedData');
 
    Route::get('/view-khqr', [BakongController::class, 'viewkhqr'])->name('bakongView');
    
    // Route::post('/save-token', [RenewtokenController::class, 'saveToken']);
    // Route::post('/save-token', [RenewtokenController::class, 'saveToken'])->name('saveToken');
    // Route::post('/save-token', [TokenController::class, 'saveToken']);
    // Route::post('/save-token', [TokenController::class, 'saveToken']);
    // Route::post('/save-renew-token', [RenewtokenController::class, 'saveRenewTokenData'])->name('saveRenewtokenData');

    // Route::post('/save-token', [TokenController::class, 'saveToken'])->name('save.token');
    Route::post('/save-token', [TokenController::class, 'store'])->name('save.token');

});
