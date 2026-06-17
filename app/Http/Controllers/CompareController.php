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
        // session sırasını koru
        $vehicles = Vehicle::published()->whereIn('id', $ids)->get()
            ->sortBy(fn ($v) => array_search($v->id, $ids))->values();

        // Dropdown'lar için tüm araçlar (marka/model)
        $allVehicles = Vehicle::published()
            ->orderBy('brand')->orderBy('model')
            ->get(['id', 'brand', 'model']);

        return view('vehicles.compare', [
            'vehicles'    => $vehicles,
            'allVehicles' => $allVehicles,
            'max'         => self::MAX,
        ]);
    }

    /** Dropdown'dan yeni araç ekle (sütun ekleme) */
    public function store(Request $request)
    {
        $id  = (int) $request->input('vehicle_id');
        $ids = session(self::KEY, []);

        if ($id && ! in_array($id, $ids, true) && Vehicle::published()->whereKey($id)->exists()) {
            if (count($ids) >= self::MAX) {
                return back()->with('compare_msg', __('site.compare_full'));
            }
            $ids[] = $id;
            session([self::KEY => $ids]);
        }

        return back();
    }

    /** Bir sütundaki aracı dropdown'dan seçilen araçla değiştir */
    public function swap(Request $request, Vehicle $vehicle)
    {
        $newId = (int) $request->input('new_id');
        $ids   = session(self::KEY, []);
        $pos   = array_search($vehicle->id, $ids, true);

        if ($pos !== false && $newId && Vehicle::published()->whereKey($newId)->exists()) {
            $other = array_search($newId, $ids, true);
            if ($other === false) {
                $ids[$pos] = $newId;                    // boş bir araçla değiştir
            } else {
                $ids[$pos] = $newId;                    // listede zaten varsa yerlerini değiştir
                $ids[$other] = $vehicle->id;
            }
            session([self::KEY => array_values($ids)]);
        }

        return back();
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
