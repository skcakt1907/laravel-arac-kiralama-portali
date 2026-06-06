<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\PartCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $parts = Part::query()
            ->with('category')
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.parts.index', compact('parts', 'q'));
    }

    public function create()
    {
        return view('admin.parts.form', [
            'part'       => new Part(['currency' => 'USD', 'stock' => 0, 'is_published' => true]),
            'categories' => PartCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data = $this->handleImage($request, $data);

        Part::create($data);

        return redirect()->route('admin.parts.index')->with('ok', 'Parça eklendi.');
    }

    public function edit(Part $part)
    {
        return view('admin.parts.form', [
            'part'       => $part,
            'categories' => PartCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Part $part)
    {
        $data = $this->validateData($request);
        $data = $this->handleImage($request, $data, $part);

        $part->update($data);

        return redirect()->route('admin.parts.index')->with('ok', 'Parça güncellendi.');
    }

    public function destroy(Part $part)
    {
        if ($part->cover_image && ! str_starts_with($part->cover_image, 'http')) {
            Storage::disk('public')->delete($part->cover_image);
        }
        $part->delete();

        return back()->with('ok', 'Parça silindi.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'part_category_id' => ['nullable', 'exists:part_categories,id'],
            'name'             => ['required', 'string', 'max:160'],
            'sku'              => ['nullable', 'string', 'max:60'],
            'compatible'       => ['nullable', 'string', 'max:160'],
            'price'            => ['required', 'numeric', 'min:0'],
            'currency'         => ['required', 'string', 'size:3'],
            'stock'            => ['required', 'integer', 'min:0'],
            'short_desc'       => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'is_published'     => ['nullable', 'boolean'],
            'is_featured'      => ['nullable', 'boolean'],
            'image'            => ['nullable', 'image', 'max:8192'],
        ]);
    }

    private function handleImage(Request $request, array $data, ?Part $part = null): array
    {
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured']  = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            if ($part && $part->cover_image && ! str_starts_with($part->cover_image, 'http')) {
                Storage::disk('public')->delete($part->cover_image);
            }
            $data['cover_image'] = $request->file('image')->store('parts', 'public');
        }

        unset($data['image']);

        return $data;
    }

    private function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $orig = $slug;
        $i = 1;
        while (Part::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $i++;
        }

        return $slug;
    }
}
