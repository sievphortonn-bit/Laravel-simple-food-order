<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Food;
use App\Models\Merchantbakongkhqr;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class UserController extends Controller
{
    // -------------------------
    // HOME PAGE (SHOW FOODS)
    // -------------------------
    public function user(Request $request)
    {
        $query = User::where('is_admin', 0); // regular users

        // Search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    public function index(Request $request)
    {
        $foods = Food::where('is_active', 1)
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%'.$request->search.'%');
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
                return $q->where('name', 'like', '%'.$request->search.'%');
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
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check login
        if (! auth()->check()) {
            return redirect()->route('login.form')->with('error', 'Please login first.');
        }

        $userId = auth()->id();
        $foodId = $request->food_id;
        $quantity = $request->quantity;

        // Get the food item
        $food = \App\Models\Food::find($foodId);

        if (! $food) {
            return back()->with('error', 'Food item not found.');
        }

        // Check if requested quantity is available
        // if ($quantity > $food->quantity || $food->quantity <= 0) {
        //     return back()->with('error', 'Requested quantity exceeds available stock.');
        // }

        // Check if this food already in cart for this user
        $cartItem = Cart::where('user_id', $userId)
            ->where('food_id', $foodId)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $quantity;

            // Make sure not exceeding stock
            if ($newQty > $food->quantity) {
                return back()->with('error', 'Cannot add more than available stock.');
            }

            $cartItem->quantity = $newQty;
            $cartItem->save();
        } else {
            // Create new cart row
            Cart::create([
                'user_id' => $userId,
                'food_id' => $foodId,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Added to cart!');
    }

    // -------------------------
    // VIEW CART
    // -------------------------
    public function cart()
    {
         if (!auth()->check()) {
            return redirect()->route('login.form')
                ->with('error', 'Please login first.');
        }
        $cart = Cart::with('food')
            ->where('user_id', auth()->id())
            ->get();

        // $cart = session()->get('cart', []);
        return view('frontend.cart', compact('cart'));
    }

    // -------------------------
    // UPDATE CART QUANTITY
    // -------------------------
    
    public function updateCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        // Get food stock
        $food = \App\Models\Food::find($cart->food_id);

        if (! $food) {
            return back()->with('error', 'Food item not found.');
        }

        // Action: increase quantity
        if ($request->action == 'increase') {
            $newQty = $cart->quantity + 1;

            // Check stock before increasing
            if ($newQty > $food->quantity || $food->quantity <= 0) {
                return back()->with('error', 'Requested quantity exceeds available stock.');
            }

            $cart->quantity = $newQty;
            $cart->save();

            return back()->with('success', 'Quantity increased successfully!');
        }

        // Action: decrease quantity
        if ($request->action == 'decrease') {
            if ($cart->quantity > 1) {
                $cart->quantity -= 1;
                $cart->save();

                return back()->with('success', 'Quantity decreased successfully!');
            }

            return back()->with('error', 'Minimum quantity is 1.');
        }

        return back()->with('error', 'Invalid action.');
    }

    // -------------------------
    // CHECKOUT PAGE
    // -------------------------
    public function checkout()
    {
        if (!auth()->check()) {
            return redirect()->route('login.form')
                ->with('error', 'Please login first.');
        }

        // get the current user's cart
        $cart = Cart::where('user_id', auth()->id())->get();

        $bakong = Merchantbakongkhqr::first();

        if ($cart->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Your cart is empty.');
        }

        // calculate totals
        $subtotal = $cart->sum(function ($item) {
            return $item->food->price * $item->quantity;
        });

        $tax = round($subtotal * 0.00, 2);
        $shippingFee = 0.00;
        $total = round($subtotal + $tax + $shippingFee, 2);

        // ⭐ ADD THIS
        $totalProductAmount = $total;

        return view(
            'frontend.checkout',
            compact('cart', 'bakong', 'subtotal', 'tax', 'shippingFee', 'total', 'totalProductAmount')
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
            $subtotal = $cartItems->sum(fn ($i) => $i->food->price * $i->quantity);
            $tax = 0;
            $shipping = 0;
            $total = $subtotal + $tax + $shipping;

            // Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_fee' => $shipping,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'status' => 'Pending',
            ]);

            // Create order_items & cut stock
            foreach ($cartItems as $item) {

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item->food_id,
                    'qty' => $item->quantity,
                    'price' => $item->food->price,
                    'total' => $item->food->price * $item->quantity,
                ]);

                // Cut stock
                $food = $item->food;
                $food->qty -= $item->quantity;
                if ($food->qty < 0) {
                    $food->qty = 0;
                } // Prevent negative qty
                $food->save();
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit(); // SUCCESS

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack(); // ROLLBACK IF ERROR

            return back()->with('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    // -------------------------
    // PLACE ORDER
    // -------------------------
    public function placeOrder(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (! $cart) {
            return back()->with('error', 'Cart is empty');
        }

        // Create Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'address' => $request->address,
            'total' => array_sum(array_map(fn ($item) => $item['price'] * $item['qty'], $cart)),
            'status' => 'Pending',
        ]);

        // Save order items
        foreach ($cart as $foodId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $foodId,
                'quantity' => $item['qty'],
                'price' => $item['price'],
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

    public function myOrders()
    {
        $userId = auth()->id();

        // eager load items and food relationship for performance
        $orders = Order::with(['items.foods'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.my-order', compact('orders'));
    }

    /**
     * Show details for a single order.
     */
    public function orderDetails($id)
    {
        $userId = auth()->id();

        $order = Order::with(['items.foods'])
            ->where('id', $id)
            ->where('user_id', $userId) // protect access
            ->firstOrFail();

        return view('frontend.order-details', compact('order'));
    }

    /**
     * Download invoice as PDF.
     */
    public function downloadInvoice($id)
    {
        $userId = auth()->id();

        $order = Order::with(['items.foods'])
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Render blade to HTML and convert to PDF
        $pdf = PDF::loadView('frontend.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        $filename = 'invoice-'.$order->id.'.pdf';

        return $pdf->download($filename);
    }

    public function removeItem($id)
    {
        $userId = Auth::id();

        // Find the cart item for this user
        $cartItem = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (! $cartItem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
