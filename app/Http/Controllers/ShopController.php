<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = PartCategory::orderBy('sort_order')->orderBy('name')->get();

        $activeCategory = $request->query('category');

        $parts = Part::published()
            ->with('category')
            ->when($activeCategory, function ($q) use ($activeCategory) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $activeCategory));
            })
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', compact('parts', 'categories', 'activeCategory'));
    }

    public function show(Part $part)
    {
        abort_unless($part->is_published, 404);

        $related = Part::published()
            ->where('part_category_id', $part->part_category_id)
            ->whereKeyNot($part->id)
            ->limit(3)
            ->get();

        return view('shop.show', compact('part', 'related'));
    }
}
