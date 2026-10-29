<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\PageSection;
use App\Models\Programme;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of active continuous foundation programmes.
     * Tech Day is separated as the foundation's annual flagship event under /events.
     */
    public function index(): View
    {
        $sections = PageSection::getPageSections('programmes');

        try {
            $programmes = Programme::active()
                ->whereNotIn('slug', ['innovation-and-tech-day', 'itd', 'ijero-tech-day'])
                ->ordered()
                ->get();
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
     * Ijero Tech Day redirects (301) to the flagship Tech Day event page.
     */
    public function show(string $slug): View|RedirectResponse
    {
        if (in_array($slug, ['innovation-and-tech-day', 'itd', 'ijero-tech-day'], true)) {
            return redirect()->route('events.show', 'ijero-tech-day-2026', 301);
        }

        $programme = Programme::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProgrammes = Programme::active()
            ->whereNotIn('slug', ['innovation-and-tech-day', 'itd'])
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
