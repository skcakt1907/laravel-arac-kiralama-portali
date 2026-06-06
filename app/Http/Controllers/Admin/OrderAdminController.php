<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,paid,shipped,cancelled'],
        ]);

        $order->status = $data['status'];
        if ($data['status'] === Order::STATUS_PAID && ! $order->paid_at) {
            $order->paid_at = now();
        }
        $order->save();

        return back()->with('ok', 'Sipariş durumu güncellendi.');
    }
}
