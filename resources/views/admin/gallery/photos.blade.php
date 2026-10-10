<x-admin-layout title="Manage Photos - {{ $album->title }}">
    <div class="space-y-8">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.gallery.index') }}" class="text-xs font-semibold text-stone-500 hover:text-stone-900 flex items-center gap-1.5 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Collections
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-stone-900">{{ $album->title }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                        {{ $photos->count() }} {{ Str::plural('photo', $photos->count()) }}
                    </span>
                </div>
                <p class="text-sm text-stone-500 mt-1">
                    {{ $album->kicker ? $album->kicker . ' • ' : '' }}Manage, upload, and organize photos in this collection.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('gallery.album', $album->slug) }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-stone-100 text-stone-700 hover:bg-stone-200 transition-colors border border-stone-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    View Public
                </a>
                <a href="{{ route('admin.gallery.edit', $album) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    Edit Collection Details
                </a>
            </div>
        </div>

        {{-- Upload New Photos Card --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-6">
            <h2 class="text-base font-bold text-stone-900 mb-1">Upload Photos</h2>
            <p class="text-xs text-stone-500 mb-4">Select one or multiple images (JPG, PNG, WEBP, up to 10MB each) to add to this album.</p>

            <form method="POST" action="{{ route('admin.gallery.photos.upload', $album) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Files Input --}}
                    <div>
                        <label for="photos" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Select Image(s) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" 
                               id="photos" 
                               name="photos[]" 
                               multiple 
                               required 
                               accept="image/*" 
                               class="w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 cursor-pointer">
                        @error('photos')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Default Caption --}}
                    <div>
                        <label for="caption" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Caption <span class="text-stone-400 font-normal lowercase">(optional)</span>
                        </label>
                        <input type="text" 
                               id="caption" 
                               name="caption" 
                               placeholder="e.g. Students demonstrating robotics project" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors">
                    </div>

                    {{-- Default Alt Text --}}
                    <div>
                        <label for="alt_text" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Alt Text <span class="text-stone-400 font-normal lowercase">(optional)</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="alt_text" 
                                   name="alt_text" 
                                   placeholder="Defaults to collection title" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors">
                            <button type="submit" 
                                    class="shrink-0 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Photos Grid --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-stone-900">Photos in this Collection</h2>
                <span class="text-xs text-stone-500">Showing all {{ $photos->count() }} images</span>
            </div>

            @if ($photos->isEmpty())
                <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        📸
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No photos in this album yet.</p>
                    <p class="text-xs text-stone-400">Use the form above to upload images to this collection.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($photos as $photo)
                        <div class="bg-white rounded-xl border border-stone-200 overflow-hidden shadow-xs hover:shadow transition-shadow group flex flex-col justify-between">
                            <div class="relative aspect-4/3 bg-stone-100 overflow-hidden">
                                <img src="{{ $photo->image_url }}" 
                                     alt="{{ $photo->alt_text ?? $album->title }}" 
                                     loading="lazy" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-2 left-2 bg-black/60 backdrop-blur-xs text-white text-[10px] font-mono px-1.5 py-0.5 rounded">
                                    #{{ $loop->iteration }}
                                </div>
                            </div>
                            <div class="p-3 flex items-center justify-between gap-2 border-t border-stone-100 bg-stone-50/50">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-stone-700 truncate" title="{{ $photo->caption ?? basename($photo->image_path) }}">
                                        {{ $photo->caption ?: basename($photo->image_path) }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('admin.gallery.photos.destroy', [$album, $photo]) }}" 
                                      onsubmit="return confirm('Delete this photo from the album?');" 
                                      class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1 text-stone-400 hover:text-rose-600 rounded hover:bg-rose-50 transition-colors cursor-pointer" 
                                            title="Delete photo">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
