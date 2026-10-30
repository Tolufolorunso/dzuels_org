<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\GalleryAlbum;
use App\Models\MediaItem;
use App\Services\ImageOptimizerService;
use App\Services\MediaMetadataExtractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $optimizer
    ) {}

    /**
     * Display a listing of foundation events.
     */
    public function index(): View
    {
        $events = Event::withCount('registrations')
            ->orderBy('sort_order', 'asc')
            ->orderBy('event_date', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        $albums = GalleryAlbum::active()->orderBy('title', 'asc')->get();

        return view('admin.events.create', compact('albums'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $slug = $validated['slug'] ?? null;
        if (! $slug) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Event::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $this->optimizer->optimize(
                $request->file('banner_image'),
                'uploads/events',
                1920,
                1080,
                82
            );
        } elseif ($request->filled('banner_image')) {
            $validated['banner_image'] = $request->input('banner_image');
        } elseif ($request->filled('banner_image_path')) {
            $validated['banner_image'] = $request->input('banner_image_path');
        }

        $validated['gallery_photos'] = $this->processGalleryPhotos($request, $validated['title']);
        $validated['gallery_album_id'] = $request->filled('gallery_album_id') ? (int) $request->input('gallery_album_id') : null;
        $validated['schedule'] = $this->normalizeSchedule($request);
        $validated['is_registration_open'] = $request->boolean('is_registration_open', true);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event): View
    {
        $albums = GalleryAlbum::active()->orderBy('title', 'asc')->get();

        return view('admin.events.edit', compact('event', 'albums'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $this->optimizer->optimize(
                $request->file('banner_image'),
                'uploads/events',
                1920,
                1080,
                82
            );
        } elseif ($request->has('banner_image')) {
            $validated['banner_image'] = $request->input('banner_image') ?: null;
        } elseif ($request->has('banner_image_path')) {
            $validated['banner_image'] = $request->input('banner_image_path') ?: null;
        }

        $validated['gallery_photos'] = $this->processGalleryPhotos($request, $validated['title'], $event->gallery_photos);
        $validated['gallery_album_id'] = $request->filled('gallery_album_id') ? (int) $request->input('gallery_album_id') : null;
        $validated['schedule'] = $this->normalizeSchedule($request);
        $validated['is_registration_open'] = $request->boolean('is_registration_open');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
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
                    'uploads/events',
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
                    'source_type' => 'event',
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
     * Remove the specified event from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Toggle the active visibility status of an event.
     */
    public function toggleActive(Event $event): RedirectResponse
    {
        $event->update([
            'is_active' => ! $event->is_active,
        ]);

        $status = $event->is_active ? 'published' : 'hidden';

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$event->title}' is now {$status}.");
    }

    /**
     * Toggle the registration status of an event.
     */
    public function toggleRegistration(Event $event): RedirectResponse
    {
        $event->update([
            'is_registration_open' => ! $event->is_registration_open,
        ]);

        $status = $event->is_registration_open ? 'open' : 'closed';

        return redirect()->route('admin.events.index')
            ->with('success', "Registration for '{$event->title}' is now {$status}.");
    }

    /**
     * View registrations list for a given event.
     */
    public function registrations(Event $event): View
    {
        $registrations = $event->registrations()->latest()->get();

        return view('admin.events.registrations', compact('event', 'registrations'));
    }

    /**
     * Delete an attendee registration.
     */
    public function destroyRegistration(Event $event, EventRegistration $registration): RedirectResponse
    {
        $registration->delete();

        return redirect()->route('admin.events.registrations', $event)
            ->with('success', 'Attendee registration deleted successfully.');
    }

    /**
     * Normalize schedule input (support array rows or raw JSON).
     */
    protected function normalizeSchedule(Request $request): ?array
    {
        $schedule = $request->input('schedule');

        if (is_string($schedule)) {
            $decoded = json_decode($schedule, true);

            return is_array($decoded) ? $decoded : null;
        }

        if (is_array($schedule)) {
            $cleaned = [];
            foreach ($schedule as $item) {
                if (! empty($item['title'])) {
                    $cleaned[] = [
                        'time' => $item['time'] ?? '',
                        'title' => $item['title'] ?? '',
                        'speaker' => $item['speaker'] ?? '',
                    ];
                }
            }

            return ! empty($cleaned) ? $cleaned : null;
        }

        return null;
    }
}
