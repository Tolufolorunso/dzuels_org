<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use Illuminate\Contracts\View\View;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of active foundation programmes.
     */
    public function index(): View
    {
        try {
            $programmes = Programme::active()->ordered()->get();
            $featured = $programmes->where('is_featured', true)->first();
        } catch (\Throwable) {
            $programmes = collect();
            $featured = null;
        }

        return view('programmes.index', [
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

        return view('programmes.show', [
            'programme' => $programme,
            'relatedProgrammes' => $relatedProgrammes,
        ]);
    }
}
