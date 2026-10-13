<x-admin-layout title="Edit Gallery Album">
    <div class="space-y-6 max-w-4xl mx-auto">
        {{-- Header Bar --}}
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.gallery.index') }}" class="text-xs font-semibold text-stone-500 hover:text-stone-900 flex items-center gap-1.5 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Collections
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Edit Collection</h1>
                <p class="text-sm text-stone-500 mt-1">Update details, cover image, and metadata for '{{ $album->title }}'.</p>
            </div>
            <div>
                <a href="{{ route('admin.gallery.photos', $album) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-amber-500 text-white hover:bg-amber-600 transition-colors shadow-xs">
                    <span>📷</span>
                    Manage Photos ({{ $album->photos()->count() }})
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <form method="POST" action="{{ route('admin.gallery.update', $album) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 shadow-xs p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Collection Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $album->title) }}" 
                           required 
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors @error('title') border-rose-500 @enderror">
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        URL Slug
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug', $album->slug) }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors @error('slug') border-rose-500 @enderror">
                    @error('slug')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kicker --}}
                <div>
                    <label for="kicker" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Kicker Badge / Sub-heading
                    </label>
                    <input type="text" 
                           id="kicker" 
                           name="kicker" 
                           value="{{ old('kicker', $album->kicker) }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors @error('kicker') border-rose-500 @enderror">
                    @error('kicker')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Description / Summary
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors @error('description') border-rose-500 @enderror">{{ old('description', $album->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cover Image & Preview --}}
                <div class="sm:col-span-2">
                    <x-admin.media-picker 
                        name="cover_image" 
                        :value="old('cover_image', $album->cover_image)" 
                        label="Cover Image" 
                        help="Choose from existing gallery albums or upload and auto-optimize a new image (converted to lightweight WebP <= 180 KB)." />
                    @error('cover_image')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sort Order --}}
                <div>
                    <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Sort Order
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', $album->sort_order) }}" 
                           min="0" 
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors @error('sort_order') border-rose-500 @enderror">
                    @error('sort_order')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Is Active --}}
                <div class="flex items-center pt-6">
                    <label class="relative flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $album->is_active) ? 'checked' : '' }} 
                               class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon/20">
                        <div>
                            <span class="text-sm font-semibold text-stone-800">Publish collection</span>
                            <span class="block text-xs text-stone-500">Make visible on public gallery</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-6 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.gallery.index') }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
