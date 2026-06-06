<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = PartCategory::withCount('parts')->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['sort_order'] ??= 0;

        PartCategory::create($data);

        return back()->with('ok', 'Kategori eklendi.');
    }

    public function update(Request $request, PartCategory $category)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $category->update([
            'name'       => $data['name'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('ok', 'Kategori güncellendi.');
    }

    public function destroy(PartCategory $category)
    {
        $category->delete();

        return back()->with('ok', 'Kategori silindi.');
    }

    private function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $orig = $slug;
        $i = 1;
        while (PartCategory::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $i++;
        }

        return $slug;
    }
}
