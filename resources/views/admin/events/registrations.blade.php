<x-admin-layout title="Event Registrations">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold text-stone-500 hover:text-stone-800 flex items-center gap-1.5 mb-1">
                    &larr; Back to Events
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                    Attendee Registrations: {{ $event->title }}
                </h1>
                <p class="text-sm text-stone-500 mt-1">
                    <span>📅 {{ $event->formatted_date }}</span>
                    <span class="mx-2">&bull;</span>
                    <span>📍 {{ $event->venue ?? 'Venue TBA' }}</span>
                    <span class="mx-2">&bull;</span>
                    <span class="font-semibold text-stone-700">{{ $registrations->count() }} total registered</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.events.edit', $event) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold bg-stone-100 text-stone-700 hover:bg-stone-200 transition-colors">
                    Edit Event
                </a>
                <a href="{{ url('/events/' . $event->slug) }}" 
                   target="_blank" 
                   class="px-4 py-2 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-colors">
                    View Public Page &rarr;
                </a>
            </div>
        </div>

        {{-- Registrations Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($registrations->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        👥
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No attendees have registered for this event yet.</p>
                    <p class="text-xs text-stone-400">Registrations submitted through the public event registration form will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-center w-12">#</th>
                                <th scope="col" class="px-6 py-3.5">Attendee</th>
                                <th scope="col" class="px-6 py-3.5">Contact Details</th>
                                <th scope="col" class="px-6 py-3.5">Category</th>
                                <th scope="col" class="px-6 py-3.5">Notes / Expectations</th>
                                <th scope="col" class="px-6 py-3.5">Registered At</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach ($registrations as $index => $registration)
                                <tr class="hover:bg-stone-50/75 transition-colors">
                                    {{-- Index --}}
                                    <td class="px-6 py-4 text-xs font-mono text-center text-stone-400">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- Attendee Name --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">{{ $registration->full_name }}</div>
                                    </td>

                                    {{-- Contact Details --}}
                                    <td class="px-6 py-4 text-xs">
                                        <div>
                                            <a href="mailto:{{ $registration->email }}" class="text-maroon hover:underline font-medium">
                                                {{ $registration->email }}
                                            </a>
                                        </div>
                                        <div class="text-stone-500 mt-0.5">
                                            <a href="tel:{{ $registration->phone }}" class="hover:text-stone-800">
                                                {{ $registration->phone }}
                                            </a>
                                        </div>
                                    </td>

                                    {{-- Category / Attendee Type --}}
                                    <td class="px-6 py-4 text-xs">
                                        @if ($registration->attendee_type)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 border border-stone-200 capitalize">
                                                {{ str_replace('_', ' ', $registration->attendee_type) }}
                                            </span>
                                        @else
                                            <span class="text-stone-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Notes --}}
                                    <td class="px-6 py-4 text-xs text-stone-600 max-w-xs">
                                        @if ($registration->notes)
                                            <p class="line-clamp-2" title="{{ $registration->notes }}">{{ $registration->notes }}</p>
                                        @else
                                            <span class="text-stone-400 italic">None provided</span>
                                        @endif
                                    </td>

                                    {{-- Registered At --}}
                                    <td class="px-6 py-4 text-xs text-stone-500 whitespace-nowrap">
                                        {{ $registration->created_at->format('M j, Y g:i A') }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" 
                                              action="{{ route('admin.events.registrations.destroy', [$event, $registration]) }}" 
                                              onsubmit="return confirm('Remove registration for {{ addslashes($registration->full_name) }}?');" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer" 
                                                    title="Remove registration">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
