@php
    $pageTitle = isset($selectedAlbum) && $selectedAlbum
        ? $selectedAlbum->title . ' | Photo Gallery - Dzuels Educational Foundation'
        : 'Gallery & Visual Archive | Dzuels Educational Foundation';
    $pageDescription = isset($selectedAlbum) && $selectedAlbum && !empty($selectedAlbum->description)
        ? $selectedAlbum->description
        : (isset($selectedAlbum) && $selectedAlbum
            ? 'Explore photos from ' . $selectedAlbum->title . ' celebrating student innovation and community outreach.'
            : 'Explore moments of impact, student innovation, spelling bees, and community outreach from Dzuels Educational Foundation in Ijero-Ekiti, Nigeria.');
    $pageCanonical = isset($selectedAlbum) && $selectedAlbum
        ? route('gallery.album', $selectedAlbum->slug)
        : url('/gallery');
    $pageBreadcrumbs = [
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Gallery', 'url' => url('/gallery')],
    ];
    if (isset($selectedAlbum) && $selectedAlbum) {
        $pageBreadcrumbs[] = [
            'name' => $selectedAlbum->title,
            'url' => route('gallery.album', $selectedAlbum->slug),
        ];
    }
@endphp
<x-layout 
    :title="$pageTitle"
    :description="$pageDescription"
    :canonical="$pageCanonical"
    :breadcrumbs="$pageBreadcrumbs">

    @php
        $allPhotos = [];
        $totalPhotos = 0;
        foreach ($albums as $album) {
            foreach ($album->photos as $photo) {
                $totalPhotos++;
                $label = $photo->display_label;
                $allPhotos[] = [
                    'id' => $photo->id,
                    'album_id' => $album->id,
                    'album_slug' => $album->slug,
                    'album_title' => $album->title,
                    'album_kicker' => $album->kicker ?? 'Dzuels Outreach',
                    'url' => $photo->image_url,
                    'caption' => $photo->caption,
                    'year' => $photo->year,
                    'display_label' => $label,
                    'alt' => $photo->alt_text ?: $label,
                ];
            }
        }
    @endphp

    <script>
        function galleryApp(allPhotos, initialAlbum) {
            return {
                allPhotos: allPhotos,
                currentAlbum: initialAlbum || 'all',
                lightboxOpen: false,
                activePhotoIndex: 0,

                init() {
                    this.$watch('lightboxOpen', value => {
                        if (value) {
                            document.body.classList.add('overflow-hidden');
                        } else {
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                },

                get filteredPhotos() {
                    if (this.currentAlbum === 'all') {
                        return this.allPhotos;
                    }
                    return this.allPhotos.filter(p => p.album_slug === this.currentAlbum);
                },

                get currentPhoto() {
                    if (this.filteredPhotos.length === 0) return null;
                    return this.filteredPhotos[this.activePhotoIndex] || null;
                },

                setAlbum(slug) {
                    this.currentAlbum = slug;
                    this.activePhotoIndex = 0;
                },

                openLightboxByPhotoId(photoId) {
                    const idx = this.filteredPhotos.findIndex(p => p.id === photoId);
                    if (idx !== -1) {
                        this.activePhotoIndex = idx;
                        this.lightboxOpen = true;
                    }
                },

                openLightbox(index) {
                    this.activePhotoIndex = index;
                    this.lightboxOpen = true;
                },

                closeLightbox() {
                    this.lightboxOpen = false;
                },

                nextPhoto() {
                    if (this.filteredPhotos.length === 0) return;
                    this.activePhotoIndex = (this.activePhotoIndex + 1) % this.filteredPhotos.length;
                },

                prevPhoto() {
                    if (this.filteredPhotos.length === 0) return;
                    this.activePhotoIndex = (this.activePhotoIndex - 1 + this.filteredPhotos.length) % this.filteredPhotos.length;
                }
            };
        }
        window.galleryApp = galleryApp;
    </script>

    <div x-data="galleryApp({{ Js::from($allPhotos) }}, '{{ $activeSlug ?? 'all' }}')" 
         x-init="init()" 
         class="min-h-screen bg-[var(--bg)] transition-colors">

        {{-- 1. Hero Section --}}
        <section class="relative py-16 lg:py-24 bg-[var(--surface)] border-b border-[var(--border)] overflow-hidden transition-colors">
            {{-- Decorative ambient background glows --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-maroon/5 dark:bg-maroon/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[var(--accent-cream)]/30 dark:bg-[var(--accent-cream)]/5 blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    {{-- Left Copy --}}
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-maroon/10 dark:bg-maroon/30 text-maroon dark:text-[var(--accent-cream)] border border-maroon/20">
                            <span>🖼️</span> Curated Visual Archive
                        </span>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[var(--heading)] tracking-tight leading-tight">
                            Real Moments From The <span class="font-serif italic font-normal text-crimson dark:text-[var(--accent-cream)]">Work We Do</span>
                        </h1>

                        <p class="text-lg sm:text-xl text-[var(--text-muted)] max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                            Browse an authentic visual archive of learners, mentors, innovation festivals, literacy competitions, and community educational empowerment across Ekiti State.
                        </p>

                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                            <a href="#photo-gallery" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                                <span>Explore {{ $totalPhotos }} Photos</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </a>
                            <a href="{{ route('events.index') }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-sm font-semibold bg-[var(--surface-alt)] text-[var(--text)] hover:text-maroon dark:hover:text-[var(--accent-cream)] border border-[var(--border)] transition-colors">
                                Upcoming Events &rarr;
                            </a>
                        </div>
                    </div>

                    {{-- Right Feature Card --}}
                    <div class="lg:col-span-5">
                        <div class="relative rounded-3xl overflow-hidden border border-[var(--border)] shadow-xl bg-[var(--surface)] group">
                            <div class="aspect-4/3 w-full overflow-hidden bg-stone-100">
                                <img src="{{ asset('front/images/gallery/optimized/itd-2025-05.webp') }}" 
                                     alt="Students gathered during Innovation and Tech Day" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 p-4 rounded-2xl bg-black/60 backdrop-blur-md text-white border border-white/10 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[var(--accent-cream)] block">Featured Highlight</span>
                                    <p class="text-sm font-bold truncate">Innovation and Tech Day 2025</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-mint text-stone-900 shrink-0">
                                    Active Impact
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 2. Interactive Filter Chips Bar --}}
        <section id="photo-gallery" class="py-8 bg-[var(--bg)] border-b border-[var(--border)] sticky top-20 z-30 backdrop-blur-md bg-[var(--bg)]/90 transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    {{-- Filter Chips --}}
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 scrollbar-none w-full sm:w-auto" role="tablist" aria-label="Gallery collections">
                        {{-- All Photos Chip --}}
                        <button type="button" 
                                @click="setAlbum('all')" 
                                :class="currentAlbum === 'all' ? 'bg-maroon text-white shadow-sm' : 'bg-[var(--surface)] text-[var(--text-muted)] hover:text-[var(--text)] hover:bg-[var(--surface-alt)] border border-[var(--border)]'"
                                class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-2">
                            <span>All Collections</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px]" :class="currentAlbum === 'all' ? 'bg-white/20 text-white' : 'bg-stone-200 dark:bg-stone-800 text-[var(--text)]'">
                                {{ $totalPhotos }}
                            </span>
                        </button>

                        {{-- Individual Album Chips --}}
                        @foreach ($albums as $album)
                            <button type="button" 
                                    @click="setAlbum('{{ $album->slug }}')" 
                                    :class="currentAlbum === '{{ $album->slug }}' ? 'bg-maroon text-white shadow-sm' : 'bg-[var(--surface)] text-[var(--text-muted)] hover:text-[var(--text)] hover:bg-[var(--surface-alt)] border border-[var(--border)]'"
                                    class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all cursor-pointer flex items-center gap-2">
                                <span>{{ $album->title }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="currentAlbum === '{{ $album->slug }}' ? 'bg-white/20 text-white' : 'bg-stone-200 dark:bg-stone-800 text-[var(--text)]'">
                                    {{ $album->photos->count() }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Counter indicator --}}
                    <div class="hidden sm:block text-xs text-[var(--text-muted)]">
                        Showing <strong class="text-[var(--heading)]" x-text="filteredPhotos.length"></strong> photos
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. Photo Masonry Grid --}}
        <section class="py-12 lg:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Empty State Fallback --}}
                <div x-show="filteredPhotos.length === 0" 
                     x-cloak 
                     class="py-20 text-center rounded-3xl bg-[var(--surface)] border border-[var(--border)] p-8 max-w-xl mx-auto">
                    <div class="w-16 h-16 rounded-full bg-stone-100 dark:bg-stone-800 text-3xl flex items-center justify-center mx-auto mb-4">
                        📷
                    </div>
                    <h3 class="text-xl font-bold text-[var(--heading)] mb-2">No photos found in this collection</h3>
                    <p class="text-sm text-[var(--text-muted)] mb-6">Photos for this album will be uploaded shortly. In the meantime, explore our other gallery archives.</p>
                    <button type="button" 
                            @click="setAlbum('all')" 
                            class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-colors cursor-pointer">
                        View All Photos
                    </button>
                </div>

                {{-- Masonry Grid (Server-Rendered for Instant Display, Filtered by Alpine) --}}
                <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4" id="gallery-masonry-grid">
                    @foreach ($allPhotos as $index => $photo)
                        <div class="gallery-photo-item break-inside-avoid rounded-2xl overflow-hidden bg-[var(--surface)] border border-[var(--border)] shadow-xs hover:shadow-xl transition-all duration-300 group cursor-pointer relative"
                             data-album="{{ $photo['album_slug'] }}"
                             x-show="currentAlbum === 'all' || currentAlbum === '{{ $photo['album_slug'] }}'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             @click="openLightboxByPhotoId({{ $photo['id'] }})">
                            
                            {{-- Image --}}
                            <div class="relative overflow-hidden bg-stone-100 dark:bg-stone-800">
                                <img src="{{ $photo['url'] }}" 
                                     alt="{{ $photo['alt'] }}" 
                                     loading="lazy" 
                                     class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500 block">
                                
                                {{-- Dark Hover Gradient Overlay with Captions --}}
                                <div class="absolute inset-0 bg-linear-to-t from-black/85 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <span class="text-[10px] uppercase font-bold tracking-wider text-[var(--accent-cream)] block">
                                            {{ $photo['album_title'] }}
                                        </span>
                                        @if ($photo['year'])
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-maroon/90 text-white shadow-xs">
                                                {{ $photo['year'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-semibold line-clamp-2 mt-0.5">
                                        {{ $photo['display_label'] }}
                                    </p>
                                    <div class="mt-2 flex items-center justify-between text-[10px] text-stone-300">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Fullscreen
                                        </span>
                                        <span>🔍 Expand</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- 4. Interactive Keyboard Lightbox Modal --}}
        <div x-show="lightboxOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="closeLightbox()"
             @keydown.arrow-left.window="prevPhoto()"
             @keydown.arrow-right.window="nextPhoto()"
             class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex flex-col justify-between p-4 sm:p-6 select-none"
             aria-modal="true"
             role="dialog">

            {{-- Top Controls Bar --}}
            <div class="flex items-center justify-between text-white z-10">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-xs text-[var(--accent-cream)] border border-white/10"
                          x-text="currentPhoto ? currentPhoto.album_title : ''">
                    </span>
                    <template x-if="currentPhoto && currentPhoto.year">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-maroon text-white"
                              x-text="currentPhoto.year">
                        </span>
                    </template>
                    <span class="text-xs text-stone-400 ml-2">
                        Photo <strong class="text-white" x-text="activePhotoIndex + 1"></strong> of <strong class="text-white" x-text="filteredPhotos.length"></strong>
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" 
                            @click="closeLightbox()" 
                            class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-stone-300 hover:text-white transition-colors cursor-pointer"
                            title="Close (Esc)">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Center Photo Container with Navigation Arrows --}}
            <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden" 
                 @click.self="closeLightbox()">
                
                {{-- Prev Button --}}
                <button type="button" 
                        @click="prevPhoto()" 
                        class="absolute left-2 sm:left-6 z-20 p-3 rounded-full bg-black/50 hover:bg-black/80 text-white transition-all hover:scale-110 cursor-pointer border border-white/10"
                        title="Previous Photo (Left Arrow)">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Photo Image --}}
                <template x-if="currentPhoto">
                    <img :src="currentPhoto.url" 
                         :alt="currentPhoto.alt" 
                         class="max-h-[78vh] w-auto max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300">
                </template>

                {{-- Next Button --}}
                <button type="button" 
                        @click="nextPhoto()" 
                        class="absolute right-2 sm:right-6 z-20 p-3 rounded-full bg-black/50 hover:bg-black/80 text-white transition-all hover:scale-110 cursor-pointer border border-white/10"
                        title="Next Photo (Right Arrow)">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            {{-- Bottom Caption Bar --}}
            <div class="text-center text-white z-10 max-w-3xl mx-auto px-4">
                <p class="text-sm font-semibold text-stone-100" x-text="currentPhoto ? (currentPhoto.display_label || currentPhoto.caption || currentPhoto.alt) : ''"></p>
                <p class="text-xs text-stone-400 mt-1">
                    <span x-text="currentPhoto ? currentPhoto.album_kicker : ''"></span>
                    <template x-if="currentPhoto && currentPhoto.year">
                        <span> • <span x-text="currentPhoto.year"></span></span>
                    </template>
                    <span> • Use ← and → keys to navigate, Esc to close</span>
                </p>
            </div>
        </div>

        {{-- 5. Bottom Call to Action Banner --}}
        <section class="py-16 bg-[var(--surface-alt)] border-t border-[var(--border)] transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-mint/20 text-emerald-800 dark:text-mint border border-mint/30">
                    🤝 Partner With Us
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Want to Help Us Create More Moments Like These?
                </h2>
                <p class="text-base text-[var(--text-muted)] max-w-2xl mx-auto">
                    Whether as a volunteer mentor, corporate sponsor, or community partner, join hands with Dzuels Educational Foundation in expanding technology education across Nigeria.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <a href="{{ url('/get-involved') }}" 
                       class="px-6 py-3 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg cursor-pointer">
                        Get Involved &rarr;
                    </a>
                    <a href="{{ url('/contact') }}" 
                       class="px-6 py-3 rounded-full text-sm font-semibold bg-[var(--surface)] text-[var(--text)] hover:text-maroon border border-[var(--border)] transition-colors">
                        Contact Foundation Office
                    </a>
                </div>
            </div>
        </section>
    </div>

</x-layout>
