<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        // Filtre için mevcut markalar
        $brands = Vehicle::published()
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $activeBrand = $request->query('brand');

        $vehicles = Vehicle::published()
            ->when($activeBrand, fn ($q) => $q->where('brand', $activeBrand))
            ->when($request->query('sort') === 'new',
                fn ($q) => $q->orderByDesc('id'),
                fn ($q) => $q->orderBy('sort_order')->orderByDesc('id'))
            ->paginate(12)
            ->withQueryString();

        return view('vehicles.index', compact('vehicles', 'brands', 'activeBrand'));
    }

    public function show(Vehicle $vehicle)
    {
        abort_unless($vehicle->is_published, 404);

        $vehicle->load('images');

        $related = Vehicle::published()
            ->where('brand', $vehicle->brand)
            ->whereKeyNot($vehicle->id)
            ->limit(3)
            ->get();

        return view('vehicles.show', compact('vehicle', 'related'));
    }
}
