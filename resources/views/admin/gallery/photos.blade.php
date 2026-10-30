<x-admin-layout title="Manage Photos - {{ $album->title }}">
    <div x-data="{
        activeSection: 'library', // Default is 'library', other option is 'upload'
        selectedPhotos: [],
        selectAll: false,
        editingPhoto: null,
        uploadFilesCount: 0,

        // Media Library Browser State
        libraryItems: [],
        selectedMediaIds: [],
        loadingLibrary: false,
        librarySearch: '',
        libraryYear: 'all',
        libraryCategory: 'all',
        libraryPage: 1,
        libraryPerPage: 60,
        libraryHasMore: false,
        libraryTotal: 0,
        libraryCollections: [],
        libraryYears: [2026, 2025, 2024],
        libraryErrorMessage: '',

        init() {
            this.loadLibraryItems(true);
        },

        loadLibraryItems(reset = true) {
            if (reset) {
                this.libraryPage = 1;
                this.loadingLibrary = true;
                this.libraryErrorMessage = '';
            }

            let url = '{{ route('admin.media.picker-items') }}?page=' + this.libraryPage + '&per_page=' + this.libraryPerPage + '&';
            if (this.librarySearch) url += 'search=' + encodeURIComponent(this.librarySearch) + '&';
            if (this.libraryCategory && this.libraryCategory !== 'all') url += 'category=' + encodeURIComponent(this.libraryCategory) + '&';
            if (this.libraryYear && this.libraryYear !== 'all') url += 'year=' + encodeURIComponent(this.libraryYear) + '&';

            fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    const newItems = data.items || [];
                    if (reset) {
                        this.libraryItems = newItems;
                    } else {
                        this.libraryItems = [...this.libraryItems, ...newItems];
                    }
                    this.libraryTotal = data.total || 0;
                    this.libraryHasMore = Boolean(data.has_more);
                    if (data.collections) {
                        this.libraryCollections = data.collections;
                    }
                    if (data.years && data.years.length > 0) {
                        this.libraryYears = data.years;
                    }
                    this.loadingLibrary = false;
                })
                .catch(() => {
                    this.libraryErrorMessage = 'Failed to load library items. Please retry.';
                    this.loadingLibrary = false;
                });
        },

        loadMoreLibrary() {
            if (this.libraryHasMore && !this.loadingLibrary) {
                this.libraryPage++;
                this.loadLibraryItems(false);
            }
        },

        toggleMediaSelect(id) {
            if (this.selectedMediaIds.includes(id)) {
                this.selectedMediaIds = this.selectedMediaIds.filter(item => item !== id);
            } else {
                this.selectedMediaIds.push(id);
            }
        },

        toggleAllLibrary() {
            const visibleIds = this.libraryItems.map(item => item.id);
            const allSelected = visibleIds.every(id => this.selectedMediaIds.includes(id));
            if (allSelected) {
                this.selectedMediaIds = this.selectedMediaIds.filter(id => !visibleIds.includes(id));
            } else {
                this.selectedMediaIds = [...new Set([...this.selectedMediaIds, ...visibleIds])];
            }
        },

        clearMediaSelection() {
            this.selectedMediaIds = [];
        },

        handleFileSelect(e) {
            this.uploadFilesCount = e.target.files.length;
        },

        toggleAll() {
            if (this.selectAll) {
                this.selectedPhotos = {{ json_encode($photos->pluck('id')->values()) }};
            } else {
                this.selectedPhotos = [];
            }
        },

        openEdit(photo) {
            this.editingPhoto = { ...photo };
        },

        closeEdit() {
            this.editingPhoto = null;
        }
    }" class="space-y-8">
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
                    {{ $album->kicker ? $album->kicker . ' • ' : '' }}Select photos from the Media Hub or upload new photos for this collection.
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

        {{-- Segmented Tab Switcher --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            <div class="flex border-b border-stone-200 bg-stone-50/70 p-1.5 gap-1.5">
                <button type="button" 
                        @click="activeSection = 'library'" 
                        :class="activeSection === 'library' ? 'bg-white text-maroon font-bold shadow-xs border-stone-200' : 'text-stone-600 hover:text-stone-900 font-medium border-transparent'"
                        class="flex-1 py-3 px-4 rounded-xl text-xs sm:text-sm border transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>1. Pick from Central Media Library (Default)</span>
                    <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Primary</span>
                </button>
                <button type="button" 
                        @click="activeSection = 'upload'" 
                        :class="activeSection === 'upload' ? 'bg-white text-maroon font-bold shadow-xs border-stone-200' : 'text-stone-600 hover:text-stone-900 font-medium border-transparent'"
                        class="flex-1 py-3 px-4 rounded-xl text-xs sm:text-sm border transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>2. Upload New Photos (Direct Ingestion)</span>
                </button>
            </div>

            {{-- TAB 1: Select from Central Media Library --}}
            <div x-show="activeSection === 'library'" class="p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-stone-100">
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Choose Photos from Central Media Hub</h2>
                        <p class="text-xs text-stone-500 mt-0.5">
                            Select one or multiple existing photos to attach to <strong>{{ $album->title }}</strong> without duplicate uploads.
                        </p>
                    </div>

                    {{-- Form with selected photo IDs --}}
                    <form method="POST" action="{{ route('admin.gallery.photos.attach-media', $album) }}" class="flex items-center gap-2">
                        @csrf
                        <template x-for="id in selectedMediaIds" :key="id">
                            <input type="hidden" name="media_item_ids[]" :value="id">
                        </template>

                        <button type="button" 
                                @click="clearMediaSelection()" 
                                x-show="selectedMediaIds.length > 0"
                                class="px-3 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 border border-stone-200 rounded-xl hover:bg-stone-50 transition-colors cursor-pointer">
                            Clear (<span x-text="selectedMediaIds.length"></span>)
                        </button>

                        <button type="submit" 
                                :disabled="selectedMediaIds.length === 0" 
                                :class="selectedMediaIds.length > 0 ? 'bg-maroon hover:bg-crimson text-white shadow-sm cursor-pointer' : 'bg-stone-200 text-stone-400 cursor-not-allowed'"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                            <span>Attach Selected</span>
                            <span x-show="selectedMediaIds.length > 0" class="px-2 py-0.5 rounded-full bg-white/20 text-white text-[10px]" x-text="selectedMediaIds.length"></span>
                        </button>
                    </form>
                </div>

                {{-- Filters: Search, Collection, Year --}}
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-stone-50/80 p-3 rounded-xl border border-stone-200">
                    <div class="sm:col-span-2">
                        <input type="text" 
                               x-model="librarySearch" 
                               @input.debounce.400ms="loadLibraryItems(true)"
                               placeholder="Search photos by title, filename or caption..." 
                               class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-xs bg-white focus:outline-none focus:border-maroon focus:ring-1 focus:ring-maroon">
                    </div>
                    <div>
                        <select x-model="libraryCategory" 
                                @change="loadLibraryItems(true)"
                                class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs bg-white focus:outline-none focus:border-maroon">
                            <option value="all">All Collections</option>
                            <template x-for="c in libraryCollections" :key="c.slug || c.id">
                                <option :value="c.slug || c.id" x-text="c.title"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <select x-model="libraryYear" 
                                @change="loadLibraryItems(true)"
                                class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs bg-white focus:outline-none focus:border-maroon">
                            <option value="all">All Years</option>
                            <template x-for="y in libraryYears" :key="y">
                                <option :value="y" x-text="y"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Toolbar: Select All on page & count --}}
                <div class="flex items-center justify-between text-xs text-stone-600 px-1">
                    <label class="flex items-center gap-2 cursor-pointer font-medium hover:text-stone-900">
                        <input type="checkbox" 
                               @click="toggleAllLibrary()"
                               :checked="libraryItems.length > 0 && libraryItems.every(i => selectedMediaIds.includes(i.id))"
                               class="rounded text-maroon focus:ring-maroon/20 border-stone-300 w-4 h-4 cursor-pointer">
                        <span>Select All Visible Photos</span>
                    </label>
                    <span>
                        Found <strong class="text-stone-900" x-text="libraryTotal">0</strong> library photo(s)
                    </span>
                </div>

                {{-- Grid of Central Media Hub items --}}
                <div class="relative min-h-[220px]">
                    <div x-show="loadingLibrary" class="absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center z-20 rounded-xl">
                        <div class="flex items-center gap-2 text-xs font-semibold text-maroon">
                            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span>Loading photos...</span>
                        </div>
                    </div>

                    <template x-if="libraryItems.length === 0 && !loadingLibrary">
                        <div class="p-8 text-center text-xs text-stone-400 bg-stone-50 rounded-xl border border-dashed border-stone-200">
                            No library photos match your filters. Try adjusting search or switch to "Upload New Photos".
                        </div>
                    </template>

                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                        <template x-for="item in libraryItems" :key="item.id">
                            <div @click="toggleMediaSelect(item.id)"
                                 :class="selectedMediaIds.includes(item.id) ? 'ring-3 ring-maroon border-maroon scale-[0.98]' : 'hover:border-stone-400'"
                                 class="relative aspect-square rounded-xl border-2 border-stone-200 overflow-hidden bg-stone-100 cursor-pointer group transition-all">
                                <img :src="item.image_url" 
                                     :alt="item.title || item.filename"
                                     loading="lazy" 
                                     class="w-full h-full object-cover">

                                {{-- Checkbox Badge --}}
                                <div class="absolute top-1.5 left-1.5 z-10">
                                    <div :class="selectedMediaIds.includes(item.id) ? 'bg-maroon text-white border-maroon' : 'bg-white/80 text-transparent border-stone-300'"
                                         class="w-5 h-5 rounded-md border flex items-center justify-center shadow-xs transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Year badge --}}
                                <template x-if="item.year">
                                    <span class="absolute top-1.5 right-1.5 bg-black/70 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-xs" x-text="item.year"></span>
                                </template>

                                {{-- Hover Info --}}
                                <div class="absolute inset-x-0 bottom-0 p-1.5 bg-gradient-to-t from-black/80 to-transparent text-[10px] text-white truncate opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span x-text="item.title || item.filename"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Load More Button --}}
                    <div x-show="libraryHasMore" class="pt-4 text-center">
                        <button type="button" 
                                @click="loadMoreLibrary()"
                                class="px-5 py-2 rounded-xl text-xs font-semibold bg-stone-100 hover:bg-stone-200 text-stone-700 transition-colors cursor-pointer border border-stone-200">
                            Load More Photos
                        </button>
                    </div>
                </div>
            </div>

            {{-- TAB 2: Upload New Photos --}}
            <div x-show="activeSection === 'upload'" class="p-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Upload & Optimize Photos (Bulk Uploader)</h2>
                        <p class="text-xs text-stone-500 mt-0.5">
                            Files are automatically optimized to modern WebP (&le; 180 KB) and cataloged directly into the Central Media Hub.
                        </p>
                    </div>
                    <span class="text-xs font-medium px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg">
                        ⚡ Auto-WebP Ingestion
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.gallery.photos.upload', $album) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                                   @change="handleFileSelect($event)"
                                   class="w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 cursor-pointer">
                            <template x-if="uploadFilesCount > 0">
                                <p class="text-xs font-semibold text-emerald-700 mt-1.5 flex items-center gap-1">
                                    <span>✓</span>
                                    <span x-text="uploadFilesCount + ' file(s) selected for bulk upload'"></span>
                                </p>
                            </template>
                            @error('photos')
                                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                            @enderror
                            @error('photos.*')
                                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Default Caption --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="caption" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                                    Caption <span class="text-stone-400 font-normal lowercase">(optional)</span>
                                </label>
                                <x-admin.ai-button 
                                    for="caption" 
                                    field-name="caption" 
                                    field-label="Photo Caption" 
                                    page-context="Gallery Album: {{ $album->title }}" 
                                    section-context="{{ $album->title }} ({{ $album->kicker ?? 'Programme' }})" 
                                />
                            </div>
                            <input type="text" 
                                   id="caption" 
                                   name="caption" 
                                   placeholder="e.g. Students in coding session" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors">
                        </div>

                        {{-- Year --}}
                        <div>
                            <label for="year" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                Event / Activity Year <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="year" 
                                   name="year" 
                                   placeholder="e.g. 2024, 2025" 
                                   value="{{ date('Y') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors">
                        </div>

                        {{-- Default Alt Text & Submit --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="alt_text" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                                    Alt Text <span class="text-stone-400 font-normal lowercase">(optional)</span>
                                </label>
                                <x-admin.ai-button 
                                    for="alt_text" 
                                    field-name="alt_text" 
                                    field-label="Image Alt Text" 
                                    page-context="Gallery Album: {{ $album->title }}" 
                                    section-context="{{ $album->title }}" 
                                    :sibling-selectors="['caption' => '#caption']" 
                                />
                            </div>
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
        </div>

        {{-- Photos Grid and Bulk Actions for this Album --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-stone-200">
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-stone-700">
                        <input type="checkbox" 
                               x-model="selectAll" 
                               @change="toggleAll()" 
                               class="rounded text-maroon focus:ring-maroon/20 border-stone-300 w-4 h-4">
                        <span>Select All in Album (<span x-text="{{ $photos->count() }}"></span>)</span>
                    </label>
                    <span class="text-xs text-stone-400">•</span>
                    <span class="text-xs text-stone-500">
                        <span x-text="selectedPhotos.length" class="font-bold text-stone-900">0</span> selected
                    </span>
                </div>

                {{-- Bulk Delete Form --}}
                <div x-show="selectedPhotos.length > 0" x-cloak class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.gallery.photos.bulk-destroy', $album) }}" 
                          @submit.prevent="if(confirm('Are you sure you want to delete ' + selectedPhotos.length + ' selected photo(s)? This cannot be undone.')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedPhotos" :key="id">
                            <input type="hidden" name="photo_ids[]" :value="id">
                        </template>
                        <button type="submit" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-xs cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Selected (<span x-text="selectedPhotos.length"></span>)
                        </button>
                    </form>
                </div>
            </div>

            @if ($photos->isEmpty())
                <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        📸
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No photos in this album yet.</p>
                    <p class="text-xs text-stone-400">Use the picker above to select from your Central Media Hub or upload new photos.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($photos as $photo)
                        <div class="bg-white rounded-xl border border-stone-200 overflow-hidden shadow-xs hover:shadow transition-shadow group flex flex-col justify-between relative"
                             :class="{ 'ring-2 ring-maroon border-maroon': selectedPhotos.includes({{ $photo->id }}) }">
                            
                            {{-- Checkbox overlay --}}
                            <div class="absolute top-2 left-2 z-10 bg-white/90 backdrop-blur-xs rounded-md p-1 shadow-xs">
                                <input type="checkbox" 
                                       value="{{ $photo->id }}" 
                                       x-model="selectedPhotos" 
                                       class="rounded text-maroon focus:ring-maroon/20 border-stone-300 w-4 h-4 cursor-pointer">
                            </div>

                            <div class="relative aspect-4/3 bg-stone-100 overflow-hidden">
                                <img src="{{ $photo->image_url }}" 
                                     alt="{{ $photo->alt_text ?? $album->title }}" 
                                     loading="lazy" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                
                                {{-- Year badge --}}
                                @if ($photo->year)
                                    <div class="absolute top-2 right-2 bg-maroon/90 backdrop-blur-xs text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-xs">
                                        {{ $photo->year }}
                                    </div>
                                @endif
                                
                                {{-- Visibility badge --}}
                                @if ($photo->is_public)
                                    <div class="absolute bottom-1 right-2 bg-emerald-600/90 backdrop-blur-xs text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-xs">
                                        Public
                                    </div>
                                @else
                                    <div class="absolute bottom-1 right-2 bg-stone-800/90 backdrop-blur-xs text-stone-300 text-[9px] font-bold px-1.5 py-0.5 rounded shadow-xs">
                                        Archive
                                    </div>
                                @endif

                                <div class="absolute bottom-1 left-2 text-white/90 text-[10px] font-mono drop-shadow">
                                    #{{ $photo->sort_order }}
                                </div>
                            </div>

                            <div class="p-3 space-y-2 flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-xs font-medium text-stone-900 line-clamp-2" title="{{ $photo->caption }}">
                                        {{ $photo->caption ?: 'No caption' }}
                                    </p>
                                    @if ($photo->alt_text && $photo->alt_text !== $album->title)
                                        <p class="text-[11px] text-stone-400 line-clamp-1 mt-0.5" title="{{ $photo->alt_text }}">
                                            Alt: {{ $photo->alt_text }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-stone-100 text-xs">
                                    <button type="button" 
                                            @click="openEdit({{ json_encode($photo) }})" 
                                            class="text-stone-600 hover:text-maroon font-semibold flex items-center gap-1 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('admin.gallery.photos.destroy', [$album, $photo]) }}" 
                                          @submit.prevent="if(confirm('Are you sure you want to remove this photo from the album?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Edit Photo Modal --}}
        <div x-show="editingPhoto !== null" 
             x-cloak 
             class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="closeEdit()" 
                 class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-4 border border-stone-200">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900">Edit Photo Details</h3>
                    <button type="button" @click="closeEdit()" class="text-stone-400 hover:text-stone-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <template x-if="editingPhoto">
                    <form method="POST" :action="'/admin/gallery/{{ $album->id }}/photos/' + editingPhoto.id" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="aspect-16/9 bg-stone-100 rounded-xl overflow-hidden relative border border-stone-200">
                            <img :src="editingPhoto.image_url" id="gallery_edit_photo_img" class="w-full h-full object-contain">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="gallery_edit_photo_caption" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Caption</label>
                                <x-admin.ai-button 
                                    for="gallery_edit_photo_caption" 
                                    field-name="caption" 
                                    field-label="Photo Caption" 
                                    page-context="Gallery Album: {{ $album->title }}" 
                                    section-context="Photo in {{ $album->title }}" 
                                    image-selector="#gallery_edit_photo_img" 
                                    :sibling-selectors="['alt_text' => '#gallery_edit_photo_alt']" 
                                />
                            </div>
                            <input type="text" id="gallery_edit_photo_caption" name="caption" x-model="editingPhoto.caption" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Event Year</label>
                                <input type="text" name="year" x-model="editingPhoto.year" placeholder="e.g. 2024" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Sort Order</label>
                                <input type="number" name="sort_order" x-model="editingPhoto.sort_order" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="gallery_edit_photo_alt" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Alt Text</label>
                                <x-admin.ai-button 
                                    for="gallery_edit_photo_alt" 
                                    field-name="alt_text" 
                                    field-label="Photo Alt Text" 
                                    page-context="Gallery Album: {{ $album->title }}" 
                                    section-context="Photo in {{ $album->title }}" 
                                    image-selector="#gallery_edit_photo_img" 
                                    :sibling-selectors="['caption' => '#gallery_edit_photo_caption']" 
                                />
                            </div>
                            <input type="text" id="gallery_edit_photo_alt" name="alt_text" x-model="editingPhoto.alt_text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        </div>

                        {{-- Public Gallery Visibility Toggle --}}
                        <div class="p-3 bg-stone-50 rounded-xl border border-stone-200 flex items-center justify-between">
                            <div>
                                <label for="gallery_edit_photo_is_public" class="block text-xs font-bold text-stone-800 cursor-pointer">Show in Public Gallery</label>
                                <p class="text-[11px] text-stone-500">Uncheck to keep as an internal archive photo.</p>
                            </div>
                            <input type="hidden" name="is_public" value="0">
                            <input type="checkbox" id="gallery_edit_photo_is_public" name="is_public" value="1" x-model="editingPhoto.is_public" class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon cursor-pointer">
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-stone-100">
                            <button type="button" @click="closeEdit()" class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson rounded-xl shadow-xs cursor-pointer">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-admin-layout>
