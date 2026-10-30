<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryAlbumRequest;
use App\Http\Requests\Admin\StoreGalleryPhotoRequest;
use App\Http\Requests\Admin\UpdateGalleryAlbumRequest;
use App\Http\Requests\Admin\UpdateGalleryPhotoRequest;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use App\Services\ImageOptimizerService;
use App\Services\MediaMetadataExtractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        } elseif ($request->filled('cover_image')) {
            $validated['cover_image'] = $request->input('cover_image');
        } else {
            $validated['cover_image'] = null;
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

        $oldCoverToDelete = null;

        if ($request->hasFile('cover_image')) {
            $newCover = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/gallery/covers',
                1200,
                800
            );

            if ($album->cover_image && str_starts_with($album->cover_image, 'uploads/') && file_exists(public_path($album->cover_image))) {
                $oldCoverToDelete = public_path($album->cover_image);
            }

            $validated['cover_image'] = $newCover;
        } elseif ($request->filled('cover_image')) {
            $newCover = $request->input('cover_image');
            if ($album->cover_image && $album->cover_image !== $newCover && str_starts_with($album->cover_image, 'uploads/gallery/covers/') && file_exists(public_path($album->cover_image))) {
                $oldCoverToDelete = public_path($album->cover_image);
            }
            $validated['cover_image'] = $newCover;
        } elseif ($request->boolean('remove_cover_image') || ($request->has('cover_image') && $request->input('cover_image') === null)) {
            if ($album->cover_image && str_starts_with($album->cover_image, 'uploads/') && file_exists(public_path($album->cover_image))) {
                $oldCoverToDelete = public_path($album->cover_image);
            }
            $validated['cover_image'] = null;
        } else {
            unset($validated['cover_image']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $album->update($validated);

        if ($oldCoverToDelete && file_exists($oldCoverToDelete)) {
            @unlink($oldCoverToDelete);
        }

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
    public function uploadPhotos(StoreGalleryPhotoRequest $request, GalleryAlbum $album, MediaMetadataExtractor $extractor): RedirectResponse
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
        $inputYear = $request->input('year');

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                $failedCount++;

                continue;
            }

            try {
                // Extract raw EXIF metadata before GD optimization
                $rawPath = $file->getRealPath();
                $originalMeta = $rawPath ? $extractor->extract($rawPath) : [];

                $optimizedPath = $this->optimizer->optimize(
                    $file,
                    'uploads/gallery/photos',
                    1920,
                    1920,
                    82
                );

                $maxSortOrder++;
                $photoYear = $inputYear ?: ($originalMeta['taken_at']?->year ?? null);

                $photo = $album->photos()->create([
                    'image_path' => $optimizedPath,
                    'caption' => $caption,
                    'alt_text' => $altText,
                    'year' => $photoYear,
                    'sort_order' => $maxSortOrder,
                    'is_public' => true,
                ]);

                // Register directly into Central Media Library (media_items)
                $fullPath = public_path($optimizedPath);
                $fileSize = File::exists($fullPath) ? File::size($fullPath) : 0;
                $dimensions = @getimagesize($fullPath);
                $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $humanTitle = Str::title(str_replace(['-', '_'], ' ', $origName));

                MediaItem::create([
                    'filename' => basename($optimizedPath),
                    'file_path' => $optimizedPath,
                    'file_size' => $fileSize ?: ($originalMeta['file_size'] ?? 0),
                    'mime_type' => 'image/webp',
                    'width' => $dimensions[0] ?? ($originalMeta['width'] ?? null),
                    'height' => $dimensions[1] ?? ($originalMeta['height'] ?? null),
                    'taken_at' => $originalMeta['taken_at'] ?? null,
                    'color_space' => $originalMeta['color_space'] ?? 'sRGB',
                    'title' => $caption ?: $humanTitle,
                    'caption' => $caption,
                    'alt_text' => $altText,
                    'source_type' => 'gallery',
                    'year' => $photoYear,
                    'gallery_album_id' => $album->id,
                    'gallery_photo_id' => $photo->id,
                    'is_public' => true,
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

        if (array_key_exists('is_public', $validated)) {
            MediaItem::where('gallery_photo_id', $photo->id)->update(['is_public' => (bool) $photo->is_public]);
        }

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

        MediaItem::where('gallery_photo_id', $photo->id)->delete();
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
            MediaItem::where('gallery_photo_id', $photo->id)->delete();
            $photo->delete();
            $deletedCount++;
        }

        return redirect()->route('admin.gallery.photos', $album)
            ->with('success', "{$deletedCount} photo(s) removed successfully.");
    }
}
