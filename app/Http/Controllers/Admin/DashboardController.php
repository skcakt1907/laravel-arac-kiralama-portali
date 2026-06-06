<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Part;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'vehicles'       => Vehicle::count(),
            'vehicles_pub'   => Vehicle::where('is_published', true)->count(),
            'parts'          => Part::count(),
            'orders'         => Order::count(),
            'orders_pending' => Order::where('status', Order::STATUS_PENDING)->count(),
        ];

        $recentOrders = Order::latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
