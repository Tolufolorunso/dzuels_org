<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProgrammeRequest;
use App\Http\Requests\Admin\UpdateProgrammeRequest;
use App\Models\Programme;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ProgrammeController extends Controller
{
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
            $file = $request->file('cover_image');
            $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $destination = public_path('uploads/programmes');

            if (! file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $validated['cover_image'] = 'uploads/programmes/'.$filename;
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
            $file = $request->file('cover_image');
            $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $destination = public_path('uploads/programmes');

            if (! file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $validated['cover_image'] = 'uploads/programmes/'.$filename;
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
