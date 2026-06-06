<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        // WeoBank yapılandırılmış mı? (.env -> WEOBANK_* )
        $configured = (bool) config('services.weobank.api_key');

        return view('shop.payment', compact('order', 'configured'));
    }

    /**
     * Ödemeyi başlat.
     *
     * NOT: WeoBank API bilgileri (WEOBANK_API_KEY vb.) geldiğinde burada
     * gerçek ödeme isteği oluşturulup kullanıcı WeoBank'a yönlendirilecek.
     * Şu an seam hazır; anahtar yoksa sipariş "pending" kalır.
     */
    public function pay(Request $request, Order $order)
    {
        if ($order->status === Order::STATUS_PAID) {
            return redirect()->route('payment.show', $order);
        }

        if (! config('services.weobank.api_key')) {
            // API henüz bağlı değil — sipariş kaydı duruyor, müşteriye bilgi.
            return redirect()->route('payment.show', $order)
                ->with('sent_ok', 'payment_pending');
        }

        // TODO (WeoBank): API'ye ödeme isteği aç, dönen redirect URL'sine yönlendir.
        // $session = WeoBank::createPayment([...]);
        // return redirect()->away($session['redirect_url']);

        return redirect()->route('payment.show', $order);
    }

    /**
     * WeoBank dönüş (callback/webhook) — API gelince doldurulacak.
     */
    public function callback(Request $request)
    {
        // TODO (WeoBank): imza doğrula, order_no eşleştir, başarılıysa:
        // $order->update(['status' => Order::STATUS_PAID, 'paid_at' => now(), 'payment_ref' => ...]);

        return response('OK');
    }
}
