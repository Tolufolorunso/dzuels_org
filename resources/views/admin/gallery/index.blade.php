<x-admin-layout title="Gallery Albums">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Gallery Collections</h1>
                <p class="text-sm text-stone-500 mt-1">Manage photo collections, upload event highlights, update cover images, and curate photo archives.</p>
            </div>
            <div>
                <a href="{{ route('admin.gallery.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Album
                </a>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($albums->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        🖼️
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No gallery albums found.</p>
                    <a href="{{ route('admin.gallery.create') }}" class="text-xs text-maroon hover:underline font-semibold inline-block">
                        Create the first album
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Cover & Collection</th>
                                <th scope="col" class="px-6 py-3.5">Kicker & Description</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Photos</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Visibility</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Order</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach ($albums as $album)
                                <tr class="hover:bg-stone-50/75 transition-colors">
                                    {{-- Cover & Collection Title --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-14 h-11 rounded-lg bg-stone-100 border border-stone-200 overflow-hidden shrink-0">
                                                <img src="{{ $album->cover_image_url }}" 
                                                     alt="{{ $album->title }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <div class="max-w-xs sm:max-w-sm">
                                                <span class="font-bold text-stone-900 block line-clamp-1">{{ $album->title }}</span>
                                                <span class="text-xs font-mono text-stone-500 block">/gallery/{{ $album->slug }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kicker & Description --}}
                                    <td class="px-6 py-4 text-xs">
                                        @if ($album->kicker)
                                            <span class="inline-block px-2 py-0.5 rounded-md bg-stone-100 text-stone-700 font-semibold mb-1">
                                                {{ $album->kicker }}
                                            </span>
                                        @endif
                                        <p class="text-stone-500 line-clamp-1">{{ $album->description ?? 'No description provided.' }}</p>
                                    </td>

                                    {{-- Photos Count / Manage Link --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.gallery.photos', $album) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all {{ $album->photos_count > 0 ? 'bg-amber-100 text-amber-900 hover:bg-amber-200 border border-amber-300' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                                           title="Manage photos in this collection">
                                            <span>📷</span>
                                            <span>{{ $album->photos_count }} {{ Str::plural('photo', $album->photos_count) }}</span>
                                        </a>
                                    </td>

                                    {{-- Visibility Toggle --}}
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="{{ route('admin.gallery.toggle', $album) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors {{ $album->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-stone-100 text-stone-600 border border-stone-200 hover:bg-stone-200' }}"
                                                    title="Click to toggle published status">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $album->is_active ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                                {{ $album->is_active ? 'Published' : 'Hidden' }}
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Order --}}
                                    <td class="px-6 py-4 text-xs font-mono text-center text-stone-600">
                                        {{ $album->sort_order }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Manage Photos --}}
                                            <a href="{{ route('admin.gallery.photos', $album) }}" 
                                               class="p-1.5 text-stone-500 hover:text-amber-700 rounded-lg hover:bg-stone-100 transition-colors"
                                               title="Manage photos">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </a>

                                            {{-- View Public --}}
                                            <a href="{{ route('gallery.album', $album->slug) }}" 
                                               target="_blank" 
                                               class="p-1.5 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition-colors"
                                               title="View on public website">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.gallery.edit', $album) }}" 
                                               class="p-1.5 text-stone-500 hover:text-maroon rounded-lg hover:bg-stone-100 transition-colors"
                                               title="Edit collection details">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('admin.gallery.destroy', $album) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete album \'{{ addslashes($album->title) }}\'? This will also delete all {{ $album->photos_count }} photos in this collection.');" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer"
                                                        title="Delete collection">
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
