@props([
    'name' => 'gallery_photos',
    'photos' => [],
    'albumId' => null,
    'albums' => [],
    'title' => 'Photo Gallery Showcase',
    'subtitle' => 'Attach photography from the Central Media Hub or upload new photos to display in the public lightbox gallery.',
])

@php
    $normalizedPhotos = [];
    $rawPhotos = old($name, $photos);

    if (is_string($rawPhotos)) {
        $decoded = json_decode($rawPhotos, true);
        $rawPhotos = is_array($decoded) ? $decoded : [];
    }

    if (is_iterable($rawPhotos)) {
        foreach ($rawPhotos as $p) {
            if (is_string($p)) {
                $normalizedPhotos[] = [
                    'path' => $p,
                    'caption' => '',
                    'alt_text' => '',
                    'media_item_id' => null,
                ];
            } elseif (is_array($p) && !empty($p['path'])) {
                $normalizedPhotos[] = [
                    'path' => $p['path'],
                    'caption' => $p['caption'] ?? '',
                    'alt_text' => $p['alt_text'] ?? '',
                    'media_item_id' => $p['media_item_id'] ?? null,
                ];
            }
        }
    }
@endphp

<div x-data="{
    photos: {{ json_encode($normalizedPhotos) }},
    albumId: '{{ old('gallery_album_id', $albumId) }}',
    newUploadsCount: 0,

    // Hub Picker Modal State
    modalOpen: false,
    hubItems: [],
    selectedHubIds: [],
    loadingHub: false,
    hubSearch: '',
    hubYear: 'all',
    hubCategory: 'all',
    hubPage: 1,
    hubPerPage: 60,
    hubHasMore: false,
    hubTotal: 0,
    hubCollections: [],
    hubYears: [2026, 2025, 2024],

    init() {
        this.$watch('modalOpen', value => {
            if (value && this.hubItems.length === 0) {
                this.loadHubItems(true);
            }
        });
    },

    loadHubItems(reset = true) {
        if (reset) {
            this.hubPage = 1;
            this.loadingHub = true;
        }

        let url = '{{ route('admin.media.picker-items') }}?page=' + this.hubPage + '&per_page=' + this.hubPerPage + '&';
        if (this.hubSearch) url += 'search=' + encodeURIComponent(this.hubSearch) + '&';
        if (this.hubCategory && this.hubCategory !== 'all') url += 'category=' + encodeURIComponent(this.hubCategory) + '&';
        if (this.hubYear && this.hubYear !== 'all') url += 'year=' + encodeURIComponent(this.hubYear) + '&';

        fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                const newItems = data.items || [];
                if (reset) {
                    this.hubItems = newItems;
                } else {
                    this.hubItems = [...this.hubItems, ...newItems];
                }
                this.hubTotal = data.total || 0;
                this.hubHasMore = Boolean(data.has_more);
                if (data.collections) {
                    this.hubCollections = data.collections;
                }
                if (data.years && data.years.length > 0) {
                    this.hubYears = data.years;
                }
                this.loadingHub = false;
            })
            .catch(() => {
                this.loadingHub = false;
            });
    },

    loadMoreHub() {
        if (this.hubHasMore && !this.loadingHub) {
            this.hubPage++;
            this.loadHubItems(false);
        }
    },

    toggleHubItem(item) {
        const index = this.selectedHubIds.indexOf(item.id);
        if (index > -1) {
            this.selectedHubIds.splice(index, 1);
        } else {
            this.selectedHubIds.push(item.id);
        }
    },

    addSelectedFromHub() {
        const chosen = this.hubItems.filter(item => this.selectedHubIds.includes(item.id));
        chosen.forEach(item => {
            // Avoid duplicate paths
            if (!this.photos.some(p => p.path === item.file_path)) {
                this.photos.push({
                    path: item.file_path,
                    caption: item.caption || item.title || '',
                    alt_text: item.alt_text || item.title || '',
                    media_item_id: item.id
                });
            }
        });
        this.selectedHubIds = [];
        this.modalOpen = false;
    },

    removePhoto(index) {
        this.photos.splice(index, 1);
    },

    moveUp(index) {
        if (index > 0) {
            const temp = this.photos[index];
            this.photos[index] = this.photos[index - 1];
            this.photos[index - 1] = temp;
        }
    },

    moveDown(index) {
        if (index < this.photos.length - 1) {
            const temp = this.photos[index];
            this.photos[index] = this.photos[index + 1];
            this.photos[index + 1] = temp;
        }
    },

    handleUploadSelect(e) {
        this.newUploadsCount = e.target.files.length;
    },

    resolveImageUrl(path) {
        if (!path) return '';
        if (path.startsWith('http://') || path.startsWith('https://')) return path;
        return '{{ asset('') }}' + path.replace(/^\//, '');
    }
}" class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 space-y-6 shadow-xs">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-stone-100 gap-4">
        <div>
            <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                <span>📷</span> {{ $title }}
            </h2>
            <p class="text-xs text-stone-500 mt-1">
                {{ $subtitle }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" 
                    @click="modalOpen = true"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-xs cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Add from Media Hub</span>
            </button>
        </div>
    </div>

    {{-- Linked Album Selector (Optional Collection Link) --}}
    @if (!empty($albums) && count($albums) > 0)
        <div class="p-4 bg-stone-50 rounded-xl border border-stone-200 grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
            <div class="sm:col-span-1">
                <label for="gallery_album_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                    Linked Collection (Album)
                </label>
                <p class="text-[11px] text-stone-500">
                    Connect an existing gallery album collection.
                </p>
            </div>
            <div class="sm:col-span-2">
                <select name="gallery_album_id" 
                        id="gallery_album_id" 
                        x-model="albumId"
                        class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs bg-white focus:outline-none focus:border-maroon focus:ring-1 focus:ring-maroon">
                    <option value="">-- No Linked Collection (Use Direct Attached Photos) --</option>
                    @foreach ($albums as $alb)
                        <option value="{{ $alb->id }}" {{ old('gallery_album_id', $albumId) == $alb->id ? 'selected' : '' }}>
                            {{ $alb->title }} ({{ $alb->photos_count ?? $alb->photos()->count() }} photos)
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    {{-- Serialized JSON Field for form submission --}}
    <input type="hidden" name="{{ $name }}" :value="JSON.stringify(photos)">

    {{-- Attached Photos Grid --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between text-xs text-stone-500">
            <span>Attached Photos (<strong class="text-stone-900" x-text="photos.length">0</strong>)</span>
            <span class="text-[11px] text-stone-400">Photos will render in the public interactive lightbox showcase</span>
        </div>

        <template x-if="photos.length === 0">
            <div class="p-8 text-center bg-stone-50/70 rounded-2xl border-2 border-dashed border-stone-200 space-y-2">
                <div class="text-2xl text-stone-300">🖼️</div>
                <p class="text-xs font-semibold text-stone-600">No custom photos attached yet</p>
                <p class="text-[11px] text-stone-400 max-w-sm mx-auto">
                    Click <strong>Add from Media Hub</strong> above or upload images below to showcase on the public page.
                </p>
            </div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" x-show="photos.length > 0">
            <template x-for="(photo, index) in photos" :key="photo.path + '-' + index">
                <div class="bg-stone-50 rounded-xl border border-stone-200 overflow-hidden shadow-xs flex flex-col justify-between group">
                    <div class="relative aspect-4/3 bg-stone-200 overflow-hidden">
                        <img :src="resolveImageUrl(photo.path)" 
                             :alt="photo.caption || 'Attached photo'" 
                             class="w-full h-full object-cover">
                        
                        {{-- Reorder & Delete Floating Buttons --}}
                        <div class="absolute top-2 right-2 flex items-center gap-1 bg-black/60 backdrop-blur-xs rounded-lg p-1 opacity-90 group-hover:opacity-100 transition-opacity">
                            <button type="button" 
                                    @click="moveUp(index)" 
                                    :disabled="index === 0" 
                                    :class="index === 0 ? 'text-stone-500 cursor-not-allowed' : 'text-white hover:text-amber-300 cursor-pointer'"
                                    class="p-1" title="Move earlier">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button type="button" 
                                    @click="moveDown(index)" 
                                    :disabled="index === photos.length - 1" 
                                    :class="index === photos.length - 1 ? 'text-stone-500 cursor-not-allowed' : 'text-white hover:text-amber-300 cursor-pointer'"
                                    class="p-1" title="Move later">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <button type="button" 
                                    @click="removePhoto(index)" 
                                    class="p-1 text-rose-300 hover:text-rose-400 cursor-pointer" title="Remove photo">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <span class="absolute bottom-2 left-2 bg-black/70 text-white text-[10px] font-mono px-1.5 py-0.5 rounded" x-text="'#' + (index + 1)"></span>
                    </div>

                    {{-- Caption input --}}
                    <div class="p-2.5 bg-white border-t border-stone-200">
                        <input type="text" 
                               x-model="photo.caption" 
                               placeholder="Photo caption (optional)..." 
                               class="w-full px-2.5 py-1 text-xs border border-stone-200 rounded-lg focus:outline-none focus:border-maroon focus:ring-1 focus:ring-maroon bg-stone-50/50">
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Direct File Upload Card (Auto WebP Ingestion) --}}
    <div class="pt-4 border-t border-stone-100">
        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
            Or Direct-Upload New Photos
        </label>
        <p class="text-[11px] text-stone-500 mb-3">
            Select files to upload immediately with this form. Files are auto-optimized to WebP (&le; 180 KB) and cataloged in the Central Media Hub.
        </p>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="file" 
                   name="new_gallery_photos[]" 
                   multiple 
                   accept="image/*" 
                   @change="handleUploadSelect($event)"
                   class="w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 cursor-pointer">

            <template x-if="newUploadsCount > 0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 whitespace-nowrap">
                    <span>✓</span>
                    <span x-text="newUploadsCount + ' new image(s) queued for upload'"></span>
                </span>
            </template>
        </div>
    </div>

    {{-- Media Hub Picker Modal --}}
    <div x-show="modalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="modalOpen = false" 
             class="bg-white rounded-2xl max-w-4xl w-full p-6 shadow-2xl space-y-4 border border-stone-200 max-h-[90vh] flex flex-col">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                <div>
                    <h3 class="text-base font-bold text-stone-900">Select Photos from Central Media Hub</h3>
                    <p class="text-xs text-stone-500">Pick photography to attach to this gallery.</p>
                </div>
                <button type="button" @click="modalOpen = false" class="text-stone-400 hover:text-stone-700 cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Filter Bar --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-stone-50 p-3 rounded-xl border border-stone-200">
                <div class="sm:col-span-2">
                    <input type="text" 
                           x-model="hubSearch" 
                           @input.debounce.400ms="loadHubItems(true)"
                           placeholder="Search by title or caption..." 
                           class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs bg-white focus:outline-none focus:border-maroon">
                </div>
                <div>
                    <select x-model="hubCategory" 
                            @change="loadHubItems(true)"
                            class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs bg-white focus:outline-none focus:border-maroon">
                        <option value="all">All Collections</option>
                        <template x-for="c in hubCollections" :key="c.slug || c.id">
                            <option :value="c.slug || c.id" x-text="c.title"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <select x-model="hubYear" 
                            @change="loadHubItems(true)"
                            class="w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs bg-white focus:outline-none focus:border-maroon">
                        <option value="all">All Years</option>
                        <template x-for="y in hubYears" :key="y">
                            <option :value="y" x-text="y"></option>
                        </template>
                    </select>
                </div>
            </div>

            {{-- Grid of Photos --}}
            <div class="flex-1 overflow-y-auto min-h-[300px] relative">
                <div x-show="loadingHub" class="absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center z-10">
                    <span class="text-xs font-semibold text-maroon flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        Loading Hub Photos...
                    </span>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3 p-1">
                    <template x-for="item in hubItems" :key="item.id">
                        <div @click="toggleHubItem(item)" 
                             :class="selectedHubIds.includes(item.id) ? 'ring-3 ring-maroon border-maroon scale-[0.98]' : 'hover:border-stone-400'"
                             class="relative aspect-square rounded-xl border-2 border-stone-200 overflow-hidden bg-stone-100 cursor-pointer group transition-all">
                            <img :src="item.image_url" :alt="item.title" class="w-full h-full object-cover">
                            
                            {{-- Checkmark Badge --}}
                            <div class="absolute top-1 left-1 z-10">
                                <div :class="selectedHubIds.includes(item.id) ? 'bg-maroon text-white border-maroon' : 'bg-white/80 text-transparent border-stone-300'"
                                     class="w-5 h-5 rounded-md border flex items-center justify-center shadow-xs transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>

                            <template x-if="item.year">
                                <span class="absolute top-1 right-1 bg-black/70 text-white text-[9px] font-bold px-1.5 py-0.5 rounded" x-text="item.year"></span>
                            </template>

                            <div class="absolute inset-x-0 bottom-0 p-1 bg-black/70 text-[9px] text-white truncate opacity-0 group-hover:opacity-100 transition-opacity">
                                <span x-text="item.title || item.filename"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="hubHasMore" class="pt-4 text-center">
                    <button type="button" 
                            @click="loadMoreHub()"
                            class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-stone-100 hover:bg-stone-200 text-stone-700 transition-colors border border-stone-200 cursor-pointer">
                        Load More Photos
                    </button>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                <span class="text-xs text-stone-500">
                    <strong class="text-stone-900" x-text="selectedHubIds.length">0</strong> photos selected
                </span>

                <div class="flex items-center gap-3">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 cursor-pointer">
                        Cancel
                    </button>
                    <button type="button" 
                            @click="addSelectedFromHub()" 
                            :disabled="selectedHubIds.length === 0"
                            :class="selectedHubIds.length > 0 ? 'bg-maroon hover:bg-crimson text-white shadow-xs cursor-pointer' : 'bg-stone-200 text-stone-400 cursor-not-allowed'"
                            class="px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition-all">
                        Attach Selected (<span x-text="selectedHubIds.length"></span>)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

