<?php

namespace App\Http\Controllers;

use App\Models\Rental;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::published()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12);

        return view('rentals.index', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        abort_unless($rental->is_published, 404);

        $related = Rental::published()
            ->whereKeyNot($rental->id)
            ->limit(3)
            ->get();

        return view('rentals.show', compact('rental', 'related'));
    }
}
