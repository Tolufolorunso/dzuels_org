<x-admin-layout title="Edit {{ $pageMeta['title'] }} Content">
    <div class="space-y-8">
        <!-- Breadcrumb & Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-stone-200">
            <div>
                <nav class="flex items-center gap-2 text-xs text-stone-400 mb-2">
                    <a href="{{ route('admin.pages.index') }}" class="hover:text-stone-700 transition-colors">Pages</a>
                    <span>/</span>
                    <span class="text-stone-900 font-bold">{{ $pageMeta['title'] }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Editing {{ $pageMeta['title'] }}</h1>
                <p class="text-sm text-stone-500 mt-1">{{ $pageMeta['description'] }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-white border border-stone-300 text-stone-700 hover:bg-stone-50 transition-colors">
                    <span>← All Pages</span>
                </a>
                <a href="{{ url($pageMeta['public_url']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-stone-900 text-white hover:bg-stone-800 transition-colors shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>View Live Page</span>
                </a>
            </div>
        </div>

        <!-- Sections List -->
        <div class="space-y-8">
            @forelse ($sections as $section)
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-stone-100">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-50 text-maroon flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($section->section_key, 0, 2) }}
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-stone-900">
                                    Section: {{ ucwords(str_replace('_', ' ', $section->section_key)) }}
                                </h2>
                                <span class="text-[11px] font-mono text-stone-400">section_key: {{ $section->section_key }}</span>
                            </div>
                        </div>

                        <span class="text-[11px] text-stone-400">
                            Last modified: {{ $section->updated_at ? $section->updated_at->diffForHumans() : 'Never' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.pages.sections.update', ['page' => $page, 'section' => $section->section_key]) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Headline -->
                            <div>
                                <label for="{{ $section->section_key }}_headline" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Section Headline
                                </label>
                                <input type="text" 
                                       id="{{ $section->section_key }}_headline" 
                                       name="headline" 
                                       value="{{ old('headline', $section->headline) }}" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                                       placeholder="Enter main headline...">
                            </div>

                            <!-- Subheadline -->
                            <div>
                                <label for="{{ $section->section_key }}_subheadline" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Subheadline / Eyebrow Badge
                                </label>
                                <input type="text" 
                                       id="{{ $section->section_key }}_subheadline" 
                                       name="subheadline" 
                                       value="{{ old('subheadline', $section->subheadline) }}" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                                       placeholder="Enter subheadline or category...">
                            </div>
                        </div>

                        <!-- Body Text -->
                        <div>
                            <label for="{{ $section->section_key }}_body_text" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                Section Description / Body Copy
                            </label>
                            <textarea id="{{ $section->section_key }}_body_text" 
                                      name="body_text" 
                                      rows="4" 
                                      class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none leading-relaxed" 
                                      placeholder="Enter main descriptive copy...">{{ old('body_text', $section->body_text) }}</textarea>
                            <p class="text-[11px] text-stone-400 mt-1">Paragraph line breaks are preserved when rendered on the public page.</p>
                        </div>

                        <!-- Button Text & URL -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div>
                                <label for="{{ $section->section_key }}_button_text" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Button Text (Optional)
                                </label>
                                <input type="text" 
                                       id="{{ $section->section_key }}_button_text" 
                                       name="button_text" 
                                       value="{{ old('button_text', $section->button_text) }}" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                                       placeholder="e.g. Donate Now, Learn More...">
                            </div>

                            <div>
                                <label for="{{ $section->section_key }}_button_url" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Button URL (Optional)
                                </label>
                                <input type="text" 
                                       id="{{ $section->section_key }}_button_url" 
                                       name="button_url" 
                                       value="{{ old('button_url', $section->button_url) }}" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                                       placeholder="e.g. /donate, /get-involved...">
                            </div>
                        </div>

                        <!-- Image Upload & Preview -->
                        <div class="pt-2 border-t border-stone-100">
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-3">
                                Section Imagery & Graphic Banner
                            </label>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                                @if ($section->image_path)
                                    <div class="shrink-0">
                                        <span class="block text-[11px] text-stone-400 mb-1">Current Image:</span>
                                        <div class="w-32 h-20 rounded-2xl overflow-hidden border border-stone-200 bg-stone-50 shadow-xs">
                                            <img src="{{ $section->getImageUrl() }}" 
                                                 alt="{{ $section->headline }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <span class="block text-[11px] text-stone-400 mb-1">Upload New Image (Replaces current):</span>
                                    <input type="file" 
                                           name="image" 
                                           accept="image/png,image/jpeg,image/webp" 
                                           class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 cursor-pointer">
                                    <p class="text-[11px] text-stone-400 mt-1">Recommended format: WebP, PNG, or JPG (max 5MB).</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-stone-100 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-stone-900 transition-colors shadow-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Save Changes for {{ ucwords(str_replace('_', ' ', $section->section_key)) }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-stone-200 text-stone-400 text-sm">
                    No sections configured for this page yet.
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
