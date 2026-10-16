<x-admin-layout title="Add Programme">
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header Bar --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">New Foundation Programme</h1>
                <p class="text-sm text-stone-500 mt-1">Publish a community educational project or initiative.</p>
            </div>
            <a href="{{ route('admin.programmes.index') }}" 
               class="text-xs font-semibold text-stone-600 hover:text-stone-900 transition-colors">
                ← Back to programmes
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.programmes.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Title & Slug Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                                Programme Title <span class="text-rose-500">*</span>
                            </label>
                            <x-admin.ai-button 
                                for="title" 
                                field-name="title" 
                                field-label="Programme Title" 
                                page-context="Programmes Management" 
                                section-context="Educational Initiative" 
                            />
                        </div>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               required
                               value="{{ old('title') }}" 
                               placeholder="e.g. Digital Infrastructure & Literacy in Ijero-Ekiti"
                               class="w-full px-4 py-2.5 rounded-xl border @error('title') border-rose-500 ring-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                        @error('title')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            URL Slug <span class="text-stone-400 font-normal">(Optional, auto-generated)</span>
                        </label>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug') }}" 
                               placeholder="e.g. ijero-digital-literacy"
                               class="w-full px-4 py-2.5 rounded-xl border @error('slug') border-rose-500 ring-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                        @error('slug')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Partner & Sort Order --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="partner_name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Partner / Sponsor Name <span class="text-stone-400 font-normal">(Optional)</span>
                        </label>
                        <input type="text" 
                               name="partner_name" 
                               id="partner_name" 
                               value="{{ old('partner_name') }}" 
                               placeholder="e.g. Information Technology Seed Development Initiative (SeedDev)"
                               class="w-full px-4 py-2.5 rounded-xl border @error('partner_name') border-rose-500 ring-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                        @error('partner_name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Display Sort Order
                        </label>
                        <input type="number" 
                               name="sort_order" 
                               id="sort_order" 
                               value="{{ old('sort_order', 0) }}" 
                               min="0"
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                    </div>
                </div>

                {{-- Summary --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="summary" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                            Short Summary <span class="text-rose-500">*</span>
                        </label>
                        <x-admin.ai-button 
                            for="summary" 
                            field-name="summary" 
                            field-label="Programme Summary" 
                            page-context="Programmes Management" 
                            section-context="Programme Overview" 
                            :sibling-selectors="['title' => '#title', 'partner_name' => '#partner_name']" 
                        />
                    </div>
                    <textarea name="summary" 
                              id="summary" 
                              rows="3" 
                              required
                              placeholder="Concise 1-2 sentence overview shown on index cards and teaser bands..."
                              class="w-full px-4 py-2.5 rounded-xl border @error('summary') border-rose-500 ring-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">{{ old('summary') }}</textarea>
                    @error('summary')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Narrative Body --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="body" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                            Full Programme Narrative (Body) <span class="text-rose-500">*</span>
                        </label>
                        <x-admin.ai-button 
                            for="body" 
                            field-name="body" 
                            field-label="Programme Narrative" 
                            page-context="Programmes Management" 
                            section-context="Full Initiative Narrative" 
                            :sibling-selectors="['title' => '#title', 'summary' => '#summary', 'partner_name' => '#partner_name']" 
                        />
                    </div>
                    <textarea name="body" 
                              id="body" 
                              rows="8" 
                              required
                              placeholder="Detailed description of the programme, goals, activities, and community impact (paragraphs separated by blank lines)..."
                              class="w-full px-4 py-2.5 rounded-xl border @error('body') border-rose-500 ring-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm font-sans">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cover Image --}}
                <div>
                    <x-admin.media-picker 
                        name="cover_image" 
                        :value="old('cover_image')" 
                        label="Cover Photo" 
                        help="Choose from existing gallery albums or upload and auto-optimize a new image (converted to lightweight WebP <= 180 KB)." />
                    @error('cover_image')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Flags (Featured & Active) --}}
                <div class="pt-4 border-t border-stone-200 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-stone-200 bg-stone-50/50 hover:bg-stone-50 cursor-pointer">
                        <input type="checkbox" 
                               name="is_featured" 
                               value="1" 
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon">
                        <div>
                            <span class="text-xs font-bold text-stone-900 block">Featured Programme</span>
                            <span class="text-[11px] text-stone-500 block">Spotlight on homepage and top of programmes page</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-stone-200 bg-stone-50/50 hover:bg-stone-50 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon">
                        <div>
                            <span class="text-xs font-bold text-stone-900 block">Visible on Website</span>
                            <span class="text-[11px] text-stone-500 block">Uncheck to temporarily hide from public view</span>
                        </div>
                    </label>
                </div>

                {{-- SEO Metadata Accordion/Fields --}}
                <div class="pt-4 border-t border-stone-200 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-stone-600">Search Engine Optimization (Optional)</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="seo_title" class="block text-xs font-medium text-stone-700">SEO Title</label>
                                <x-admin.ai-button 
                                    for="seo_title" 
                                    field-name="seo_title" 
                                    field-label="SEO Meta Title" 
                                    page-context="Programmes SEO" 
                                    section-context="Search Engine Optimization" 
                                    :sibling-selectors="['title' => '#title']" 
                                />
                            </div>
                            <input type="text" 
                                   name="seo_title" 
                                   id="seo_title" 
                                   value="{{ old('seo_title') }}" 
                                   placeholder="Title tag for search engines"
                                   class="w-full px-3.5 py-2 rounded-xl border border-stone-300 text-xs focus:border-maroon focus:ring-1 focus:ring-maroon">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="seo_description" class="block text-xs font-medium text-stone-700">SEO Description</label>
                                <x-admin.ai-button 
                                    for="seo_description" 
                                    field-name="seo_description" 
                                    field-label="SEO Meta Description" 
                                    page-context="Programmes SEO" 
                                    section-context="Search Engine Optimization" 
                                    :sibling-selectors="['title' => '#title', 'summary' => '#summary']" 
                                />
                            </div>
                            <input type="text" 
                                   name="seo_description" 
                                   id="seo_description" 
                                   value="{{ old('seo_description') }}" 
                                   placeholder="Search engine meta description snippet"
                                   class="w-full px-3.5 py-2 rounded-xl border border-stone-300 text-xs focus:border-maroon focus:ring-1 focus:ring-maroon">
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="pt-6 border-t border-stone-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.programmes.index') }}" 
                       class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-stone-600 hover:bg-stone-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                        Save Programme
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
