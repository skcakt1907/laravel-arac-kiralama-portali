<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RentalAdminController extends Controller
{
    public function index()
    {
        $rentals = Rental::orderByDesc('id')->paginate(20);

        return view('admin.rentals.index', compact('rentals'));
    }

    public function create()
    {
        return view('admin.rentals.form', ['rental' => new Rental(['currency' => 'AED', 'is_published' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['brand'] . '-' . $data['model']);
        $data = $this->handleImage($request, $data);

        Rental::create($data);

        return redirect()->route('admin.rentals.index')->with('ok', 'Kiralık araç eklendi.');
    }

    public function edit(Rental $rental)
    {
        return view('admin.rentals.form', compact('rental'));
    }

    public function update(Request $request, Rental $rental)
    {
        $data = $this->validateData($request);
        $data = $this->handleImage($request, $data, $rental);

        $rental->update($data);

        return redirect()->route('admin.rentals.index')->with('ok', 'Kiralık araç güncellendi.');
    }

    public function destroy(Rental $rental)
    {
        if ($rental->cover_image && ! str_starts_with($rental->cover_image, 'http')) {
            Storage::disk('public')->delete($rental->cover_image);
        }
        $rental->delete();

        return back()->with('ok', 'Kiralık araç silindi.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'brand'        => ['required', 'string', 'max:80'],
            'model'        => ['required', 'string', 'max:120'],
            'year'         => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'daily_price'  => ['required', 'numeric', 'min:0'],
            'currency'     => ['required', 'string', 'size:3'],
            'engine'       => ['nullable', 'string', 'max:160'],
            'transmission' => ['nullable', 'string', 'max:120'],
            'seats'        => ['nullable', 'integer', 'min:1', 'max:50'],
            'body_type'    => ['nullable', 'string', 'max:60'],
            'description'  => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured'  => ['nullable', 'boolean'],
            'image'        => ['nullable', 'image', 'max:8192'],
        ]);
    }

    private function handleImage(Request $request, array $data, ?Rental $rental = null): array
    {
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured']  = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            if ($rental && $rental->cover_image && ! str_starts_with($rental->cover_image, 'http')) {
                Storage::disk('public')->delete($rental->cover_image);
            }
            $data['cover_image'] = $request->file('image')->store('rentals', 'public');
        }

        unset($data['image']);

        return $data;
    }

    private function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $orig = $slug;
        $i = 1;
        while (Rental::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $i++;
        }

        return $slug;
    }
}
