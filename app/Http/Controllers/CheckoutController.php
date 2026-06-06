<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected Cart $cart) {}

    public function form()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('shop.checkout', [
            'items'    => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'currency' => $this->cart->currency(),
        ]);
    }

    public function place(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'email'         => ['required', 'email', 'max:160'],
            'phone'         => ['required', 'string', 'max:40'],
            'country'       => ['nullable', 'string', 'max:80'],
            'city'          => ['nullable', 'string', 'max:80'],
            'address'       => ['required', 'string', 'max:400'],
            'note'          => ['nullable', 'string', 'max:1000'],
        ]);

        $items    = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $currency = $this->cart->currency();

        $order = DB::transaction(function () use ($data, $items, $subtotal, $currency) {
            $order = Order::create([
                'order_no'       => Order::generateOrderNo(),
                'customer_name'  => $data['customer_name'],
                'email'          => $data['email'],
                'phone'          => $data['phone'],
                'country'        => $data['country'] ?? null,
                'city'           => $data['city'] ?? null,
                'address'        => $data['address'],
                'note'           => $data['note'] ?? null,
                'subtotal'       => $subtotal,
                'total'          => $subtotal,
                'currency'       => $currency,
                'status'         => Order::STATUS_PENDING,
                'payment_method' => 'weobank',
            ]);

            foreach ($items as $row) {
                $part = $row['part'];
                OrderItem::create([
                    'order_id'   => $order->id,
                    'part_id'    => $part->id,
                    'name'       => $part->name,
                    'unit_price' => $part->price,
                    'qty'        => $row['qty'],
                    'line_total' => $row['line_total'],
                ]);

                // stok düş (negatife düşmesin)
                $part->decrement('stock', min($part->stock, $row['qty']));
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('payment.show', $order);
    }
}
