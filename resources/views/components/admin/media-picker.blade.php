@props([
    'name',
    'value' => null,
    'label' => 'Image',
    'required' => false,
    'help' => null,
])

@php
    $initialPath = old($name, $value);
    $initialUrl = $initialPath ? (str_starts_with($initialPath, 'http') ? $initialPath : asset($initialPath)) : null;
@endphp

<div x-data="{
    modalOpen: false,
    activeTab: 'browse',
    selectedPath: '{{ $initialPath }}',
    selectedUrl: '{{ $initialUrl }}',
    items: [],
    collections: [],
    years: [2026, 2025, 2024],
    yearFilter: 'all',
    sort: 'newest',
    loading: false,
    uploading: false,
    search: '',
    dateFilter: 'all',
    category: 'all',
    errorMessage: '',

    init() {
        this.$watch('modalOpen', value => {
            if (value && this.items.length === 0) {
                this.loadItems();
            }
        });
    },

    loadItems() {
        this.loading = true;
        this.errorMessage = '';
        let url = '{{ route('admin.media.picker-items') }}?';
        if (this.search) url += 'search=' + encodeURIComponent(this.search) + '&';
        if (this.dateFilter && this.dateFilter !== 'all') url += 'date_filter=' + encodeURIComponent(this.dateFilter) + '&';
        if (this.category && this.category !== 'all') url += 'category=' + encodeURIComponent(this.category) + '&';
        if (this.yearFilter && this.yearFilter !== 'all') url += 'year=' + encodeURIComponent(this.yearFilter) + '&';
        if (this.sort) url += 'sort=' + encodeURIComponent(this.sort) + '&';

        fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                this.items = data.items || [];
                if (data.collections) {
                    this.collections = data.collections;
                }
                if (data.years && data.years.length > 0) {
                    this.years = data.years;
                }
                this.loading = false;
            })
            .catch(err => {
                this.errorMessage = 'Failed to load media library items. Please try again.';
                this.loading = false;
            });
    },

    selectItem(item) {
        this.selectedPath = item.file_path;
        this.selectedUrl = item.image_url;
        this.modalOpen = false;
    },

    clearImage() {
        this.selectedPath = '';
        this.selectedUrl = '';
    },

    handleUpload(e) {
        const file = e.target.files[0];
        if (!file) return;

        this.uploading = true;
        this.errorMessage = '';

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route('admin.media.upload') }}', {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
        .then(async res => {
            const contentType = res.headers.get('content-type') || '';
            let data = null;
            if (contentType.includes('application/json')) {
                data = await res.json();
            }
            if (!res.ok) {
                let msg = data?.message;
                if (!msg && data?.errors) {
                    msg = Object.values(data.errors).flat()[0];
                }
                if (!msg) {
                    msg = res.status === 413
                        ? 'The uploaded file exceeds the maximum size allowed by the server.'
                        : `Upload failed with server status ${res.status}.`;
                }
                throw new Error(msg);
            }
            return data;
        })
        .then(data => {
            if (data && data.success && (data.item || data.image_path)) {
                const item = data.item || {};
                this.selectedPath = item.file_path || data.image_path;
                this.selectedUrl = item.image_url || data.image_url;
                this.uploading = false;
                this.modalOpen = false;
            } else {
                throw new Error(data?.message || 'Optimization failed');
            }
        })
        .catch(err => {
            this.errorMessage = err.message || 'Upload failed. Please verify the image file format and size.';
            this.uploading = false;
        })
        .finally(() => {
            if (e.target) e.target.value = '';
        });
    }
}" class="space-y-2">

    {{-- Field Label --}}
    @if ($label)
        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700">
            {{ $label }}
            @if ($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    {{-- Hidden Form Input submitting the path --}}
    <input type="hidden" name="{{ $name }}" :value="selectedPath" {{ $required ? 'required' : '' }}>

    {{-- Current Image Preview & Action Card --}}
    <div class="flex items-center gap-4 p-3.5 bg-stone-50 border border-stone-200 rounded-2xl">
        <div class="w-20 h-20 rounded-xl bg-stone-200 border border-stone-300 overflow-hidden shrink-0 flex items-center justify-center relative">
            <template x-if="selectedUrl">
                <img :src="selectedUrl" alt="Selected media" class="w-full h-full object-cover">
            </template>
            <template x-if="!selectedUrl">
                <div class="text-center p-2 text-stone-400 text-xs">
                    <span>No image</span>
                </div>
            </template>
        </div>

        <div class="flex-1 min-w-0">
            <template x-if="selectedPath">
                <div class="mb-2">
                    <p class="text-xs font-mono font-bold text-stone-800 truncate" x-text="selectedPath"></p>
                    <span class="text-[10px] text-emerald-600 font-semibold inline-flex items-center gap-1">
                        ✓ Selected & optimized WebP
                    </span>
                </div>
            </template>
            <template x-if="!selectedPath">
                <div class="mb-2">
                    <p class="text-xs font-medium text-stone-600">No image chosen yet.</p>
                    <p class="text-[11px] text-stone-400">Choose from Media Library or upload a new photo.</p>
                </div>
            </template>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" 
                        @click="activeTab = 'browse'; modalOpen = true" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-colors shadow-xs cursor-pointer flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Choose from Library
                </button>

                <button type="button" 
                        @click="activeTab = 'upload'; modalOpen = true" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-stone-700 bg-white border border-stone-300 hover:bg-stone-100 transition-colors cursor-pointer flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload New
                </button>

                <template x-if="selectedPath">
                    <button type="button" 
                            @click="clearImage()" 
                            class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                        Clear
                    </button>
                </template>
            </div>
        </div>
    </div>

    @if ($help)
        <p class="text-xs text-stone-500">{{ $help }}</p>
    @endif

    {{-- Media Picker Modal --}}
    <div x-show="modalOpen" 
         x-cloak 
         @keydown.escape.window="modalOpen = false" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        
        <div @click.away="modalOpen = false" 
             class="bg-white rounded-3xl max-w-4xl w-full max-h-[88vh] flex flex-col shadow-2xl border border-stone-200 overflow-hidden">
            
            {{-- Modal Header & Tabs --}}
            <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-stone-900">Select or Upload Media</h3>
                    <p class="text-xs text-stone-500">Pick from foundation media catalog (search by name, date uploaded) or upload an optimized image.</p>
                </div>
                <button type="button" @click="modalOpen = false" class="p-1.5 text-stone-400 hover:text-stone-700 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Tabs switcher --}}
            <div class="flex border-b border-stone-200 bg-stone-50 px-6 gap-6 text-xs font-bold uppercase tracking-wider">
                <button type="button" 
                        @click="activeTab = 'browse'" 
                        :class="activeTab === 'browse' ? 'border-maroon text-maroon' : 'border-transparent text-stone-500 hover:text-stone-800'"
                        class="py-3 border-b-2 transition-colors cursor-pointer flex items-center gap-2">
                    <span>🖼️ Media Library Catalog</span>
                </button>
                <button type="button" 
                        @click="activeTab = 'upload'" 
                        :class="activeTab === 'upload' ? 'border-maroon text-maroon' : 'border-transparent text-stone-500 hover:text-stone-800'"
                        class="py-3 border-b-2 transition-colors cursor-pointer flex items-center gap-2">
                    <span>⚡ Upload & Optimize (&le; 180 KB)</span>
                </button>
            </div>

            {{-- Tab 1: Browse Media Library --}}
            <div x-show="activeTab === 'browse'" class="flex-1 overflow-y-auto p-6 space-y-4">
                {{-- Quick Year Filter Chips --}}
                <div class="flex flex-wrap items-center gap-1.5 pb-2 border-b border-stone-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 mr-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Year:</span>
                    </span>
                    <button type="button" 
                            @click="yearFilter = 'all'; loadItems()" 
                            :class="yearFilter === 'all' ? 'bg-maroon text-white shadow-2xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200 hover:text-stone-900'"
                            class="px-2.5 py-1 rounded-full text-xs font-semibold transition-all cursor-pointer">
                        All Years
                    </button>
                    <template x-for="yr in years" :key="yr">
                        <button type="button" 
                                @click="yearFilter = yr; loadItems()" 
                                :class="yearFilter == yr ? 'bg-maroon text-white shadow-2xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200 hover:text-stone-900'"
                                class="px-2.5 py-1 rounded-full text-xs font-semibold transition-all cursor-pointer"
                                x-text="yr">
                        </button>
                    </template>
                </div>

                {{-- Search & Multi-filter Controls --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                    <div>
                        <input type="text" 
                               x-model="search" 
                               @input.debounce.300ms="loadItems()" 
                               placeholder="Search photos..." 
                               class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                    </div>
                    <div>
                        <select x-model="category" 
                                @change="loadItems()" 
                                class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            <option value="all">All Categories</option>
                            <optgroup label="System Categories">
                                <option value="gallery">All Gallery Photos</option>
                                <option value="site">Site & CMS Assets</option>
                                <option value="general">Unassigned Media</option>
                            </optgroup>
                            <template x-if="collections && collections.length > 0">
                                <optgroup label="Collections & Programs">
                                    <template x-for="col in collections" :key="col.id">
                                        <option :value="col.slug" x-text="col.title + (!col.is_public ? ' (Internal)' : '')"></option>
                                    </template>
                                </optgroup>
                            </template>
                        </select>
                    </div>
                    <div>
                        <select x-model="dateFilter" 
                                @change="loadItems()" 
                                class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            <option value="all">All Dates Uploaded</option>
                            <option value="today">Uploaded Today</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="past_year">Past Year</option>
                        </select>
                    </div>
                    <div>
                        <select x-model="sort" 
                                @change="loadItems()" 
                                class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="year_desc">Year (Newest)</option>
                            <option value="year_asc">Year (Oldest)</option>
                            <option value="name_asc">Name (A &rarr; Z)</option>
                            <option value="size_asc">Smallest Size</option>
                            <option value="size_desc">Largest Size</option>
                        </select>
                    </div>
                </div>

                {{-- Loading Spinner --}}
                <div x-show="loading" class="py-16 text-center text-stone-500 text-xs">
                    <div class="w-8 h-8 border-2 border-maroon border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                    Searching foundation media...
                </div>

                {{-- Error notification --}}
                <div x-show="errorMessage" x-text="errorMessage" class="p-3 bg-rose-50 text-rose-700 text-xs rounded-xl border border-rose-200"></div>

                {{-- Items Grid --}}
                <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    <template x-for="item in items" :key="item.id">
                        <div @click="selectItem(item)" 
                             class="group relative aspect-square bg-stone-100 rounded-xl overflow-hidden border border-stone-200 hover:border-maroon hover:ring-2 hover:ring-maroon/30 transition-all cursor-pointer">
                            <img :src="item.image_url" :alt="item.alt_text || item.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            
                            {{-- Info overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-2 flex flex-col justify-end text-white text-[10px]">
                                <span class="font-bold truncate" x-text="item.title"></span>
                                <span class="text-stone-300 truncate" x-text="item.formatted_size + (item.year ? ' • ' + item.year : '') + ' • ' + item.date_formatted"></span>
                            </div>

                            {{-- Badges --}}
                            <div class="absolute top-1.5 right-1.5 flex items-center gap-1">
                                <span class="px-1.5 py-0.5 rounded bg-black/70 backdrop-blur-xs text-white text-[9px] font-bold" x-text="item.formatted_size"></span>
                            </div>
                            <div class="absolute top-1.5 left-1.5 flex items-center gap-1" x-show="item.year">
                                <span class="px-1.5 py-0.5 rounded bg-maroon/90 backdrop-blur-xs text-white text-[9px] font-bold" x-text="item.year"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="!loading && items.length === 0" class="py-12 text-center text-stone-400 text-xs">
                    No matching photos found in the media library.
                </div>
            </div>

            {{-- Tab 2: Upload New --}}
            <div x-show="activeTab === 'upload'" class="flex-1 p-8 space-y-4 text-center">
                <div class="max-w-md mx-auto p-8 border-2 border-dashed border-stone-300 rounded-3xl space-y-3 bg-stone-50/50 hover:bg-stone-50 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-maroon/10 text-maroon flex items-center justify-center mx-auto text-2xl">
                        ⚡
                    </div>
                    <h4 class="font-bold text-stone-900 text-sm">Upload & Automatically Optimize to WebP</h4>
                    <p class="text-xs text-stone-500">Supports JPEG, PNG, or WebP. The image will be compressed to &le; 180 KB WebP, cataloged in the Media Library, and selected automatically.</p>

                    <label class="inline-block mt-3 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-xs cursor-pointer">
                        <span x-show="!uploading">Select File to Upload</span>
                        <span x-show="uploading">Optimizing Image...</span>
                        <input type="file" accept="image/*" @change="handleUpload($event)" class="hidden" :disabled="uploading">
                    </label>

                    <div x-show="uploading" class="pt-2 text-xs text-maroon font-semibold flex items-center justify-center gap-2">
                        <div class="w-4 h-4 border-2 border-maroon border-t-transparent rounded-full animate-spin"></div>
                        <span>Processing with GD & cataloging...</span>
                    </div>

                    <div x-show="errorMessage" x-text="errorMessage" class="pt-2 text-xs text-rose-600 font-semibold"></div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-3 border-t border-stone-100 bg-stone-50 flex items-center justify-end">
                <button type="button" @click="modalOpen = false" class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
