<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProgrammeRequest;
use App\Http\Requests\Admin\UpdateProgrammeRequest;
use App\Models\GalleryAlbum;
use App\Models\MediaItem;
use App\Models\Programme;
use App\Services\ImageOptimizerService;
use App\Services\MediaMetadataExtractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $albums = GalleryAlbum::active()->orderBy('title', 'asc')->get();

        return view('admin.programmes.create', compact('albums'));
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

        $validated['gallery_photos'] = $this->processGalleryPhotos($request, $validated['title']);
        $validated['gallery_album_id'] = $request->filled('gallery_album_id') ? (int) $request->input('gallery_album_id') : null;
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
        $albums = GalleryAlbum::active()->orderBy('title', 'asc')->get();

        return view('admin.programmes.edit', compact('programme', 'albums'));
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

        $oldImageToDelete = null;

        if ($request->hasFile('cover_image')) {
            $newCover = $this->optimizer->optimize(
                $request->file('cover_image'),
                'uploads/programmes',
                1600,
                1200
            );

            if ($programme->cover_image && str_starts_with($programme->cover_image, 'uploads/programmes/') && file_exists(public_path($programme->cover_image))) {
                $oldImageToDelete = public_path($programme->cover_image);
            }

            $validated['cover_image'] = $newCover;
        } elseif ($request->filled('cover_image')) {
            $newCover = $request->input('cover_image');
            if ($programme->cover_image && $programme->cover_image !== $newCover && str_starts_with($programme->cover_image, 'uploads/programmes/') && file_exists(public_path($programme->cover_image))) {
                $oldImageToDelete = public_path($programme->cover_image);
            }
            $validated['cover_image'] = $newCover;
        } elseif ($request->boolean('remove_cover_image') || ($request->has('cover_image') && $request->input('cover_image') === null)) {
            if ($programme->cover_image && str_starts_with($programme->cover_image, 'uploads/programmes/') && file_exists(public_path($programme->cover_image))) {
                $oldImageToDelete = public_path($programme->cover_image);
            }
            $validated['cover_image'] = null;
        } else {
            unset($validated['cover_image']);
        }

        $validated['gallery_photos'] = $this->processGalleryPhotos($request, $validated['title'], $programme->gallery_photos);
        $validated['gallery_album_id'] = $request->filled('gallery_album_id') ? (int) $request->input('gallery_album_id') : null;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $programme->update($validated);

        if ($oldImageToDelete && file_exists($oldImageToDelete)) {
            @unlink($oldImageToDelete);
        }

        return redirect()->route('admin.programmes.index')
            ->with('success', 'Programme updated successfully.');
    }

    /**
     * Process existing and newly uploaded gallery photos.
     *
     * @param  array<mixed>|null  $currentPhotos
     * @return array<int, array{path: string, caption: ?string, alt_text: ?string, media_item_id: ?int}>
     */
    protected function processGalleryPhotos(Request $request, string $title, ?array $currentPhotos = null): array
    {
        $photos = [];

        if ($request->has('gallery_photos')) {
            $input = $request->input('gallery_photos');
            if (is_string($input)) {
                $decoded = json_decode($input, true);
                $photos = is_array($decoded) ? $decoded : [];
            } elseif (is_array($input)) {
                $photos = $input;
            }
        } elseif ($currentPhotos !== null) {
            $photos = $currentPhotos;
        }

        if ($request->hasFile('new_gallery_photos')) {
            $extractor = app(MediaMetadataExtractor::class);
            foreach ($request->file('new_gallery_photos') as $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                $rawPath = $file->getRealPath();
                $originalMeta = $rawPath ? $extractor->extract($rawPath) : [];

                $optimizedPath = $this->optimizer->optimize(
                    $file,
                    'uploads/programmes',
                    1920,
                    1920,
                    82
                );

                $fullPath = public_path($optimizedPath);
                $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
                $dimensions = @getimagesize($fullPath);
                $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $humanTitle = Str::title(str_replace(['-', '_'], ' ', $origName));

                $mediaItem = MediaItem::create([
                    'filename' => basename($optimizedPath),
                    'file_path' => $optimizedPath,
                    'file_size' => $fileSize ?: ($originalMeta['file_size'] ?? 0),
                    'mime_type' => 'image/webp',
                    'width' => $dimensions[0] ?? ($originalMeta['width'] ?? null),
                    'height' => $dimensions[1] ?? ($originalMeta['height'] ?? null),
                    'taken_at' => $originalMeta['taken_at'] ?? null,
                    'color_space' => $originalMeta['color_space'] ?? 'sRGB',
                    'title' => $humanTitle,
                    'alt_text' => $title,
                    'source_type' => 'programme',
                    'year' => $originalMeta['taken_at']?->year ?? date('Y'),
                    'is_public' => true,
                ]);

                $photos[] = [
                    'path' => $optimizedPath,
                    'caption' => '',
                    'alt_text' => $title,
                    'media_item_id' => $mediaItem->id,
                ];
            }
        }

        return array_values($photos);
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
