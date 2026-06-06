<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        $recent = Post::published()
            ->whereKeyNot($post->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'recent'));
    }
}
