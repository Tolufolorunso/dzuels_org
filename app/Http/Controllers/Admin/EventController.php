<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
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
        return view('admin.events.create');
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
            $file = $request->file('banner_image');
            $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $destination = public_path('uploads/events');

            if (! file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $validated['banner_image'] = 'uploads/events/'.$filename;
        }

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
        return view('admin.events.edit', compact('event'));
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
            $file = $request->file('banner_image');
            $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $destination = public_path('uploads/events');

            if (! file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $validated['banner_image'] = 'uploads/events/'.$filename;
        }

        $validated['schedule'] = $this->normalizeSchedule($request);
        $validated['is_registration_open'] = $request->boolean('is_registration_open');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
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
