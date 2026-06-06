<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected Cart $cart) {}

    public function index()
    {
        return view('shop.cart', [
            'items'    => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'currency' => $this->cart->currency(),
        ]);
    }

    public function add(Request $request, Part $part)
    {
        abort_unless($part->is_published, 404);

        $qty = max(1, (int) $request->input('qty', 1));
        $this->cart->add($part->id, $qty);

        return redirect()->route('cart.index')->with('sent_ok', 'added');
    }

    public function update(Request $request, Part $part)
    {
        $this->cart->update($part->id, (int) $request->input('qty', 1));

        return redirect()->route('cart.index');
    }

    public function remove(Part $part)
    {
        $this->cart->remove($part->id);

        return redirect()->route('cart.index');
    }
}
