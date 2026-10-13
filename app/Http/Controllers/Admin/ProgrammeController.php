<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProgrammeRequest;
use App\Http\Requests\Admin\UpdateProgrammeRequest;
use App\Models\Programme;
use App\Services\ImageOptimizerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ProgrammeController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $optimizer
    ) {}

    /**
     * Display a listing of foundation programmes.
     */
    public function index(): View
    {
        $programmes = Programme::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return view('admin.programmes.index', compact('programmes'));
    }

    /**
     * Show the form for creating a new programme.
     */
    public function create(): View
    {
        return view('admin.programmes.create');
    }

    /**
     * Store a newly created programme in storage.
     */
    public function store(StoreProgrammeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $slug = $validated['slug'] ?? null;
        if (! $slug) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Programme::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/programmes',
                1600,
                1200
            );
        } elseif ($request->filled('cover_image')) {
            $validated['cover_image'] = $request->input('cover_image');
        } else {
            $validated['cover_image'] = null;
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        Programme::create($validated);

        return redirect()->route('admin.programmes.index')
            ->with('success', 'Programme created successfully.');
    }

    /**
     * Show the form for editing the specified programme.
     */
    public function edit(Programme $programme): View
    {
        return view('admin.programmes.edit', compact('programme'));
    }

    /**
     * Update the specified programme in storage.
     */
    public function update(UpdateProgrammeRequest $request, Programme $programme): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('cover_image')) {
            if ($programme->cover_image && str_starts_with($programme->cover_image, 'uploads/programmes/') && file_exists(public_path($programme->cover_image))) {
                @unlink(public_path($programme->cover_image));
            }

            $validated['cover_image'] = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/programmes',
                1600,
                1200
            );
        } elseif ($request->filled('cover_image')) {
            $newCover = $request->input('cover_image');
            if ($programme->cover_image && $programme->cover_image !== $newCover && str_starts_with($programme->cover_image, 'uploads/programmes/') && file_exists(public_path($programme->cover_image))) {
                @unlink(public_path($programme->cover_image));
            }
            $validated['cover_image'] = $newCover;
        } elseif ($request->boolean('remove_cover_image') || ($request->has('cover_image') && $request->input('cover_image') === null)) {
            if ($programme->cover_image && str_starts_with($programme->cover_image, 'uploads/programmes/') && file_exists(public_path($programme->cover_image))) {
                @unlink(public_path($programme->cover_image));
            }
            $validated['cover_image'] = null;
        } else {
            unset($validated['cover_image']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $programme->update($validated);

        return redirect()->route('admin.programmes.index')
            ->with('success', 'Programme updated successfully.');
    }

    /**
     * Remove the specified programme from storage.
     */
    public function destroy(Programme $programme): RedirectResponse
    {
        if ($programme->cover_image && str_starts_with($programme->cover_image, 'uploads/programmes/') && file_exists(public_path($programme->cover_image))) {
            @unlink(public_path($programme->cover_image));
        }

        $programme->delete();

        return redirect()->route('admin.programmes.index')
            ->with('success', 'Programme deleted successfully.');
    }

    /**
     * Toggle the active status of a programme.
     */
    public function toggleActive(Programme $programme): RedirectResponse
    {
        $programme->update([
            'is_active' => ! $programme->is_active,
        ]);

        $status = $programme->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.programmes.index')
            ->with('success', "Programme '{$programme->title}' was {$status}.");
    }
}
