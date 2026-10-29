<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display the public gallery index with interactive collections and photo masonry.
     */
    public function index(Request $request): View
    {
        $albums = GalleryAlbum::active()
            ->public()
            ->ordered()
            ->with(['photos' => function ($query) {
                $query->where('is_public', true)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc');
            }])
            ->get();

        $activeSlug = $request->query('collection') ?: $request->query('album');

        return view('gallery.index', compact('albums', 'activeSlug'));
    }

    /**
     * Display a specific gallery album / collection.
     */
    public function album(string $slug): View
    {
        $selectedAlbum = GalleryAlbum::active()
            ->public()
            ->where('slug', $slug)
            ->with(['photos' => function ($query) {
                $query->where('is_public', true)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc');
            }])
            ->firstOrFail();

        $albums = GalleryAlbum::active()
            ->public()
            ->ordered()
            ->with(['photos' => function ($query) {
                $query->where('is_public', true)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc');
            }])
            ->get();

        $activeSlug = $selectedAlbum->slug;

        return view('gallery.index', compact('albums', 'activeSlug', 'selectedAlbum'));
    }
}
