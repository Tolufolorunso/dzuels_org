<x-admin-layout title="Events">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Events & Gatherings</h1>
                <p class="text-sm text-stone-500 mt-1">Manage Foundation events, anniversary celebrations, schedule highlights, and attendee registrations.</p>
            </div>
            <div>
                <a href="{{ route('admin.events.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Event
                </a>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($events->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        📅
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No events found.</p>
                    <a href="{{ route('admin.events.create') }}" class="text-xs text-maroon hover:underline font-semibold inline-block">
                        Create the first event
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Banner & Title</th>
                                <th scope="col" class="px-6 py-3.5">Date & Venue</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Registrations</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Registration</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Visibility</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Order</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach ($events as $event)
                                <tr class="hover:bg-stone-50/75 transition-colors">
                                    {{-- Banner & Title --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-14 h-11 rounded-lg bg-stone-100 border border-stone-200 overflow-hidden shrink-0">
                                                <img src="{{ $event->banner_image_url }}" 
                                                     alt="{{ $event->title }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <div class="max-w-xs sm:max-w-sm">
                                                <span class="font-bold text-stone-900 block line-clamp-1">{{ $event->title }}</span>
                                                <span class="text-xs font-mono text-stone-500 block">/events/{{ $event->slug }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Date & Venue --}}
                                    <td class="px-6 py-4 text-xs">
                                        <div class="font-medium text-stone-900 flex items-center gap-1.5">
                                            <span>📅</span>
                                            <span>{{ $event->formatted_date }}</span>
                                        </div>
                                        <div class="text-stone-500 mt-0.5 flex items-center gap-1.5">
                                            <span>📍</span>
                                            <span class="line-clamp-1">{{ $event->venue ?? 'Venue TBA' }}</span>
                                        </div>
                                    </td>

                                    {{-- Registrations Count --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.events.registrations', $event) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all {{ $event->registrations_count > 0 ? 'bg-amber-100 text-amber-900 hover:bg-amber-200 border border-amber-300' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                                           title="Click to view attendee registrations">
                                            <span>👥</span>
                                            <span>{{ $event->registrations_count }}</span>
                                        </a>
                                    </td>

                                    {{-- Registration Toggle --}}
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="{{ route('admin.events.toggle-registration', $event) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors {{ $event->is_registration_open ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}"
                                                    title="Click to toggle registration availability">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $event->is_registration_open ? 'bg-emerald-500' : 'bg-rose-400' }}"></span>
                                                {{ $event->is_registration_open ? 'Open' : 'Closed' }}
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Visibility Toggle --}}
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="{{ route('admin.events.toggle-active', $event) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors {{ $event->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-stone-100 text-stone-600 border border-stone-200 hover:bg-stone-200' }}"
                                                    title="Click to toggle published status">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $event->is_active ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                                {{ $event->is_active ? 'Published' : 'Hidden' }}
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Order --}}
                                    <td class="px-6 py-4 text-xs font-mono text-center text-stone-600">
                                        {{ $event->sort_order }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- View Attendees --}}
                                            <a href="{{ route('admin.events.registrations', $event) }}" 
                                               class="p-1.5 text-stone-500 hover:text-amber-700 rounded-lg hover:bg-stone-100 transition-colors"
                                               title="View attendee registrations list">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </a>

                                            {{-- View Public --}}
                                            <a href="{{ url('/events/' . $event->slug) }}" 
                                               target="_blank" 
                                               class="p-1.5 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition-colors"
                                               title="View live public page">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.events.edit', $event) }}" 
                                               class="p-1.5 text-stone-500 hover:text-maroon rounded-lg hover:bg-stone-100 transition-colors"
                                               title="Edit event details">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete event \'{{ addslashes($event->title) }}\'? This will also delete any attendee registrations.');" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer"
                                                        title="Delete event">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
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
