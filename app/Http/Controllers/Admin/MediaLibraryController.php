<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\MediaItem;
use App\Services\ImageOptimizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    /**
     * Display the Central Media Hub index page.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $dateFilter = $request->query('date_filter', 'all');
        $category = $request->query('category', 'all');
        $yearFilter = $request->query('year', 'all');
        $visibility = $request->query('visibility', 'all');
        $sort = $request->query('sort', 'newest');

        $query = MediaItem::query()
            ->search($search)
            ->byDateFilter($dateFilter)
            ->bySourceType($category)
            ->byYear($yearFilter)
            ->byVisibility($visibility)
            ->ordered($sort);

        $mediaItems = $query->paginate(24)->withQueryString();

        // Calculate library metrics
        $totalCount = MediaItem::count();
        $totalBytes = MediaItem::sum('file_size');
        $webpCount = MediaItem::where('mime_type', 'image/webp')->count();
        $webpRate = $totalCount > 0 ? (int) round(($webpCount / $totalCount) * 100) : 100;
        $publicCount = MediaItem::where('is_public', true)->count();
        $archiveCount = MediaItem::where('is_public', false)->count();

        // Retrieve active website paths for usage badges
        $activeSitePaths = static::getActiveSitePaths();

        $totalSizeFormatted = '0 KB';
        if ($totalBytes >= 1048576) {
            $totalSizeFormatted = number_format($totalBytes / 1048576, 1).' MB';
        } elseif ($totalBytes > 0) {
            $totalSizeFormatted = number_format($totalBytes / 1024, 0).' KB';
        }

        // Retrieve dynamic collections from GalleryAlbum
        $collections = GalleryAlbum::ordered()->get();

        // Retrieve distinct years present in the media library with defaults
        $dbYears = MediaItem::whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year')->all();
        $availableYears = array_values(array_unique(array_merge([2026, 2025, 2024], $dbYears)));
        rsort($availableYears);

        return view('admin.media.index', [
            'mediaItems' => $mediaItems,
            'collections' => $collections,
            'availableYears' => $availableYears,
            'totalCount' => $totalCount,
            'totalSizeFormatted' => $totalSizeFormatted,
            'webpRate' => $webpRate,
            'publicCount' => $publicCount,
            'archiveCount' => $archiveCount,
            'activeSitePaths' => $activeSitePaths,
            'currentSearch' => $search,
            'currentDateFilter' => $dateFilter,
            'currentCategory' => $category,
            'currentYear' => $yearFilter,
            'currentVisibility' => $visibility,
            'currentSort' => $sort,
            'effectiveMaxUploadSize' => ini_get('upload_max_filesize') ?: '16M',
        ]);
    }

    /**
     * Create a new media collection / gallery album on the fly from the dashboard.
     */
    public function storeCollection(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'kicker' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (GalleryAlbum::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $maxOrder = GalleryAlbum::max('sort_order') ?? 0;

        $album = GalleryAlbum::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'kicker' => $validated['kicker'] ?? 'Foundation Programme',
            'description' => $validated['description'] ?? null,
            'is_active' => true,
            'is_public' => $request->boolean('is_public', true),
            'sort_order' => $maxOrder + 10,
            'cover_image' => $validated['cover_image'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Collection '{$album->title}' created successfully.",
                'collection' => $album,
            ], 201);
        }

        return redirect()->route('admin.media.index', ['category' => $album->slug])
            ->with('success', "Collection '{$album->title}' created successfully.");
    }

    /**
     * Handle single or batch drag-and-drop file uploads into the library.
     */
    public function upload(Request $request, ImageOptimizerService $optimizer): JsonResponse
    {
        if ($failureMessage = $this->getUploadFailureMessage($request)) {
            return response()->json([
                'success' => false,
                'message' => $failureMessage,
                'errors' => [
                    'image' => [$failureMessage],
                ],
            ], 422);
        }

        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:15360'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:15360'],
            'gallery_album_id' => ['nullable', 'integer', 'exists:gallery_albums,id'],
            'collection_id' => ['nullable', 'integer', 'exists:gallery_albums,id'],
            'source_type' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ], [
            'image.image' => 'The uploaded file must be an image (JPEG, PNG, WebP, or GIF).',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, webp, gif.',
            'image.max' => 'The image must not exceed 15 MB.',
            'images.*.image' => 'Each uploaded file must be an image (JPEG, PNG, WebP, or GIF).',
            'images.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg, webp, gif.',
            'images.*.max' => 'Each image must not exceed 15 MB.',
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images');
        } elseif ($request->hasFile('image')) {
            $uploadedFiles = [$request->file('image')];
        }

        if (empty($uploadedFiles)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid image file was uploaded.',
                'errors' => [
                    'image' => ['No valid image file was uploaded.'],
                ],
            ], 422);
        }

        $albumId = $request->input('gallery_album_id') ?: $request->input('collection_id');
        $sourceType = $albumId ? 'gallery' : ($request->input('source_type') ?: 'general');
        $inputYear = $request->filled('year') ? (int) $request->input('year') : null;

        $createdItems = [];

        foreach ($uploadedFiles as $file) {
            $optimizedPath = $optimizer->optimize(
                $file,
                'uploads/media',
                1920,
                1920,
                82
            );

            $fullPath = public_path($optimizedPath);
            $fileSize = File::exists($fullPath) ? File::size($fullPath) : 0;
            $dimensions = @getimagesize($fullPath);
            $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $humanTitle = Str::title(str_replace(['-', '_'], ' ', $origName));
            $extension = strtolower(pathinfo($optimizedPath, PATHINFO_EXTENSION));
            $mimeType = match ($extension) {
                'webp' => 'image/webp',
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'svg' => 'image/svg+xml',
                'gif' => 'image/gif',
                default => ($dimensions['mime'] ?? (File::exists($fullPath) ? File::mimeType($fullPath) : 'image/webp')),
            };

            // Detect year from filename if not explicitly provided
            $itemYear = $inputYear;
            if (! $itemYear && preg_match('/\b(202[0-9]|203[0-9])\b/', $origName, $yearMatches)) {
                $itemYear = (int) $yearMatches[1];
            }

            $item = MediaItem::create([
                'filename' => basename($optimizedPath),
                'file_path' => $optimizedPath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'width' => $dimensions[0] ?? null,
                'height' => $dimensions[1] ?? null,
                'title' => $humanTitle,
                'caption' => null,
                'alt_text' => $humanTitle,
                'source_type' => $sourceType,
                'year' => $itemYear,
                'gallery_album_id' => $albumId,
                'gallery_photo_id' => null,
            ]);

            $createdItems[] = [
                'id' => $item->id,
                'filename' => $item->filename,
                'file_path' => $item->file_path,
                'image_url' => $item->image_url,
                'title' => $item->title,
                'caption' => $item->caption,
                'alt_text' => $item->alt_text,
                'formatted_size' => $item->formatted_size,
                'dimensions' => $item->dimensions,
                'date_formatted' => $item->formatted_date,
                'source_type' => $item->source_type,
                'year' => $item->year,
                'gallery_album_id' => $item->gallery_album_id,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($createdItems) === 1
                ? 'Image optimized and cataloged in Media Library.'
                : count($createdItems).' images optimized and cataloged in Media Library.',
            'item' => $createdItems[0],
            'items' => $createdItems,
            'image_path' => $createdItems[0]['file_path'],
            'image_url' => $createdItems[0]['image_url'],
        ]);
    }

    /**
     * Retrieve a cached lookup set of all asset paths actively referenced across the site.
     *
     * @return array<string, bool>
     */
    public static function getActiveSitePaths(): array
    {
        if (app()->environment('testing')) {
            return static::queryActiveSitePaths();
        }

        return Cache::remember('active_site_media_paths', 60, function () {
            return static::queryActiveSitePaths();
        });
    }

    /**
     * Query all referenced image paths across all site content tables.
     *
     * @return array<string, bool>
     */
    protected static function queryActiveSitePaths(): array
    {
        $paths = [];
        $paths = array_merge($paths, DB::table('page_sections')->whereNotNull('image_path')->pluck('image_path')->all());
        $paths = array_merge($paths, DB::table('programmes')->whereNotNull('cover_image')->pluck('cover_image')->all());
        $paths = array_merge($paths, DB::table('events')->whereNotNull('banner_image')->pluck('banner_image')->all());
        $paths = array_merge($paths, DB::table('event_sponsors')->whereNotNull('logo_path')->pluck('logo_path')->all());
        $paths = array_merge($paths, DB::table('team_members')->whereNotNull('photo')->pluck('photo')->all());
        $paths = array_merge($paths, DB::table('partners')->whereNotNull('logo')->pluck('logo')->all());
        $paths = array_merge($paths, DB::table('gallery_albums')->whereNotNull('cover_image')->pluck('cover_image')->all());
        $paths = array_merge($paths, DB::table('settings')->whereIn('key', ['site_logo'])->whereNotNull('value')->pluck('value')->all());

        $lookup = [];
        foreach ($paths as $p) {
            if (empty($p)) {
                continue;
            }
            $clean = ltrim(parse_url($p, PHP_URL_PATH) ?? $p, '/');
            $lookup[$clean] = true;
            $lookup[basename($clean)] = true;
        }

        return $lookup;
    }

    /**
     * Toggle or update the public gallery visibility of a media item.
     */
    public function toggleVisibility(Request $request, MediaItem $mediaItem): JsonResponse|RedirectResponse
    {
        $newStatus = $request->has('is_public')
            ? $request->boolean('is_public')
            : ! $mediaItem->is_public;

        $mediaItem->update(['is_public' => $newStatus]);

        if ($mediaItem->gallery_photo_id) {
            GalleryPhoto::where('id', $mediaItem->gallery_photo_id)->update(['is_public' => $newStatus]);
        }

        $label = $newStatus ? 'marked as Public in Gallery' : 'moved to Archive';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_public' => $newStatus,
                'message' => "Photo successfully {$label}.",
                'item' => $mediaItem,
            ]);
        }

        return back()->with('success', "Photo successfully {$label}.");
    }

    /**
     * Batch toggle visibility for multiple media items.
     */
    public function bulkVisibility(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:media_items,id'],
            'is_public' => ['required', 'boolean'],
        ]);

        $ids = $validated['ids'];
        $isPublic = (bool) $validated['is_public'];

        MediaItem::whereIn('id', $ids)->update(['is_public' => $isPublic]);

        $linkedPhotoIds = MediaItem::whereIn('id', $ids)
            ->whereNotNull('gallery_photo_id')
            ->pluck('gallery_photo_id')
            ->all();

        if (! empty($linkedPhotoIds)) {
            GalleryPhoto::whereIn('id', $linkedPhotoIds)->update(['is_public' => $isPublic]);
        }

        $count = count($ids);
        $label = $isPublic ? 'Public in Gallery' : 'Archive (Hidden from Public Gallery)';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $count,
                'is_public' => $isPublic,
                'message' => "Successfully marked {$count} items as {$label}.",
            ]);
        }

        return back()->with('success', "Successfully marked {$count} items as {$label}.");
    }

    /**
     * Update metadata (title, caption, alt_text, gallery_album_id, year, is_public) for a media item.
     */
    public function update(Request $request, MediaItem $mediaItem): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
            'alt_text' => ['nullable', 'string', 'max:500'],
            'gallery_album_id' => ['nullable', 'integer', 'exists:gallery_albums,id'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        if (array_key_exists('gallery_album_id', $validated)) {
            $validated['source_type'] = $validated['gallery_album_id'] ? 'gallery' : 'general';
        }

        $mediaItem->update($validated);

        if (array_key_exists('is_public', $validated) && $mediaItem->gallery_photo_id) {
            GalleryPhoto::where('id', $mediaItem->gallery_photo_id)->update(['is_public' => (bool) $validated['is_public']]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Media details updated successfully.',
                'item' => $mediaItem,
            ]);
        }

        return back()->with('success', 'Media details updated successfully.');
    }

    /**
     * Delete a single media item and its physical file.
     */
    public function destroy(Request $request, MediaItem $mediaItem): JsonResponse|RedirectResponse
    {
        $filePath = $mediaItem->file_path;

        // Delete physical file only if it resides in uploads/ to avoid touching core front assets
        if (str_starts_with($filePath, 'uploads/') && File::exists(public_path($filePath))) {
            @unlink(public_path($filePath));
        }

        $mediaItem->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Media item permanently removed from library.',
            ]);
        }

        return back()->with('success', 'Media item permanently removed from library.');
    }

    /**
     * Batch delete multiple media items.
     */
    public function bulkDestroy(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:media_items,id'],
        ]);

        $items = MediaItem::whereIn('id', $validated['ids'])->get();
        $count = 0;

        foreach ($items as $item) {
            if (str_starts_with($item->file_path, 'uploads/') && File::exists(public_path($item->file_path))) {
                @unlink(public_path($item->file_path));
            }
            $item->delete();
            $count++;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Successfully removed {$count} media items from library.",
            ]);
        }

        return back()->with('success', "Successfully removed {$count} media items from library.");
    }

    /**
     * JSON API powering universal modal picker (<x-admin.media-picker>).
     */
    public function pickerItems(Request $request): JsonResponse
    {
        $yearFilter = $request->input('year', 'all');
        $visibility = $request->input('visibility', 'all');
        $perPage = (int) $request->input('per_page', 60);
        if ($perPage < 1 || $perPage > 250) {
            $perPage = 60;
        }

        $query = MediaItem::query()
            ->search($request->input('search'))
            ->byDateFilter($request->input('date_filter'))
            ->bySourceType($request->input('category'))
            ->byYear($yearFilter)
            ->byVisibility($visibility)
            ->ordered($request->input('sort', 'newest'));

        $activeSitePaths = static::getActiveSitePaths();

        $paginator = $query->paginate($perPage);

        $items = collect($paginator->items())->map(function (MediaItem $item) use ($activeSitePaths) {
            $isUsedOnSite = isset($activeSitePaths[$item->file_path]) || isset($activeSitePaths[basename($item->file_path)]);
            $isDisplayedInGallery = (bool) $item->is_public && ($item->source_type === 'gallery' || ! empty($item->gallery_album_id) || ! empty($item->gallery_photo_id));

            return [
                'id' => $item->id,
                'filename' => $item->filename,
                'file_path' => $item->file_path,
                'image_url' => $item->image_url,
                'title' => $item->title ?: $item->filename,
                'caption' => $item->caption,
                'alt_text' => $item->alt_text,
                'formatted_size' => $item->formatted_size,
                'dimensions' => $item->dimensions,
                'date_formatted' => $item->formatted_date,
                'source_type' => $item->source_type,
                'year' => $item->year,
                'is_public' => (bool) $item->is_public,
                'is_used_on_site' => $isUsedOnSite,
                'is_displayed_in_gallery' => $isDisplayedInGallery,
                'gallery_album_id' => $item->gallery_album_id,
            ];
        });

        $collections = GalleryAlbum::ordered()->get(['id', 'title', 'slug', 'is_public']);

        $dbYears = MediaItem::whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year')->all();
        $availableYears = array_values(array_unique(array_merge([2026, 2025, 2024], $dbYears)));
        rsort($availableYears);

        return response()->json([
            'items' => $items,
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'has_more' => $paginator->hasMorePages(),
            'collections' => $collections,
            'years' => $availableYears,
        ]);
    }

    /**
     * Inspect request and raw uploaded files for PHP runtime upload limit failures.
     */
    protected function getUploadFailureMessage(Request $request): ?string
    {
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        $postMaxSize = ini_get('post_max_size') ?: '8M';
        $postMaxBytes = $this->parseIniSize($postMaxSize);

        if ($contentLength > 0 && $postMaxBytes > 0 && $contentLength > $postMaxBytes && empty($request->all()) && empty($request->allFiles())) {
            return "The uploaded payload exceeds the server maximum POST limit of {$postMaxSize}.";
        }

        $rawImage = $request->file('image');
        if ($rawImage instanceof UploadedFile && ! $rawImage->isValid()) {
            return $this->formatUploadedFileError($rawImage);
        }

        $rawImages = $request->file('images');
        if (is_array($rawImages)) {
            foreach ($rawImages as $file) {
                if ($file instanceof UploadedFile && ! $file->isValid()) {
                    return $this->formatUploadedFileError($file);
                }
            }
        }

        return null;
    }

    /**
     * Map PHP upload error codes to informative error messages.
     */
    protected function formatUploadedFileError(UploadedFile $file): string
    {
        $name = $file->getClientOriginalName() ?: 'The file';
        $maxFileSize = ini_get('upload_max_filesize') ?: '2M';

        return match ($file->getError()) {
            UPLOAD_ERR_INI_SIZE => "{$name} exceeds the server maximum upload limit of {$maxFileSize}.",
            UPLOAD_ERR_FORM_SIZE => "{$name} exceeds the form upload limit.",
            UPLOAD_ERR_PARTIAL => "{$name} was only partially uploaded. Please try again.",
            UPLOAD_ERR_NO_TMP_DIR => 'Server temporary directory is missing. Please contact administrator.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write upload to server disk.',
            default => "{$name} failed to upload.",
        };
    }

    /**
     * Parse php.ini size strings (e.g. 16M, 32M, 1G) into bytes.
     */
    protected function parseIniSize(string $size): int
    {
        $size = trim($size);
        if ($size === '') {
            return 0;
        }

        $unit = strtolower(substr($size, -1));
        $value = (int) $size;

        return match ($unit) {
            'g' => $value * 1024 * 1024 * 1024,
            'm' => $value * 1024 * 1024,
            'k' => $value * 1024,
            default => $value,
        };
    }
}
