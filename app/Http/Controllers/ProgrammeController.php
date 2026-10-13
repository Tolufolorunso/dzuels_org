<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\PageSection;
use App\Models\Programme;
use Illuminate\Contracts\View\View;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of active foundation programmes.
     */
    public function index(): View
    {
        $sections = PageSection::getPageSections('programmes');

        try {
            $programmes = Programme::active()->ordered()->get();
            $featured = $programmes->where('is_featured', true)->first();
        } catch (\Throwable) {
            $programmes = collect();
            $featured = null;
        }

        return view('programmes.index', [
            'sections' => $sections,
            'programmes' => $programmes,
            'featured' => $featured,
        ]);
    }

    /**
     * Display the specified programme by its unique slug.
     */
    public function show(string $slug): View
    {
        $programme = Programme::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProgrammes = Programme::active()
            ->where('id', '!=', $programme->id)
            ->ordered()
            ->take(3)
            ->get();

        $galleryAlbum = GalleryAlbum::active()
            ->where(function ($query) use ($programme) {
                if ($programme->slug === 'famoyinka-memorial-library') {
                    $query->where('slug', 'famoyinka-library');
                } else {
                    $query->where('slug', $programme->slug);
                }
            })
            ->with(['photos' => function ($q) {
                $q->orderBy('sort_order', 'asc')->take(4);
            }])
            ->first();

        return view('programmes.show', [
            'programme' => $programme,
            'relatedProgrammes' => $relatedProgrammes,
            'galleryAlbum' => $galleryAlbum,
        ]);
    }
}
