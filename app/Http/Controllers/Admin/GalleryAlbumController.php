<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryAlbumRequest;
use App\Http\Requests\Admin\StoreGalleryPhotoRequest;
use App\Http\Requests\Admin\UpdateGalleryAlbumRequest;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class GalleryAlbumController extends Controller
{
    /**
     * Display a listing of gallery albums.
     */
    public function index(): View
    {
        $albums = GalleryAlbum::withCount('photos')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.gallery.index', compact('albums'));
    }

    /**
     * Show the form for creating a new gallery album.
     */
    public function create(): View
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created gallery album in storage.
     */
    public function store(StoreGalleryAlbumRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $slug = $validated['slug'] ?? null;
        if (! $slug) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (GalleryAlbum::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $destination = public_path('uploads/gallery/covers');

            if (! file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $validated['cover_image'] = 'uploads/gallery/covers/'.$filename;
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $album = GalleryAlbum::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', "Album '{$album->title}' created successfully.");
    }

    /**
     * Show the form for editing the specified gallery album.
     */
    public function edit(GalleryAlbum $album): View
    {
        return view('admin.gallery.edit', compact('album'));
    }

    /**
     * Update the specified gallery album in storage.
     */
    public function update(UpdateGalleryAlbumRequest $request, GalleryAlbum $album): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $destination = public_path('uploads/gallery/covers');

            if (! file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);

            if ($album->cover_image && str_starts_with($album->cover_image, 'uploads/') && file_exists(public_path($album->cover_image))) {
                @unlink(public_path($album->cover_image));
            }

            $validated['cover_image'] = 'uploads/gallery/covers/'.$filename;
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $album->update($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', "Album '{$album->title}' updated successfully.");
    }

    /**
     * Remove the specified gallery album from storage.
     */
    public function destroy(GalleryAlbum $album): RedirectResponse
    {
        if ($album->cover_image && str_starts_with($album->cover_image, 'uploads/') && file_exists(public_path($album->cover_image))) {
            @unlink(public_path($album->cover_image));
        }

        foreach ($album->photos as $photo) {
            if ($photo->image_path && str_starts_with($photo->image_path, 'uploads/') && file_exists(public_path($photo->image_path))) {
                @unlink(public_path($photo->image_path));
            }
        }

        $album->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Album and associated photos deleted successfully.');
    }

    /**
     * Toggle the active visibility status of an album.
     */
    public function toggleActive(GalleryAlbum $album): RedirectResponse
    {
        $album->update([
            'is_active' => ! $album->is_active,
        ]);

        $status = $album->is_active ? 'published' : 'hidden';

        return redirect()->route('admin.gallery.index')
            ->with('success', "Album '{$album->title}' is now {$status}.");
    }

    /**
     * View photos in a gallery album.
     */
    public function photos(GalleryAlbum $album): View
    {
        $photos = $album->photos()
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.gallery.photos', compact('album', 'photos'));
    }

    /**
     * Upload photos to a gallery album.
     */
    public function uploadPhotos(StoreGalleryPhotoRequest $request, GalleryAlbum $album): RedirectResponse
    {
        $destination = public_path('uploads/gallery/photos');
        if (! file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $uploadedCount = 0;
        $maxSortOrder = (int) $album->photos()->max('sort_order');

        $files = [];
        if ($request->hasFile('photos')) {
            $files = $request->file('photos');
        } elseif ($request->hasFile('photo')) {
            $files = [$request->file('photo')];
        }

        $caption = $request->input('caption');
        $altText = $request->input('alt_text') ?: $album->title;

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                $file->move($destination, $filename);

                $maxSortOrder++;
                $album->photos()->create([
                    'image_path' => 'uploads/gallery/photos/'.$filename,
                    'caption' => $caption,
                    'alt_text' => $altText,
                    'sort_order' => $maxSortOrder,
                ]);

                $uploadedCount++;
            }
        }

        return redirect()->route('admin.gallery.photos', $album)
            ->with('success', "{$uploadedCount} photo(s) added successfully.");
    }

    /**
     * Delete an individual photo from a gallery album.
     */
    public function destroyPhoto(GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        if ($photo->gallery_album_id !== $album->id) {
            abort(404);
        }

        if ($photo->image_path && str_starts_with($photo->image_path, 'uploads/') && file_exists(public_path($photo->image_path))) {
            @unlink(public_path($photo->image_path));
        }

        $photo->delete();

        return redirect()->route('admin.gallery.photos', $album)
            ->with('success', 'Photo removed successfully.');
    }
}
