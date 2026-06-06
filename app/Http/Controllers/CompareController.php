<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    private const KEY = 'compare';
    private const MAX = 3;

    public function index()
    {
        $ids = session(self::KEY, []);
        $vehicles = Vehicle::published()->whereIn('id', $ids)->get();

        return view('vehicles.compare', compact('vehicles'));
    }

    public function add(Request $request, Vehicle $vehicle)
    {
        $ids = session(self::KEY, []);

        if (! in_array($vehicle->id, $ids, true)) {
            if (count($ids) >= self::MAX) {
                return back()->with('compare_msg', __('site.compare_full'));
            }
            $ids[] = $vehicle->id;
            session([self::KEY => $ids]);
        }

        return back()->with('compare_msg', __('site.compare_added'));
    }

    public function remove(Vehicle $vehicle)
    {
        $ids = array_values(array_diff(session(self::KEY, []), [$vehicle->id]));
        session([self::KEY => $ids]);

        return back();
    }

    public function clear()
    {
        session()->forget(self::KEY);

        return redirect()->route('compare.index');
    }
}
