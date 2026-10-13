<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\ImageOptimizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Retrieve a filtered JSON listing of gallery photos for the media picker.
     */
    public function photos(Request $request): JsonResponse
    {
        $query = GalleryPhoto::with('album')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        if ($request->filled('album_id')) {
            $query->where('gallery_album_id', $request->integer('album_id'));
        }

        if ($request->filled('search')) {
            $search = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('caption', 'like', $search)
                    ->orWhere('alt_text', 'like', $search)
                    ->orWhere('year', 'like', $search)
                    ->orWhere('image_path', 'like', $search);
            });
        }

        $photos = $query->limit(60)->get()->map(function ($photo) {
            return [
                'id' => $photo->id,
                'image_path' => $photo->image_path,
                'image_url' => $photo->image_url,
                'caption' => $photo->caption,
                'year' => $photo->year,
                'display_label' => $photo->display_label,
                'album_id' => $photo->gallery_album_id,
                'album_title' => $photo->album?->title ?? 'General',
            ];
        });

        $albums = GalleryAlbum::orderBy('sort_order', 'asc')->get(['id', 'title']);

        return response()->json([
            'photos' => $photos,
            'albums' => $albums,
        ]);
    }

    /**
     * Direct AJAX upload & optimization for the media picker.
     */
    public function upload(Request $request, ImageOptimizerService $optimizer): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ]);

        $optimizedPath = $optimizer->optimize(
            $request->file('image'),
            'uploads/media',
            1920,
            1920,
            82
        );

        return response()->json([
            'success' => true,
            'image_path' => $optimizedPath,
            'image_url' => asset($optimizedPath),
        ]);
    }
}
