<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Services\ImageOptimizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $sort = $request->query('sort', 'newest');

        $query = MediaItem::query()
            ->search($search)
            ->byDateFilter($dateFilter)
            ->bySourceType($category)
            ->ordered($sort);

        $mediaItems = $query->paginate(24)->withQueryString();

        // Calculate library metrics
        $totalCount = MediaItem::count();
        $totalBytes = MediaItem::sum('file_size');
        $webpCount = MediaItem::where('mime_type', 'image/webp')->count();
        $webpRate = $totalCount > 0 ? (int) round(($webpCount / $totalCount) * 100) : 100;

        $totalSizeFormatted = '0 KB';
        if ($totalBytes >= 1048576) {
            $totalSizeFormatted = number_format($totalBytes / 1048576, 1).' MB';
        } elseif ($totalBytes > 0) {
            $totalSizeFormatted = number_format($totalBytes / 1024, 0).' KB';
        }

        return view('admin.media.index', [
            'mediaItems' => $mediaItems,
            'totalCount' => $totalCount,
            'totalSizeFormatted' => $totalSizeFormatted,
            'webpRate' => $webpRate,
            'currentSearch' => $search,
            'currentDateFilter' => $dateFilter,
            'currentCategory' => $category,
            'currentSort' => $sort,
        ]);
    }

    /**
     * Handle single or batch drag-and-drop file uploads into the library.
     */
    public function upload(Request $request, ImageOptimizerService $optimizer): JsonResponse
    {
        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:15360'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:15360'],
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
            ], 422);
        }

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

            $item = MediaItem::create([
                'filename' => basename($optimizedPath),
                'file_path' => $optimizedPath,
                'file_size' => $fileSize,
                'mime_type' => 'image/webp',
                'width' => $dimensions[0] ?? null,
                'height' => $dimensions[1] ?? null,
                'title' => $humanTitle,
                'caption' => null,
                'alt_text' => $humanTitle,
                'source_type' => 'general',
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
     * Update metadata (title, caption, alt_text) for a media item.
     */
    public function update(Request $request, MediaItem $mediaItem): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
            'alt_text' => ['nullable', 'string', 'max:500'],
        ]);

        $mediaItem->update($validated);

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
        $query = MediaItem::query()
            ->search($request->input('search'))
            ->byDateFilter($request->input('date_filter'))
            ->bySourceType($request->input('category'))
            ->ordered($request->input('sort', 'newest'));

        $items = $query->limit(60)->get()->map(function (MediaItem $item) {
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
            ];
        });

        return response()->json([
            'items' => $items,
        ]);
    }
}
