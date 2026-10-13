<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;
use App\Models\Programme;
use App\Services\ImageOptimizerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $optimizer
    ) {}

    /**
     * Display a listing of news articles in admin panel.
     */
    public function index(): View
    {
        $posts = Post::with('programme')
            ->latest('id')
            ->paginate(15);

        return view('admin.news.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        $programmes = Programme::orderBy('title')->get();

        return view('admin.news.create', compact('programmes'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $slug = $validated['slug'] ?? null;
        if (! $slug) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Post::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/news',
                1600,
                1200
            );
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article created successfully.');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $news): View
    {
        $post = $news;
        $programmes = Programme::orderBy('title')->get();

        return view('admin.news.edit', compact('post', 'programmes'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(UpdatePostRequest $request, Post $news): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/news',
                1600,
                1200
            );

            if ($news->cover_image && str_starts_with($news->cover_image, 'uploads/news/') && file_exists(public_path($news->cover_image)) && $news->cover_image !== $validated['cover_image']) {
                @unlink(public_path($news->cover_image));
            }
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = $news->published_at ?? now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article updated successfully.');
    }

    /**
     * Toggle the publish status of the specified post.
     */
    public function togglePublish(Post $news): RedirectResponse
    {
        if ($news->status === 'published') {
            $news->update(['status' => 'draft']);
            $message = 'Post set to draft status.';
        } else {
            $news->update([
                'status' => 'published',
                'published_at' => $news->published_at ?? now(),
            ]);
            $message = 'Post published successfully.';
        }

        return back()->with('success', $message);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $news): RedirectResponse
    {
        if ($news->cover_image && str_starts_with($news->cover_image, 'uploads/news/') && file_exists(public_path($news->cover_image))) {
            @unlink(public_path($news->cover_image));
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News article deleted successfully.');
    }
}
