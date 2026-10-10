<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{
    /**
     * Display a paginated listing of published news articles.
     */
    public function index(): View
    {
        $posts = Post::published()
            ->with('programme')
            ->recent()
            ->paginate(9);

        return view('news.index', compact('posts'));
    }

    /**
     * Display the specified published news article.
     */
    public function show(string $slug): View
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with('programme')
            ->firstOrFail();

        // Query related articles (same programme first, then newest published)
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->programme_id, fn ($q) => $q->where('programme_id', $post->programme_id))
            ->recent()
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $filler = Post::published()
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->recent()
                ->take(3 - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->concat($filler);
        }

        return view('news.show', compact('post', 'relatedPosts'));
    }
}
