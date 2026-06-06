<?php

namespace App\Http\Controllers;

use App\Models\SellRequest;
use Illuminate\Http\Request;

class SellController extends Controller
{
    public function create()
    {
        return view('pages.sell');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:120'],
            'email'      => ['required', 'email', 'max:160'],
            'phone'      => ['required', 'string', 'max:40'],
            'brand'      => ['nullable', 'string', 'max:80'],
            'model'      => ['nullable', 'string', 'max:120'],
            'year'       => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'mileage_km' => ['nullable', 'integer', 'min:0'],
            'message'    => ['nullable', 'string', 'max:2000'],
            'photo'      => ['nullable', 'image', 'max:8192'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('sell', 'public');
        }

        SellRequest::create($data);

        return redirect()->route('sell.create')->with('sent_ok', true);
    }
}
