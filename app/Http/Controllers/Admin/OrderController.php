<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $status = $request->query('status', 'all');

    $orders = Order::with(['items.foods', 'user']);

    if ($status != 'all') {
        $orders = $orders->where('status', $status);
    }

    $orders = $orders->orderBy('created_at', 'desc')->get();

    return view('admin.orders.index', compact('orders', 'status'));
}

// Download invoice
public function downloadInvoice($id)
{
    $order = Order::with(['items.foods', 'user'])->findOrFail($id);

    $pdf = \PDF::loadView('admin.orders.invoice', compact('order'));
    return $pdf->download('invoice_order_'.$order->id.'.pdf');
}

public function show($id)
{
    $order = Order::with(['items.foods', 'user'])->findOrFail($id);
    return view('admin.orders.show', compact('order'));
}

// Update status
public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $request->validate([
        'status' => 'required|in:pending,completed,cancelled'
    ]);

    $order->status = $request->status;
    $order->save();

    return back()->with('success', 'Order status updated successfully.');
}

}
