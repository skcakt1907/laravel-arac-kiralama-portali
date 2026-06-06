<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VehicleAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $vehicles = Vehicle::query()
            ->when($q, fn ($query) => $query->where(fn ($w) =>
                $w->where('brand', 'like', "%{$q}%")->orWhere('model', 'like', "%{$q}%")))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.vehicles.index', compact('vehicles', 'q'));
    }

    public function create()
    {
        return view('admin.vehicles.form', ['vehicle' => new Vehicle()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['brand'] . '-' . $data['model']);
        $data = $this->handleImage($request, $data);

        Vehicle::create($data);

        return redirect()->route('admin.vehicles.index')->with('ok', 'Araç eklendi.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.form', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $this->validateData($request);
        $data = $this->handleImage($request, $data, $vehicle);

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('ok', 'Araç güncellendi.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->cover_image && ! str_starts_with($vehicle->cover_image, 'http')) {
            Storage::disk('public')->delete($vehicle->cover_image);
        }
        $vehicle->delete();

        return back()->with('ok', 'Araç silindi.');
    }

    public function toggle(Request $request, Vehicle $vehicle)
    {
        $field = $request->input('field');
        if (in_array($field, ['is_published', 'is_featured'], true)) {
            $vehicle->update([$field => ! $vehicle->$field]);
        }

        return back();
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'brand'        => ['required', 'string', 'max:80'],
            'model'        => ['required', 'string', 'max:120'],
            'year'         => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'mileage_km'   => ['nullable', 'integer', 'min:0'],
            'engine'       => ['nullable', 'string', 'max:160'],
            'fuel'         => ['nullable', 'string', 'max:60'],
            'transmission' => ['nullable', 'string', 'max:120'],
            'body_type'    => ['nullable', 'string', 'max:60'],
            'color'        => ['nullable', 'string', 'max:120'],
            'description'  => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured'  => ['nullable', 'boolean'],
            'image'        => ['nullable', 'image', 'max:8192'],
        ]);
    }

    private function handleImage(Request $request, array $data, ?Vehicle $vehicle = null): array
    {
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured']  = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            if ($vehicle && $vehicle->cover_image && ! str_starts_with($vehicle->cover_image, 'http')) {
                Storage::disk('public')->delete($vehicle->cover_image);
            }
            $data['cover_image'] = $request->file('image')->store('vehicles', 'public');
        }

        unset($data['image']);

        return $data;
    }

    private function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $orig = $slug;
        $i = 1;
        while (Vehicle::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $i++;
        }

        return $slug;
    }
}
