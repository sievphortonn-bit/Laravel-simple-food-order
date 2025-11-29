<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Food;
use App\Models\Merchantbakongkhqr;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
    

class UserController extends Controller
{
    // -------------------------
    // HOME PAGE (SHOW FOODS)
    // -------------------------
    public function index(Request $request)
    {
        $foods = Food::where('is_active', 1)
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->category, function ($q) use ($request) {
                return $q->where('category_id', $request->category);
            })
            ->paginate(12);

        $categories = Category::all();

        return view('frontend.home', compact('foods', 'categories'));
    }

    public function foods(Request $request)
    {
        $foods = Food::where('is_active', 1)
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->category, function ($q) use ($request) {
                return $q->where('category_id', $request->category);
            })
            ->paginate(12);

        $categories = Category::all();

        return view('frontend.foods', compact('foods', 'categories'));
    }

    // -------------------------
    // SHOW FOOD DETAILS
    // -------------------------
    public function foodDetails($slug)
    {
        $food = Food::where('slug', $slug)->firstOrFail();
        return view('frontend.food-details', compact('food'));
    }

    // -------------------------
    // ADD TO CART
    // -------------------------
    public function addToCart(Request $request)
{
    // Validate request
    $request->validate([
        'food_id'  => 'required|exists:foods,id',
        'quantity' => 'required|integer|min:1'
    ]);

    // Check login
    if (!auth()->check()) {
        return redirect()->route('login.form')->with('error', 'Please login first.');
    }

    $userId = auth()->id();
    $foodId = $request->food_id;

    // Check if this food already in cart for this user
    $cartItem = Cart::where('user_id', $userId)
                    ->where('food_id', $foodId)
                    ->first();

    if ($cartItem) {
        // Update quantity
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        // Create new row in DB
        Cart::create([
            'user_id'  => $userId,
            'food_id'  => $foodId,
            'quantity' => $request->quantity,
        ]);
    }

    return back()->with('success', 'Added to cart!');
}







    // -------------------------
    // VIEW CART
    // -------------------------
    public function cart()
    {
        $cart = Cart::with('food')
                ->where('user_id', auth()->id())
                ->get();
        // $cart = session()->get('cart', []);
        return view('frontend.cart', compact('cart'));
    }

    // -------------------------
    // UPDATE CART QUANTITY
    // -------------------------
    // public function updateCart(Request $request, $id)
    // {
    //     $cart = session()->get('cart', []);

    //     if (!isset($cart[$id])) {
    //         return back()->with('error', 'Item not found in cart.');
    //     }

    //     if ($request->action === 'increase') {
    //         $cart[$id]['quantity'] += 1;
    //     } 
        
    //     elseif ($request->action === 'decrease') {
    //         if ($cart[$id]['quantity'] > 1) {
    //             $cart[$id]['quantity'] -= 1;
    //         } else {
    //             unset($cart[$id]); // remove item if qty reaches 0
    //         }
    //     }

    //     session()->put('cart', $cart);

    //     return back()->with('success', 'Cart updated.');
    // }
    public function updateCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        if ($request->action == 'increase') {
            $cart->quantity += 1;
            $message = 'Quantity increased successfully!';
        }

        if ($request->action == 'decrease' && $cart->quantity > 1) {
            $cart->quantity -= 1;
            $message = 'Quantity decreased successfully!';
        }

        $cart->save();

        return back()->with('success', $message);
    }



    // -------------------------
    // CHECKOUT PAGE
    // -------------------------
    public function checkout()
    {
        // get the current user's cart
        $cart = Cart::where('user_id', auth()->id())->get();

        $bakong = Merchantbakongkhqr::first();

        if ($cart->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Your cart is empty.');
        }

        // calculate totals
        $subtotal = $cart->sum(function($item){
            return $item->food->price * $item->quantity;
        });

        $tax = round($subtotal * 0.00, 2);
        $shippingFee = 0.00;
        $total = round($subtotal + $tax + $shippingFee, 2);

        // ⭐ ADD THIS
        $totalProductAmount = $total;

        return view(
            'frontend.checkout',
            compact('cart','bakong','subtotal','tax','shippingFee','total','totalProductAmount')
        );
    }



public function process(Request $request)
{
    // Validate
    $request->validate([
        'customer_name' => 'required',
        'customer_email' => 'required|email',
        'payment_method' => 'required',
    ]);

    $cartItems = Cart::where('user_id', auth()->id())->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('user.cart')->with('error', 'Your cart is empty.');
    }

    DB::beginTransaction(); // START TRANSACTION

    try {

        // Calculate totals
        $subtotal = $cartItems->sum(fn($i) => $i->food->price * $i->quantity);
        $tax = 0;
        $shipping = 0;
        $total = $subtotal + $tax + $shipping;

        // Create Order
        $order = Order::create([
            'user_id'          => auth()->id(),
            'customer_name'    => $request->customer_name,
            'customer_email'   => $request->customer_email,
            'customer_phone'   => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'subtotal'         => $subtotal,
            'tax'              => $tax,
            'shipping_fee'     => $shipping,
            'total'            => $total,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'unpaid',
            'status'           => 'Pending',
        ]);

        // Create order_items & cut stock
        foreach ($cartItems as $item) {

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'food_id'  => $item->food_id,
                'qty'      => $item->quantity,
                'price'    => $item->food->price,
                'total'    => $item->food->price * $item->quantity,
            ]);

            // Cut stock
            $food = $item->food;
            $food->qty -= $item->quantity;
            if ($food->qty < 0) $food->qty = 0; // Prevent negative qty
            $food->save();
        }

        // Clear cart
        Cart::where('user_id', auth()->id())->delete();

        DB::commit(); // SUCCESS

        return redirect()->route('order.success', $order->id)
                         ->with('success', 'Order placed successfully!');

    } catch (\Exception $e) {
        DB::rollBack(); // ROLLBACK IF ERROR
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


    // -------------------------
    // PLACE ORDER
    // -------------------------
    public function placeOrder(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone'    => 'required|string|max:50',
            'address'  => 'required|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (!$cart) {
            return back()->with('error', 'Cart is empty');
        }

        // Create Order
        $order = Order::create([
            'user_id'  => Auth::id(),
            'fullname' => $request->fullname,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'total'    => array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $cart)),
            'status'   => 'Pending',
        ]);

        // Save order items
        foreach ($cart as $foodId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id'  => $foodId,
                'quantity' => $item['qty'],
                'price'    => $item['price']
            ]);
        }

        session()->forget('cart');

        return redirect()->route('user.thankyou')->with('success', 'Order placed successfully!');
    }

    // -------------------------
    // THANK YOU PAGE
    // -------------------------
    public function success($id)
    {
        $order = Order::findOrFail($id);

        return view('frontend.success', compact('order'));
    }


    public function loginForm()
    {
        return view('frontend.login');
    }
    public function registerForm()
    {
        return view('frontend.register');
    }

    public function Userlogout()
    {
        auth()->logout();
        return redirect()->route('user.home');
    }
}
