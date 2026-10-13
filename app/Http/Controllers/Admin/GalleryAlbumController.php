<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryAlbumRequest;
use App\Http\Requests\Admin\StoreGalleryPhotoRequest;
use App\Http\Requests\Admin\UpdateGalleryAlbumRequest;
use App\Http\Requests\Admin\UpdateGalleryPhotoRequest;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\ImageOptimizerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GalleryAlbumController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $optimizer
    ) {}

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
            $validated['cover_image'] = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/gallery/covers',
                1200,
                800
            );
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
            if ($album->cover_image && str_starts_with($album->cover_image, 'uploads/') && file_exists(public_path($album->cover_image))) {
                @unlink(public_path($album->cover_image));
            }

            $validated['cover_image'] = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/gallery/covers',
                1200,
                800
            );
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
     * Reorder albums sort_order.
     */
    public function reorderAlbums(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'album_ids' => ['required', 'array', 'min:1'],
            'album_ids.*' => ['integer', 'exists:gallery_albums,id'],
        ]);

        foreach ($validated['album_ids'] as $index => $albumId) {
            GalleryAlbum::where('id', $albumId)->update(['sort_order' => $index + 1]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Albums reordered successfully.']);
        }

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Albums reordered successfully.');
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
        $uploadedCount = 0;
        $failedCount = 0;
        $maxSortOrder = (int) $album->photos()->max('sort_order');

        $files = [];
        if ($request->hasFile('photos')) {
            $files = $request->file('photos');
        } elseif ($request->hasFile('photo')) {
            $files = [$request->file('photo')];
        }

        $caption = $request->input('caption');
        $altText = $request->input('alt_text') ?: $album->title;
        $year = $request->input('year');

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                $failedCount++;

                continue;
            }

            try {
                $optimizedPath = $this->optimizer->optimize(
                    $file,
                    'uploads/gallery/photos',
                    1920,
                    1920,
                    82
                );

                $maxSortOrder++;
                $album->photos()->create([
                    'image_path' => $optimizedPath,
                    'caption' => $caption,
                    'alt_text' => $altText,
                    'year' => $year,
                    'sort_order' => $maxSortOrder,
                ]);

                $uploadedCount++;
            } catch (\Throwable $e) {
                Log::error('Failed to upload gallery photo: '.$e->getMessage());
                $failedCount++;
            }
        }

        if ($uploadedCount === 0 && $failedCount > 0) {
            return redirect()->route('admin.gallery.photos', $album)
                ->with('error', 'Failed to upload selected photo(s). Please verify the file formats and try again.');
        }

        $message = "{$uploadedCount} photo(s) uploaded successfully.";
        if ($failedCount > 0) {
            $message .= " ({$failedCount} photo(s) failed to process).";
        }

        return redirect()->route('admin.gallery.photos', $album)
            ->with('success', $message);
    }

    /**
     * Update an individual photo metadata in a gallery album.
     */
    public function updatePhoto(UpdateGalleryPhotoRequest $request, GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        if ($photo->gallery_album_id !== $album->id) {
            abort(404);
        }

        $validated = $request->validated();
        if (isset($validated['sort_order'])) {
            $validated['sort_order'] = (int) $validated['sort_order'];
        }

        $photo->update($validated);

        return redirect()->route('admin.gallery.photos', $album)
            ->with('success', 'Photo metadata updated successfully.');
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

    /**
     * Delete multiple photos in bulk from a gallery album.
     */
    public function bulkDestroyPhotos(Request $request, GalleryAlbum $album): RedirectResponse
    {
        $validated = $request->validate([
            'photo_ids' => ['required', 'array', 'min:1'],
            'photo_ids.*' => ['integer'],
        ]);

        $photos = $album->photos()->whereIn('id', $validated['photo_ids'])->get();
        $deletedCount = 0;

        foreach ($photos as $photo) {
            if ($photo->image_path && str_starts_with($photo->image_path, 'uploads/') && file_exists(public_path($photo->image_path))) {
                @unlink(public_path($photo->image_path));
            }
            $photo->delete();
            $deletedCount++;
        }

        return redirect()->route('admin.gallery.photos', $album)
            ->with('success', "{$deletedCount} photo(s) removed successfully.");
    }
}
