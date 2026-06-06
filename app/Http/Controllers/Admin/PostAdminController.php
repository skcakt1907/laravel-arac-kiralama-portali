<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostAdminController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => new Post(['is_published' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['title_tr'] ?: $data['title_en'] ?: 'yazi');
        $data = $this->handleExtras($request, $data);

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('ok', 'Yazı eklendi.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validateData($request);
        $data = $this->handleExtras($request, $data, $post);

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('ok', 'Yazı güncellendi.');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image && ! str_starts_with($post->cover_image, 'http')) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->delete();

        return back()->with('ok', 'Yazı silindi.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title_tr'   => ['nullable', 'string', 'max:255'],
            'title_en'   => ['nullable', 'string', 'max:255'],
            'title_ar'   => ['nullable', 'string', 'max:255'],
            'excerpt_tr' => ['nullable', 'string', 'max:500'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'excerpt_ar' => ['nullable', 'string', 'max:500'],
            'body_tr'    => ['nullable', 'string'],
            'body_en'    => ['nullable', 'string'],
            'body_ar'    => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'image'      => ['nullable', 'image', 'max:8192'],
        ]);
    }

    private function handleExtras(Request $request, array $data, ?Post $post = null): array
    {
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['published_at'] ?? now();

        if ($request->hasFile('image')) {
            if ($post && $post->cover_image && ! str_starts_with($post->cover_image, 'http')) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('image')->store('posts', 'public');
        }

        unset($data['image']);

        return $data;
    }

    private function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $orig = $slug ?: 'yazi';
        $slug = $orig;
        $i = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $i++;
        }

        return $slug;
    }
}
