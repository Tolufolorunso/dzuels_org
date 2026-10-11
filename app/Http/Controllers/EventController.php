<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of upcoming and past events.
     */
    public function index(): View
    {
        $upcomingEvents = Event::active()
            ->upcoming()
            ->ordered()
            ->with('sponsors')
            ->get();

        $pastEvents = Event::active()
            ->where('event_date', '<', now()->startOfDay())
            ->orderBy('event_date', 'desc')
            ->with('sponsors')
            ->get();

        // Flagship or next major event
        $featuredEvent = $upcomingEvents->first() ?? $pastEvents->first();

        return view('events.index', compact('upcomingEvents', 'pastEvents', 'featuredEvent'));
    }

    /**
     * Display the specified event.
     */
    public function show(string $slug): View
    {
        $event = Event::active()
            ->where('slug', $slug)
            ->with('sponsors')
            ->firstOrFail();

        $otherEvents = Event::active()
            ->where('id', '!=', $event->id)
            ->ordered()
            ->take(3)
            ->get();

        return view('events.show', compact('event', 'otherEvents'));
    }

    /**
     * Handle public attendee registration for an event.
     */
    public function register(Request $request, string $slug): RedirectResponse
    {
        $event = Event::active()
            ->where('slug', $slug)
            ->firstOrFail();

        if (! $event->is_registration_open) {
            return redirect()->route('events.show', $event->slug)
                ->with('error', 'Registration for this event is currently closed.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'attendee_type' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($event->registrations()->where('email', $validated['email'])->exists()) {
            return redirect()->route('events.show', $event->slug)
                ->withInput()
                ->with('error', "You have already registered for this event with {$validated['email']}. We look forward to seeing you!");
        }

        $event->registrations()->create($validated);

        return redirect()->route('events.show', $event->slug)
            ->with('success', "Thank you for registering, {$validated['full_name']}! We have reserved your spot and look forward to welcoming you.");
    }
}
