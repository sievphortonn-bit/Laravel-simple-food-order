<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use PDF; // for PDF export

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.foods', 'user']);

        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->date_filter && $request->date_filter != 'all') {
            switch($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', now());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Summary info
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');

        return view('admin.reports.index', compact('orders', 'totalOrders', 'totalRevenue'));
    }

    public function export(Request $request)
    {
        $query = Order::with(['items.foods', 'user']);

        // Apply same filters as index
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->date_filter && $request->date_filter != 'all') {
            switch($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', now());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $pdf = PDF::loadView('admin.reports.pdf', compact('orders'));
        return $pdf->download('report_'.now()->format('Ymd_His').'.pdf');
    }
}
