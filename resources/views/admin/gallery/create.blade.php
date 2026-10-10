<x-admin-layout title="Create Gallery Album">
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
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Create New Collection</h1>
                <p class="text-sm text-stone-500 mt-1">Set up a new photo album for outreach programmes, competitions, or Foundation events.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 shadow-xs p-6 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Collection Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
                           required 
                           placeholder="e.g. ITD 2026, Spelling Bee Grand Finale" 
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors @error('title') border-rose-500 @enderror">
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        URL Slug <span class="text-stone-400 font-normal lowercase">(optional, auto-generated)</span>
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug') }}" 
                           placeholder="e.g. itd-2026" 
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
                           value="{{ old('kicker') }}" 
                           placeholder="e.g. Innovation and Tech Day, Literacy Outreach" 
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
                              placeholder="Brief narrative or context describing the collection..." 
                              class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors @error('description') border-rose-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cover Image --}}
                <div class="sm:col-span-2">
                    <label for="cover_image" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Cover Image <span class="text-stone-400 font-normal lowercase">(optional, JPG, PNG, WEBP max 5MB)</span>
                    </label>
                    <input type="file" 
                           id="cover_image" 
                           name="cover_image" 
                           accept="image/*" 
                           class="w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 cursor-pointer">
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
                           value="{{ old('sort_order', 0) }}" 
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
                               {{ old('is_active', true) ? 'checked' : '' }} 
                               class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon/20">
                        <div>
                            <span class="text-sm font-semibold text-stone-800">Publish immediately</span>
                            <span class="block text-xs text-stone-500">Make this collection visible on public gallery</span>
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
                    Create Collection
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
