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
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6 {{ ! $section->is_active ? 'opacity-90 bg-stone-50/50' : '' }}">
                    {{-- Section Header with Status & Reordering Controls --}}
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-stone-100">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl {{ $section->is_active ? 'bg-amber-50 text-maroon' : 'bg-stone-200 text-stone-500' }} flex items-center justify-center font-bold text-xs uppercase shadow-2xs">
                                {{ substr($section->section_key, 0, 2) }}
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-base font-bold text-stone-900">
                                        Section: {{ ucwords(str_replace('_', ' ', $section->section_key)) }}
                                    </h2>
                                    @if ($section->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active (Visible)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-stone-100 text-stone-600 border border-stone-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                            Hidden from Live Page
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-mono text-stone-400">section_key: {{ $section->section_key }}</span>
                            </div>
                        </div>

                        {{-- Action Controls: 1-Click Toggle Visibility & Move Up / Move Down --}}
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- 1-Click Toggle Visibility --}}
                            <form method="POST" action="{{ route('admin.pages.sections.toggle-status', ['page' => $page, 'section' => $section->section_key]) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all cursor-pointer {{ $section->is_active ? 'bg-stone-100 hover:bg-stone-200 text-stone-700 border-stone-300' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border-emerald-300' }}">
                                    <span>{{ $section->is_active ? '👁️ Hide on Site' : '✓ Show on Site' }}</span>
                                </button>
                            </form>

                            {{-- Reorder Controls (Move Up / Down) --}}
                            <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-xl border border-stone-200">
                                <span class="text-[11px] font-mono font-bold px-2 text-stone-600">
                                    #{{ $loop->iteration }}
                                </span>

                                {{-- Move Up --}}
                                <form method="POST" action="{{ route('admin.pages.sections.move-up', ['page' => $page, 'section' => $section->section_key]) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            @if ($loop->first) disabled @endif
                                            class="p-1 px-2 rounded-lg border text-xs font-bold transition-all {{ $loop->first ? 'opacity-30 cursor-not-allowed bg-transparent border-transparent text-stone-400' : 'bg-white hover:bg-stone-50 border-stone-300 text-stone-700 cursor-pointer shadow-2xs' }}"
                                            title="Move section up">
                                        ▲
                                    </button>
                                </form>

                                {{-- Move Down --}}
                                <form method="POST" action="{{ route('admin.pages.sections.move-down', ['page' => $page, 'section' => $section->section_key]) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            @if ($loop->last) disabled @endif
                                            class="p-1 px-2 rounded-lg border text-xs font-bold transition-all {{ $loop->last ? 'opacity-30 cursor-not-allowed bg-transparent border-transparent text-stone-400' : 'bg-white hover:bg-stone-50 border-stone-300 text-stone-700 cursor-pointer shadow-2xs' }}"
                                            title="Move section down">
                                        ▼
                                    </button>
                                </form>
                            </div>

                            <span class="text-[11px] text-stone-400 pl-1 hidden sm:inline">
                                {{ $section->updated_at ? $section->updated_at->diffForHumans() : 'Never' }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.pages.sections.update', ['page' => $page, 'section' => $section->section_key]) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Section Settings (Active Toggle & Numeric Sort Order) --}}
                        <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $section->is_active) ? 'checked' : '' }} 
                                       class="w-4 h-4 rounded text-maroon focus:ring-maroon border-stone-300 cursor-pointer">
                                <div>
                                    <span class="text-xs font-bold text-stone-800 uppercase tracking-wider block">
                                        Section Active & Visible
                                    </span>
                                    <span class="text-[11px] text-stone-500 block">
                                        Uncheck to immediately hide this section from the live public website.
                                    </span>
                                </div>
                            </label>

                            <div class="flex items-center gap-2">
                                <label for="{{ $section->section_key }}_sort_order" class="text-xs font-bold text-stone-600 uppercase tracking-wider whitespace-nowrap">
                                    Sort Order:
                                </label>
                                <input type="number" 
                                       id="{{ $section->section_key }}_sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $section->sort_order) }}" 
                                       class="w-20 px-3 py-1.5 rounded-xl border border-stone-300 text-xs text-stone-900 font-mono text-center focus:border-maroon focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Headline -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="{{ $section->section_key }}_headline" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                                        Section Headline
                                    </label>
                                    <x-admin.ai-button 
                                        for="{{ $section->section_key }}_headline" 
                                        field-name="headline" 
                                        field-label="Section Headline" 
                                        page-context="{{ ucfirst($page) }} Page" 
                                        section-context="{{ $section->headline ?: $section->section_key }}"
                                        :image-path="$section->image_path"
                                        :sibling-selectors="['subheadline' => '#'.$section->section_key.'_subheadline', 'body' => '#'.$section->section_key.'_body_text']"
                                    />
                                </div>
                                <input type="text" 
                                       id="{{ $section->section_key }}_headline" 
                                       name="headline" 
                                       value="{{ old('headline', $section->headline) }}" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                                       placeholder="Enter main headline...">
                            </div>

                            <!-- Subheadline -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="{{ $section->section_key }}_subheadline" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                                        Subheadline / Eyebrow Badge
                                    </label>
                                    <x-admin.ai-button 
                                        for="{{ $section->section_key }}_subheadline" 
                                        field-name="subheadline" 
                                        field-label="Subheadline" 
                                        page-context="{{ ucfirst($page) }} Page" 
                                        section-context="{{ $section->headline ?: $section->section_key }}"
                                        :image-path="$section->image_path"
                                        :sibling-selectors="['headline' => '#'.$section->section_key.'_headline', 'body' => '#'.$section->section_key.'_body_text']"
                                    />
                                </div>
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
                            <div class="flex items-center justify-between mb-2">
                                <label for="{{ $section->section_key }}_body_text" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                                    Section Description / Body Copy
                                </label>
                                <x-admin.ai-button 
                                    for="{{ $section->section_key }}_body_text" 
                                    field-name="body_text" 
                                    field-label="Section Description" 
                                    page-context="{{ ucfirst($page) }} Page" 
                                    section-context="{{ $section->headline ?: $section->section_key }}"
                                    :image-path="$section->image_path"
                                    :sibling-selectors="['headline' => '#'.$section->section_key.'_headline', 'subheadline' => '#'.$section->section_key.'_subheadline']"
                                />
                            </div>
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
                                <div class="flex items-center justify-between mb-2">
                                    <label for="{{ $section->section_key }}_button_text" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                                        Button Text (Optional)
                                    </label>
                                    <x-admin.ai-button 
                                        for="{{ $section->section_key }}_button_text" 
                                        field-name="button_text" 
                                        field-label="Button Call to Action" 
                                        page-context="{{ ucfirst($page) }} Page" 
                                        section-context="{{ $section->headline ?: $section->section_key }}"
                                        :sibling-selectors="['headline' => '#'.$section->section_key.'_headline']"
                                    />
                                </div>
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
