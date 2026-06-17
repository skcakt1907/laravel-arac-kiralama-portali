<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SellRequest;
use App\Models\User;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    /**
     * Küçük CRM: üye + sipariş müşterisi + sat talebi sahiplerini e-postaya göre
     * tek listede birleştirir; özet istatistiklerle birlikte gösterir.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $key = fn ($e) => mb_strtolower(trim((string) $e));

        $c = [];
        $ensure = function (&$c, $k, $name, $phone) {
            if (! isset($c[$k])) {
                $c[$k] = [
                    'name' => $name, 'email' => $k, 'phone' => $phone,
                    'is_member' => false, 'is_customer' => false, 'is_lead' => false,
                    'orders' => 0, 'spent' => 0.0, 'currency' => null, 'last' => null, 'joined' => null,
                ];
            } else {
                if (empty($c[$k]['name']) && $name) $c[$k]['name'] = $name;
                if (empty($c[$k]['phone']) && $phone) $c[$k]['phone'] = $phone;
            }
        };
        $touchLast = function (&$row, $date) {
            if ($date && (! $row['last'] || $date->gt($row['last']))) $row['last'] = $date;
        };

        // 1) Üyeler
        foreach (User::where('is_admin', false)->get() as $u) {
            $k = $key($u->email);
            $ensure($c, $k, $u->name, null);
            $c[$k]['is_member'] = true;
            $c[$k]['joined'] = $u->created_at;
            $touchLast($c[$k], $u->created_at);
        }

        // 2) Sipariş müşterileri
        foreach (Order::orderBy('created_at')->get() as $o) {
            $k = $key($o->email);
            $ensure($c, $k, $o->customer_name, $o->phone);
            $c[$k]['is_customer'] = true;
            $c[$k]['orders']++;
            if ($o->status === Order::STATUS_PAID) $c[$k]['spent'] += (float) $o->total;
            $c[$k]['currency'] = $o->currency;
            $touchLast($c[$k], $o->created_at);
        }

        // 3) Aracını sat talepleri (potansiyel müşteri / lead)
        foreach (SellRequest::get() as $s) {
            $k = $key($s->email);
            $ensure($c, $k, $s->name, $s->phone);
            $c[$k]['is_lead'] = true;
            $touchLast($c[$k], $s->created_at);
        }

        $contacts = collect($c)->values();

        if ($q !== '') {
            $needle = mb_strtolower($q);
            $contacts = $contacts->filter(fn ($x) =>
                str_contains(mb_strtolower($x['name'] . ' ' . $x['email'] . ' ' . $x['phone']), $needle));
        }

        $contacts = $contacts
            ->sortByDesc(fn ($x) => optional($x['last'])->timestamp ?? 0)
            ->values();

        $stats = [
            'members'   => User::where('is_admin', false)->count(),
            'customers' => Order::whereNotNull('email')->distinct('email')->count('email'),
            'orders'    => Order::count(),
            'paid'      => Order::where('status', Order::STATUS_PAID)->count(),
            'pending'   => Order::where('status', Order::STATUS_PENDING)->count(),
            'leads'     => SellRequest::count(),
            'contacts'  => $contacts->count(),
        ];

        return view('admin.crm.index', compact('contacts', 'stats', 'q'));
    }
}
