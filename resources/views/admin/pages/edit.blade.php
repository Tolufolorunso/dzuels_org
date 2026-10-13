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

                        @if ($section->section_key !== 'famoyinka_library')
                            <!-- Image Upload & Gallery Picker -->
                            <div class="pt-2 border-t border-stone-100">
                                <x-admin.media-picker 
                                    name="image_path" 
                                    :value="$section->image_path" 
                                    label="Section Imagery & Graphic Banner" 
                                    help="Select an existing photo from the gallery archive or upload a new image (auto-converted to WebP <= 180 KB)." />
                            </div>
                        @endif

                        @if ($page === 'home' && $section->section_key === 'famoyinka_library')
                            @php
                                $defaultPhotos = [
                                    ['image' => 'front/images/gallery/optimized/famoyinka-01.webp', 'label' => 'Free Book Loans', 'alt' => "Student selecting books at Famoyinka Memorial Children's Library"],
                                    ['image' => 'front/images/gallery/optimized/famoyinka-03.webp', 'label' => 'Creative Arts', 'alt' => 'Creative watercolor art workshop'],
                                    ['image' => 'front/images/gallery/optimized/famoyinka-02.webp', 'label' => 'Leadership Games', 'alt' => 'Leadership teamwork games and sports'],
                                    ['image' => 'front/images/gallery/optimized/famoyinka-04.webp', 'label' => 'Ikogosi Excursion', 'alt' => 'Ikogosi Warm Springs educational excursion'],
                                ];
                                $libraryPhotos = old('meta_data.photos', $section->meta_data['photos'] ?? $defaultPhotos);
                            @endphp

                            <div class="pt-6 border-t border-stone-200/80 space-y-6">
                                <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-5">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-xl">📚</span>
                                        <h4 class="text-sm font-bold text-stone-900">Famoyinka Memorial Library – Action Photography Mosaic</h4>
                                    </div>
                                    <p class="text-xs text-stone-600">Select or upload the 4 action photos featured on the homepage sanctuary showcase. You can customize the image, action pill label (e.g. "Free Book Loans"), and accessible alt text for each photo.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @for ($idx = 0; $idx < 4; $idx++)
                                        @php
                                            $photo = $libraryPhotos[$idx] ?? ($defaultPhotos[$idx] ?? []);
                                        @endphp
                                        <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200/80 space-y-4">
                                            <div class="flex items-center justify-between pb-2 border-b border-stone-200/60">
                                                <span class="text-xs font-bold text-stone-800">Mosaic Photo {{ $idx + 1 }}</span>
                                                <span class="text-[10px] text-stone-500 font-medium">Slot {{ $idx + 1 }} of 4</span>
                                            </div>

                                            <x-admin.media-picker 
                                                name="meta_data[photos][{{ $idx }}][image]" 
                                                :value="$photo['image'] ?? ''" 
                                                label="Photo {{ $idx + 1 }}" 
                                                help="Select from gallery or upload new (WebP <= 180 KB)." />

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-stone-600 uppercase tracking-wider mb-1">
                                                        Action Label
                                                    </label>
                                                    <input type="text" 
                                                           name="meta_data[photos][{{ $idx }}][label]" 
                                                           value="{{ $photo['label'] ?? '' }}" 
                                                           class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs font-semibold text-stone-900 focus:border-maroon focus:outline-none" 
                                                           placeholder="e.g. Free Book Loans">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-stone-600 uppercase tracking-wider mb-1">
                                                        Alt Text (Accessibility)
                                                    </label>
                                                    <input type="text" 
                                                           name="meta_data[photos][{{ $idx }}][alt]" 
                                                           value="{{ $photo['alt'] ?? '' }}" 
                                                           class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs text-stone-700 focus:border-maroon focus:outline-none" 
                                                           placeholder="Descriptive caption">
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @endif

                        @if ($page === 'home' && $section->section_key === 'causes')
                            @php
                                $defaultPillars = [
                                    ['title' => 'Education Support', 'ministry' => "Paul's academy", 'description' => 'Paying school fees, supplying curriculum textbooks, notebooks, uniforms, and educational kits to keep children in classrooms.', 'icon' => '🎓'],
                                    ['title' => 'Skills Acquisition', 'ministry' => "Peter's net", 'description' => 'Hands-on digital literacy, computer coding, and practical vocational training equipping youth with 21st-century self-reliance.', 'icon' => '💻'],
                                    ['title' => 'Advocacy', 'ministry' => 'Deborah congress', 'description' => 'Championing child protection, education policy awareness, equal opportunity for indigent girls, and youth rights.', 'icon' => '📢'],
                                    ['title' => 'Feeding families', 'ministry' => "Naomi's kitchen", 'description' => 'Targeted nutritional relief, food assistance, and hunger alleviation ensuring indigent pupils have the energy to learn.', 'icon' => '🍲'],
                                    ['title' => 'Transformative leadership Training', 'ministry' => "Priscilla's upper room", 'description' => 'Values-driven mentorship, ethical leadership training, and mindset transformation for emerging community leaders.', 'icon' => '🌟'],
                                    ['title' => 'Health awareness and support', 'ministry' => "Luke's Tent", 'description' => 'Health education, medical screenings, personal hygiene kits, and basic medical support for vulnerable children and families.', 'icon' => '🩺'],
                                ];
                                $pillars = old('meta_data.pillars', $section->meta_data['pillars'] ?? $defaultPillars);
                                $tagline = old('meta_data.tagline', $section->meta_data['tagline'] ?? 'AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD');
                            @endphp

                            <div class="pt-6 border-t border-stone-200/80 space-y-6">
                                <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-5">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-xl">🌟</span>
                                        <h4 class="text-sm font-bold text-stone-900">Foundational Tagline Banner</h4>
                                    </div>
                                    <p class="text-xs text-stone-600 mb-3">Displayed as a prominent theme banner above the 6 pillars matching the official foundation design from cause.jpg.</p>
                                    <input type="text" 
                                           name="meta_data[tagline]" 
                                           value="{{ $tagline }}" 
                                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 font-bold uppercase tracking-wider text-xs sm:text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                                           placeholder="AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD">
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-bold text-stone-900">The 6 Authentic Impact Pillars</h4>
                                            <p class="text-xs text-stone-500">Edit titles, ministry tags, icons, and descriptions for each of the 6 pillars</p>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-maroon/10 text-maroon">
                                            6 Key Pillars
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($pillars as $idx => $pillar)
                                            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/80 space-y-3">
                                                <div class="flex items-center justify-between pb-2 border-b border-stone-200/60">
                                                    <span class="text-xs font-bold text-stone-700">Pillar {{ $idx + 1 }}</span>
                                                    <input type="text" 
                                                           name="meta_data[pillars][{{ $idx }}][icon]" 
                                                           value="{{ $pillar['icon'] ?? '🌟' }}" 
                                                           class="w-12 px-2 py-1 text-center rounded-lg border border-stone-300 text-sm focus:border-maroon focus:outline-none" 
                                                           title="Icon / Emoji"
                                                           placeholder="Icon">
                                                </div>

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-stone-600 uppercase tracking-wider mb-1">
                                                            Pillar Title
                                                        </label>
                                                        <input type="text" 
                                                               name="meta_data[pillars][{{ $idx }}][title]" 
                                                               value="{{ $pillar['title'] ?? '' }}" 
                                                               required
                                                               class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs font-semibold text-stone-900 focus:border-maroon focus:outline-none" 
                                                               placeholder="e.g. Education Support">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-stone-600 uppercase tracking-wider mb-1">
                                                            Ministry / Branch
                                                        </label>
                                                        <input type="text" 
                                                               name="meta_data[pillars][{{ $idx }}][ministry]" 
                                                               value="{{ $pillar['ministry'] ?? '' }}" 
                                                               class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs text-stone-700 focus:border-maroon focus:outline-none" 
                                                               placeholder="e.g. Paul's academy">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-stone-600 uppercase tracking-wider mb-1">
                                                        Description
                                                    </label>
                                                    <textarea name="meta_data[pillars][{{ $idx }}][description]" 
                                                              rows="2" 
                                                              class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs text-stone-800 leading-relaxed focus:border-maroon focus:outline-none" 
                                                              placeholder="Descriptive summary...">{{ $pillar['description'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

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
