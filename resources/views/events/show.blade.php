<x-layout 
    :title="$event->seo_title ?? ($event->title . ' | Dzuels Educational Foundation')"
    :description="$event->seo_description ?? $event->summary"
    :canonical="route('events.show', $event->slug)"
    :og-image="$event->featured_image_url ?? $event->banner_image_url ?? asset('front/images/dzuelsCollage.webp')"
    og-type="article"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Events', 'url' => route('events.index')],
        ['name' => $event->title, 'url' => route('events.show', $event->slug)]
    ]">

    <x-slot:schema>
        @php
            $eventSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'Event',
                'name' => $event->title,
                'description' => $event->summary ?: strip_tags($event->description ?? ''),
                'image' => $event->banner_image_url,
                'startDate' => $event->event_date?->toIso8601String() ?? now()->toIso8601String(),
                'endDate' => $event->end_date?->toIso8601String() ?? ($event->event_date ? $event->event_date->copy()->addHours(6)->toIso8601String() : null),
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'eventStatus' => 'https://schema.org/EventScheduled',
                'isAccessibleForFree' => true,
                'inLanguage' => 'en',
                'location' => [
                    '@type' => 'Place',
                    'name' => $event->venue ?: 'AAoJ Memorial Learning Center, Ijero-Ekiti',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $event->venue ?: 'AAoJ Memorial Learning Center',
                        'addressLocality' => 'Ijero-Ekiti',
                        'addressRegion' => 'Ekiti State',
                        'addressCountry' => 'NG',
                    ],
                ],
                'organizer' => [
                    '@type' => 'EducationalOrganization',
                    'name' => 'Dzuels Educational Foundation',
                    'url' => url('/'),
                    'logo' => asset('front/images/logo.png'),
                ],
                'audience' => [
                    '@type' => 'Audience',
                    'audienceType' => 'Students, Educators, Innovators & Community Members',
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '0',
                    'priceCurrency' => 'NGN',
                    'availability' => $event->is_past ? 'https://schema.org/SoldOut' : ($event->is_registration_open ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut'),
                    'validFrom' => $event->created_at?->toIso8601String() ?? now()->toIso8601String(),
                    'url' => route('events.show', $event->slug),
                ],
            ];

            if ($event->sponsors && $event->sponsors->isNotEmpty()) {
                $eventSchema['sponsor'] = $event->sponsors->map(function ($sponsor) {
                    return [
                        '@type' => 'Organization',
                        'name' => $sponsor->name,
                        'url' => $sponsor->website_url ?: url('/'),
                    ];
                })->all();
            }
        @endphp
        <script type="application/ld+json">
        {!! json_encode($eventSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    </x-slot:schema>

    {{-- Breadcrumb Navigation --}}
    <div class="bg-[var(--surface)] border-b border-[var(--border)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <nav class="flex items-center gap-2 text-xs font-medium text-[var(--text-muted)]" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-maroon transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('events.index') }}" class="hover:text-maroon transition-colors">Events</a>
                <span>/</span>
                <span class="text-[var(--text-strong)] font-semibold truncate max-w-xs sm:max-w-md">{{ $event->title }}</span>
            </nav>
        </div>
    </div>

    {{-- Event Header Section --}}
    <section class="py-12 lg:py-16 bg-[var(--surface)] border-b border-[var(--border)] transition-colors relative overflow-hidden">
        {{-- Decorative ambient background glows --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-maroon/5 dark:bg-maroon/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[var(--accent-cream)]/30 dark:bg-[var(--accent-cream)]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Date Badge --}}
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-maroon/10 text-maroon dark:bg-maroon/30 dark:text-[var(--accent-cream)] border border-maroon/20">
                    <span>📅</span> {{ $event->formatted_date }}
                </span>

                {{-- Time Badge --}}
                @if ($event->event_date)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-medium bg-stone-100 dark:bg-stone-800 text-stone-700 dark:text-stone-300">
                        <span>⏰</span> {{ $event->formatted_time }}
                    </span>
                @endif

                {{-- Dynamic Event Status Badge --}}
                @if ($event->is_past)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-stone-900/95 text-white shadow-xs border border-white/20">
                        <span>✅</span> {{ $event->status_label }}
                    </span>
                @elseif ($event->is_today)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow-md animate-pulse">
                        <span>🔥</span> Happening Today
                    </span>
                @elseif ($event->is_registration_open)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Registration Open
                    </span>
                @else
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold bg-stone-200 dark:bg-stone-800 text-stone-700 dark:text-stone-300">
                        Registration Closed
                    </span>
                @endif
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[var(--heading)] tracking-tight leading-tight max-w-4xl">
                {{ $event->title }}
            </h1>

            @if ($event->theme)
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs sm:text-sm font-bold bg-[var(--accent-cream)]/20 text-maroon dark:text-[var(--accent-cream)] border border-[var(--accent-cream)]/40">
                        <span>✨ Theme:</span>
                        <span class="italic font-bold">"{{ $event->theme }}"</span>
                    </span>
                </div>
            @endif

            @if ($event->objective)
                <div class="max-w-4xl p-5 rounded-2xl bg-maroon/5 dark:bg-maroon/20 border border-maroon/20 text-maroon dark:text-[var(--accent-cream)] shadow-xs">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl mt-0.5">🎯</span>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-widest text-crimson dark:text-mint block mb-1">Key Event Objective</span>
                            <p class="text-base sm:text-lg font-bold leading-relaxed text-[var(--heading)]">
                                "{{ $event->objective }}"
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tech Day Festival Impact Metrics Bar --}}
            @if (!empty($isTechDay))
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-[var(--border)] max-w-4xl">
                    <div class="p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)] text-center shadow-xs">
                        <span class="text-2xl sm:text-3xl font-black text-maroon dark:text-[var(--accent-cream)] block">300+</span>
                        <span class="text-xs font-semibold text-[var(--text-muted)] mt-0.5 block">Student Delegates</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)] text-center shadow-xs">
                        <span class="text-2xl sm:text-3xl font-black text-maroon dark:text-[var(--accent-cream)] block">22</span>
                        <span class="text-xs font-semibold text-[var(--text-muted)] mt-0.5 block">Cohort Graduates</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)] text-center shadow-xs">
                        <span class="text-2xl sm:text-3xl font-black text-maroon dark:text-[var(--accent-cream)] block">10</span>
                        <span class="text-xs font-semibold text-[var(--text-muted)] mt-0.5 block">Hands-on Stations</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)] text-center shadow-xs">
                        <span class="text-2xl sm:text-3xl font-black text-maroon dark:text-[var(--accent-cream)] block">100%</span>
                        <span class="text-xs font-semibold text-[var(--text-muted)] mt-0.5 block">Free & Sponsored</span>
                    </div>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-6 text-sm text-[var(--text-muted)] pt-2 border-t border-[var(--border)]">
                <div class="flex items-center gap-2">
                    <span>📍</span>
                    <span class="font-medium text-[var(--text)]">{{ $event->venue ?? 'Ijero-Ekiti, Ekiti State, Nigeria' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>🎟️</span>
                    <span class="font-medium text-[var(--text)]">Free Community Admission</span>
                </div>
            </div>

            {{-- Multi-Year Tech Day Edition Switcher (Dynamic Date Status) --}}
            @if (!empty($isTechDay) && isset($techDayEditions) && $techDayEditions->isNotEmpty())
                <div class="mt-4 p-4 sm:p-5 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🚀</span>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-crimson dark:text-mint block">
                                Ijero Tech Day Annual Editions
                            </span>
                            <span class="text-xs sm:text-sm font-bold text-[var(--heading)]">
                                Switch Festival Edition:
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($techDayEditions as $edition)
                            @php
                                $isActive = $edition->id === $event->id;
                            @endphp
                            <a href="{{ route('events.show', $edition->slug) }}" 
                               class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center gap-1.5 {{ $isActive 
                                   ? 'bg-maroon text-white shadow-md ring-2 ring-maroon/30 scale-102' 
                                   : 'bg-[var(--surface)] text-[var(--text)] border border-[var(--border)] hover:border-maroon/50 hover:bg-maroon/5' }}">
                                <span>{{ $edition->event_date?->year }}</span>
                                <span class="text-[10px] opacity-80 font-normal">
                                    ({{ $edition->is_future ? 'Upcoming Flagship' : ($edition->slug === 'ijero-tech-day-2024' ? 'Inaugural' : ($edition->slug === 'ijero-tech-day-2025' ? '2nd Edition' : 'Concluded')) }})
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Dynamic Countdown Timer (Only For Future Events) --}}
            @if ($event->is_future && $event->event_date)
                <div x-data="{
                    target: new Date('{{ $event->event_date->toIso8601String() }}').getTime(),
                    days: 0, hours: 0, minutes: 0, seconds: 0,
                    update() {
                        const now = new Date().getTime();
                        const distance = this.target - now;
                        if (distance > 0) {
                            this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        }
                    }
                }" x-init="update(); setInterval(() => update(), 1000)" class="p-6 rounded-3xl bg-linear-to-r from-maroon via-crimson to-maroon text-white shadow-xl max-w-4xl">
                    <div class="text-center sm:text-left sm:flex sm:items-center sm:justify-between gap-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-widest text-[var(--accent-cream)] block">Countdown to Flagship Gathering</span>
                            <h4 class="text-lg font-black mt-0.5">Event Begins In:</h4>
                        </div>
                        <div class="flex items-center justify-center gap-3 mt-4 sm:mt-0 font-mono">
                            <div class="text-center px-3 py-2 rounded-xl bg-black/30 backdrop-blur-xs min-w-[55px]">
                                <span class="text-2xl font-black block" x-text="days">0</span>
                                <span class="text-[9px] uppercase tracking-wider text-stone-300">Days</span>
                            </div>
                            <div class="text-center px-3 py-2 rounded-xl bg-black/30 backdrop-blur-xs min-w-[55px]">
                                <span class="text-2xl font-black block" x-text="hours">0</span>
                                <span class="text-[9px] uppercase tracking-wider text-stone-300">Hours</span>
                            </div>
                            <div class="text-center px-3 py-2 rounded-xl bg-black/30 backdrop-blur-xs min-w-[55px]">
                                <span class="text-2xl font-black block" x-text="minutes">0</span>
                                <span class="text-[9px] uppercase tracking-wider text-stone-300">Mins</span>
                            </div>
                            <div class="text-center px-3 py-2 rounded-xl bg-black/30 backdrop-blur-xs min-w-[55px]">
                                <span class="text-2xl font-black block" x-text="seconds">0</span>
                                <span class="text-[9px] uppercase tracking-wider text-stone-300">Secs</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Main Content & Registration Layout --}}
    <section class="py-16 lg:py-20 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                {{-- Left Column: Banner, Narrative, Schedule & Highlights (7 Cols) --}}
                <div class="lg:col-span-7 space-y-12">
                    {{-- Banner Media Card --}}
                    <div class="rounded-3xl overflow-hidden border border-[var(--border)] shadow-xl bg-stone-900 aspect-16/9">
                        <img src="{{ $event->banner_image_url }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-full object-cover object-center">
                    </div>

                    {{-- Narrative Overview --}}
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-10 shadow-sm space-y-6">
                        <h2 class="text-2xl font-bold text-[var(--heading)] pb-4 border-b border-[var(--border)]">
                            About This Event
                        </h2>

                        <div class="text-[var(--text)] text-base sm:text-lg leading-relaxed space-y-4">
                            @foreach (explode("\n\n", $event->description) as $paragraph)
                                @if (trim($paragraph))
                                    <p>{{ trim($paragraph) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Schedule Timeline --}}
                    @if (!empty($event->schedule))
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-10 shadow-sm space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b border-[var(--border)]">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                                        Agenda & Schedule
                                    </span>
                                    <h2 class="text-2xl font-bold text-[var(--heading)] mt-0.5">
                                        Event Schedule Highlights
                                    </h2>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[var(--bg)] text-[var(--text-muted)] border border-[var(--border)]">
                                    {{ count($event->schedule) }} Sessions
                                </span>
                            </div>

                            <div class="space-y-4">
                                @foreach ($event->schedule as $index => $slot)
                                    <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg)] border border-[var(--border)] flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:border-maroon/30 transition-colors">
                                        <div class="space-y-1">
                                            <span class="font-mono text-xs font-bold text-maroon dark:text-[var(--accent-cream)] block">
                                                {{ $slot['time'] ?? 'TBA' }}
                                            </span>
                                            <h3 class="font-bold text-[var(--heading)] text-base">
                                                {{ $slot['title'] ?? '' }}
                                            </h3>
                                        </div>
                                        @if (!empty($slot['speaker']))
                                            <div class="shrink-0">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[var(--surface)] text-[var(--text-strong)] border border-[var(--border)]">
                                                    🎙️ {{ $slot['speaker'] }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Event Highlights & Authentic Photo Masonry Gallery --}}
                    @php
                        $hasEditionPhotos = isset($editionPhotos) && $editionPhotos->isNotEmpty();
                        $targetAlbumSlug = !empty($eventYear) ? "itd-{$eventYear}" : null;
                    @endphp
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-10 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-[var(--border)] gap-2">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                                    Visual Archives
                                </span>
                                <h2 class="text-2xl font-bold text-[var(--heading)] mt-0.5">
                                    {{ !empty($isTechDay) ? "ITD {$eventYear} Authentic Photography" : 'Event Highlights & Gallery' }}
                                </h2>
                            </div>
                            @if ($targetAlbumSlug)
                                <a href="{{ route('gallery.album', $targetAlbumSlug) }}" class="text-xs font-bold text-maroon hover:underline flex items-center gap-1">
                                    <span>Browse Full {{ $eventYear }} Album</span>
                                    <span>&rarr;</span>
                                </a>
                            @else
                                <a href="{{ url('/gallery') }}" class="text-xs font-bold text-maroon hover:underline">
                                    Full Gallery &rarr;
                                </a>
                            @endif
                        </div>

                        <p class="text-sm text-[var(--text)] leading-relaxed">
                            @if (!empty($isTechDay))
                                Authentic curated photography from Ijero Tech Day {{ $eventYear }} capturing student projects, tech booths, and milestones:
                            @else
                                Experience the energy, innovation, and smiles from our gatherings in Ijero-Ekiti:
                            @endif
                        </p>

                        @if ($hasEditionPhotos)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                                @foreach ($editionPhotos->take(9) as $photoItem)
                                    @php
                                        $photoPath = $photoItem instanceof \App\Models\GalleryPhoto ? $photoItem->image_path : $photoItem->file_path;
                                        $photoTitle = $photoItem->title ?: "ITD {$eventYear} Photo";
                                        $photoAlt = $photoItem->alt_text ?: $photoTitle;
                                    @endphp
                                    <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800 border border-[var(--border)] group relative shadow-xs hover:shadow-md transition-all">
                                        <img src="{{ asset($photoPath) }}" 
                                             alt="{{ $photoAlt }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-opacity duration-300 block opacity-0"
                                             loading="lazy"
                                             onload="this.classList.remove('opacity-0')"
                                             onerror="this.classList.remove('opacity-0')"
                                             x-init="if ($el.complete) $el.classList.remove('opacity-0')">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2.5">
                                            <span class="text-[11px] font-medium text-white line-clamp-1">
                                                {{ $photoTitle }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($editionPhotos->count() > 9 && $targetAlbumSlug)
                                <div class="pt-2 text-center">
                                    <a href="{{ route('gallery.album', $targetAlbumSlug) }}" 
                                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold bg-[var(--surface-alt)] hover:bg-maroon hover:text-white border border-[var(--border)] text-[var(--text-strong)] transition-all">
                                        <span>View all {{ $editionPhotos->count() }} curated photos for ITD {{ $eventYear }}</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800">
                                    <img src="{{ asset('front/images/dzuelsCollage.webp') }}" alt="Digital lab training in Ijero" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800">
                                    <img src="{{ asset('front/images/about_1.webp') }}" alt="Community beneficiaries" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Social Share Strip with 1-Click Copy Link --}}
                    <div x-data="{ copied: false }" class="p-6 rounded-2xl bg-[var(--surface)] border border-[var(--border)] flex flex-wrap items-center justify-between gap-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--heading)]">
                            Spread the Word:
                        </span>
                        <div class="flex items-center flex-wrap gap-2">
                            <button type="button" 
                                    @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[var(--surface-alt)] hover:bg-stone-200 dark:hover:bg-stone-800 text-[var(--text-strong)] border border-[var(--border)] transition-colors cursor-pointer">
                                <span x-show="!copied">🔗 Copy Link</span>
                                <span x-show="copied" x-cloak class="text-emerald-600 dark:text-mint font-bold">✓ Link Copied!</span>
                            </button>
                            <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . url()->current()) }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                                WhatsApp
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(url()->current()) }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                                X / Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                Facebook
                            </a>
                        </div>
                    </div>

                    {{-- Event Sponsors & Supporters --}}
                    @if ($event->sponsors && $event->sponsors->isNotEmpty())
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-10 shadow-sm space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b border-[var(--border)]">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                                        Community Supporters
                                    </span>
                                    <h2 class="text-2xl font-bold text-[var(--heading)] mt-0.5">
                                        Official Event Sponsors
                                    </h2>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[var(--surface-alt)] text-[var(--text-muted)] border border-[var(--border)]">
                                    {{ $event->sponsors->count() }} Sponsors
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                @foreach ($event->sponsors as $sponsor)
                                    <div class="p-6 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)] flex flex-col items-center text-center justify-center space-y-3 group hover:border-maroon/30 transition-all">
                                        @if ($sponsor->logo_path)
                                            <div class="h-16 w-full flex items-center justify-center">
                                                <img src="{{ asset($sponsor->logo_path) }}" 
                                                     alt="{{ $sponsor->name }}" 
                                                     class="max-h-14 max-w-[120px] object-contain transition-transform group-hover:scale-105">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-maroon/10 text-maroon font-bold flex items-center justify-center text-lg">
                                                {{ substr($sponsor->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="text-xs font-bold text-[var(--heading)]">{{ $sponsor->name }}</span>
                                        @if ($sponsor->website_url)
                                            <a href="{{ $sponsor->website_url }}" target="_blank" rel="noopener noreferrer" class="text-[11px] text-maroon hover:underline font-medium">
                                                Visit Partner &rarr;
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Column: Attendance & Facts Sidebar (5 Cols) --}}
                <div class="lg:col-span-5 space-y-8 sticky top-28">

                    {{-- Right Column Card: Concluded Festival Celebration, or Active Registration Form --}}
                    @if ($event->is_past && $isTechDay)
                        {{-- Concluded Tech Day Edition Impact & Summary Card --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-9 shadow-xl relative overflow-hidden">
                            {{-- Top Accent Bar --}}
                            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-maroon via-crimson to-mint"></div>

                            <div class="flex items-center gap-3 pb-4 border-b border-[var(--border)]">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-mint flex items-center justify-center font-bold text-lg">
                                    🏆
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-mint block">
                                        Festival Summary & Impact
                                    </span>
                                    <h3 class="text-xl font-bold text-[var(--heading)]">
                                        Edition Milestones Achieved
                                    </h3>
                                </div>
                            </div>

                            <div class="space-y-4 text-sm text-[var(--text)] mt-6">
                                <div class="flex items-start gap-3 p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)]">
                                    <span class="text-xl shrink-0">🎓</span>
                                    <div>
                                        <strong class="text-[var(--heading)] block font-semibold text-sm">Cohort 2026 Certification</strong>
                                        <p class="text-xs text-[var(--text-muted)] mt-1 leading-relaxed">
                                            22 graduating cohort students successfully defended their capstone projects and received their official digital literacy certificates during the closing ceremony.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)]">
                                    <span class="text-xl shrink-0">🏫</span>
                                    <div>
                                        <strong class="text-[var(--heading)] block font-semibold text-sm">School Delegations</strong>
                                        <p class="text-xs text-[var(--text-muted)] mt-1 leading-relaxed">
                                            Attendance for 300 student delegates was coordinated directly with participating school delegations across Ijero-Ekiti for hands-on lab and station immersion.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)]">
                                    <span class="text-xl shrink-0">⏰</span>
                                    <div>
                                        <strong class="text-[var(--heading)] block font-semibold text-sm">Staggered Arrival Times</strong>
                                        <p class="text-xs text-[var(--text-muted)] mt-1 leading-relaxed">
                                            Conducted with staggered arrival times and rotating batches of 30 students to ensure optimal mentor guidance and zero wait times across all 10 interactive stations.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-[var(--border)] mt-6 space-y-3">
                                @if ($targetAlbumSlug)
                                    <a href="{{ route('gallery.album', $targetAlbumSlug) }}" class="w-full py-3.5 px-4 rounded-full text-center text-xs font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md block cursor-pointer">
                                        Browse Full {{ $eventYear }} Photo Album &rarr;
                                    </a>
                                @endif
                                <a href="{{ url('/contact') }}" class="w-full py-3 px-4 rounded-full text-center text-xs font-bold bg-[var(--surface-alt)] hover:bg-maroon hover:text-white border border-[var(--border)] text-[var(--text)] transition-colors block cursor-pointer">
                                    Have questions or want to partner? Contact Us &rarr;
                                </a>
                            </div>
                        </div>
                    @elseif ($event->slug === 'ijero-tech-day-2026' && ! $event->is_past)
                        {{-- Future ITD 2026 Attendance & Delegation Card --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-9 shadow-xl relative overflow-hidden">
                            {{-- Top Accent Bar --}}
                            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-maroon via-crimson to-mint"></div>

                            <div class="flex items-center gap-3 pb-4 border-b border-[var(--border)]">
                                <div class="w-10 h-10 rounded-full bg-maroon/10 text-maroon flex items-center justify-center font-bold text-lg">
                                    👥
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                                        Participant Information
                                    </span>
                                    <h3 class="text-xl font-bold text-[var(--heading)]">
                                        Attendance & Access
                                    </h3>
                                </div>
                            </div>

                            <div class="space-y-4 text-sm text-[var(--text)] mt-6">
                                <div class="flex items-start gap-3 p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)]">
                                    <span class="text-xl shrink-0">🏫</span>
                                    <div>
                                        <strong class="text-[var(--heading)] block font-semibold text-sm">School Delegations</strong>
                                        <p class="text-xs text-[var(--text-muted)] mt-1 leading-relaxed">
                                            Attendance for 300 student delegates is pre-allocated and coordinated directly with participating schools across Ijero-Ekiti in 10 rotating batches of 30.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)]">
                                    <span class="text-xl shrink-0">🎓</span>
                                    <div>
                                        <strong class="text-[var(--heading)] block font-semibold text-sm">Cohort Certification</strong>
                                        <p class="text-xs text-[var(--text-muted)] mt-1 leading-relaxed">
                                            Graduating cohort students will receive their official digital literacy certificates during the closing ceremony. Parents are invited for the presentation.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 p-4 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)]">
                                    <span class="text-xl shrink-0">⏰</span>
                                    <div>
                                        <strong class="text-[var(--heading)] block font-semibold text-sm">Staggered Arrival Times</strong>
                                        <p class="text-xs text-[var(--text-muted)] mt-1 leading-relaxed">
                                            Each batch is assigned a specific reporting time to minimize wait times and maximize hands-on lab and garden station immersion.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-[var(--border)] mt-6">
                                <a href="{{ url('/contact') }}" class="w-full py-3 px-4 rounded-full text-center text-xs font-bold bg-[var(--surface-alt)] hover:bg-maroon hover:text-white border border-[var(--border)] text-[var(--text)] transition-colors block cursor-pointer">
                                    Have questions? Contact Event Desk &rarr;
                                </a>
                            </div>
                        </div>
                    @elseif ($event->is_registration_open && ! $event->is_past)
                        {{-- Public Registration Card for other active upcoming events --}}
                        <div class="bg-[var(--surface)] border-2 border-maroon/20 rounded-3xl p-8 sm:p-9 shadow-xl relative overflow-hidden space-y-6">
                            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-maroon via-crimson to-mint"></div>

                            <div class="space-y-2 pb-4 border-b border-[var(--border)]">
                                <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                                    Reserve Your Spot
                                </span>
                                <h3 class="text-2xl font-black text-[var(--heading)]">
                                    Register for this Event
                                </h3>
                                <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                    Admission is free of charge. Fill out your details below to confirm your attendance.
                                </p>
                            </div>

                            @if (session('success'))
                                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-800 dark:text-rose-300">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('events.register', $event->slug) }}" class="space-y-4">
                                @csrf

                                {{-- Full Name --}}
                                <div>
                                    <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-1.5">
                                        Full Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="full_name" 
                                           name="full_name" 
                                           required 
                                           aria-required="true"
                                           value="{{ old('full_name') }}"
                                           placeholder="e.g. Oluwaseun Adeleke" 
                                           class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                                    @error('full_name')
                                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email Address --}}
                                <div>
                                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-1.5">
                                        Email Address <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           required 
                                           aria-required="true"
                                           value="{{ old('email') }}"
                                           placeholder="name@example.com" 
                                           class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                                    @error('email')
                                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Phone Number --}}
                                <div>
                                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-1.5">
                                        Phone Number <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           required 
                                           aria-required="true"
                                           value="{{ old('phone') }}"
                                           placeholder="+234 800 000 0000" 
                                           class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                                    @error('phone')
                                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Attendee Role / Category --}}
                                <div>
                                    <label for="attendee_type" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-1.5">
                                        Attendee Category
                                    </label>
                                    <select id="attendee_type" 
                                            name="attendee_type" 
                                            class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                                        <option value="student" {{ old('attendee_type') == 'student' ? 'selected' : '' }}>Student / Youth (Batch Participant)</option>
                                        <option value="teacher" {{ old('attendee_type') == 'teacher' ? 'selected' : '' }}>Teacher / School Representative</option>
                                        <option value="cohort_graduate" {{ old('attendee_type') == 'cohort_graduate' ? 'selected' : '' }}>Cohort Graduate (Certificate Recipient)</option>
                                        <option value="guest" {{ old('attendee_type') == 'guest' ? 'selected' : '' }}>Community Guest / Dignitary</option>
                                        <option value="general" {{ old('attendee_type') == 'general' ? 'selected' : '' }}>General Guest</option>
                                    </select>
                                    @error('attendee_type')
                                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Notes / Expectations --}}
                                <div>
                                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-1.5">
                                        School / Special Notes (Optional)
                                    </label>
                                    <textarea id="notes" 
                                              name="notes" 
                                              rows="2" 
                                              placeholder="School name, class, batch or special notes..." 
                                              class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">{{ old('notes') }}</textarea>
                                </div>

                                {{-- Privacy Notice --}}
                                <p class="text-[11px] text-[var(--text-muted)] flex items-start gap-1.5 leading-normal">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-mint shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>We respect your privacy. Attendee details are protected under our <a href="{{ route('privacy-policy') }}" class="underline hover:text-maroon dark:hover:text-[var(--accent-cream)] font-medium">Privacy Policy</a>.</span>
                                </p>

                                {{-- Submit Button --}}
                                <button type="submit" 
                                        class="w-full py-3.5 px-6 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer flex items-center justify-center gap-2">
                                    <span>Complete Free Registration</span>
                                    <span>&rarr;</span>
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Concluded or Inactive Event State --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-9 shadow-sm relative overflow-hidden text-center py-8 space-y-4">
                            <div class="w-14 h-14 rounded-full bg-stone-100 dark:bg-stone-800 flex items-center justify-center mx-auto text-2xl">
                                {{ $event->is_past ? '✅' : '🔒' }}
                            </div>
                            <h3 class="text-xl font-bold text-[var(--heading)]">
                                {{ $event->is_past ? 'This Event Has Concluded' : 'Registration is Closed' }}
                            </h3>
                            <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                @if ($event->is_past)
                                    This gathering took place on {{ $event->formatted_date }}. Explore photo highlights above or check our upcoming calendar for future opportunities.
                                @else
                                    Registration for this event has reached capacity or concluded. If you have an inquiry, please contact our event coordinator.
                                @endif
                            </p>
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                                <a href="{{ route('events.index') }}" class="text-xs font-bold text-maroon hover:underline">
                                    View All Events &rarr;
                                </a>
                                <span class="text-stone-300 hidden sm:inline">•</span>
                                <a href="{{ url('/contact') }}" class="text-xs font-semibold text-[var(--text-muted)] hover:underline">
                                    Contact Desk
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Quick Event Facts Card --}}
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 shadow-sm space-y-5">
                        <h3 class="text-base font-bold text-[var(--heading)] pb-3 border-b border-[var(--border)]">
                            Event At a Glance
                        </h3>

                        <ul class="space-y-4 text-xs">
                            <li class="flex items-start gap-3">
                                <span class="text-base text-maroon dark:text-[var(--accent-cream)]">📅</span>
                                <div>
                                    <span class="font-bold text-[var(--text-strong)] block">Date</span>
                                    <span class="text-[var(--text-muted)]">{{ $event->formatted_date }}</span>
                                </div>
                            </li>

                            <li class="flex items-start gap-3">
                                <span class="text-base text-maroon dark:text-[var(--accent-cream)]">⏰</span>
                                <div>
                                    <span class="font-bold text-[var(--text-strong)] block">Time</span>
                                    <span class="text-[var(--text-muted)]">{{ $event->formatted_time }}</span>
                                </div>
                            </li>

                            <li class="flex items-start gap-3">
                                <span class="text-base text-maroon dark:text-[var(--accent-cream)]">📍</span>
                                <div>
                                    <span class="font-bold text-[var(--text-strong)] block">Venue</span>
                                    <span class="text-[var(--text-muted)]">{{ $event->venue ?? 'Ijero-Ekiti, Ekiti State, Nigeria' }}</span>
                                </div>
                            </li>

                            <li class="flex items-start gap-3">
                                <span class="text-base text-maroon dark:text-[var(--accent-cream)]">🎟️</span>
                                <div>
                                    <span class="font-bold text-[var(--text-strong)] block">Access Fee</span>
                                    <span class="text-[var(--text-muted)]">100% Free Sponsored Initiative</span>
                                </div>
                            </li>

                            <li class="flex items-start gap-3">
                                <span class="text-base text-maroon dark:text-[var(--accent-cream)]">🤝</span>
                                <div>
                                    <span class="font-bold text-[var(--text-strong)] block">Organizer</span>
                                    <span class="text-[var(--text-muted)]">Dzuels Educational Foundation</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Other Events Section --}}
    @if ($otherEvents->isNotEmpty())
        <section class="py-16 bg-[var(--surface)] border-t border-[var(--border)] transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-[var(--heading)]">
                        More Foundation Gatherings
                    </h2>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-maroon hover:underline">
                        View All Events &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($otherEvents as $other)
                        <div class="p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border)] space-y-3 flex flex-col justify-between hover:border-maroon/40 transition-colors">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-mono font-bold text-crimson dark:text-[var(--accent-cream)]">
                                        {{ $other->formatted_date }}
                                    </span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $other->is_past ? 'bg-stone-200 dark:bg-stone-800 text-stone-600 dark:text-stone-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-mint' }}">
                                        {{ $other->is_past ? 'Concluded' : 'Upcoming' }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-[var(--heading)] text-base">
                                    <a href="{{ route('events.show', $other->slug) }}" class="hover:text-maroon transition-colors">
                                        {{ $other->title }}
                                    </a>
                                </h3>
                                <p class="text-xs text-[var(--text)] line-clamp-2">
                                    {{ $other->summary }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-[var(--border)]">
                                <a href="{{ route('events.show', $other->slug) }}" class="text-xs font-bold text-maroon hover:underline">
                                    Read Details &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layout>
