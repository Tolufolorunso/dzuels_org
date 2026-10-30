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

    {{-- 1. Full-Bleed Rich Hero Section (Homepage UI Alignment) --}}
    <section class="relative bg-maroon text-white py-16 sm:py-24 lg:py-32 overflow-hidden">
        {{-- Background Banner Image with Deep Burgundy/Navy Gradient Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ $event->banner_image_url ?: asset('front/images/techday.webp') }}" 
                 alt="{{ $event->title }}" 
                 loading="eager"
                 fetchpriority="high"
                 decoding="async"
                 class="w-full h-full object-cover object-center opacity-30" />
            <div class="absolute inset-0 pointer-events-none" 
                 style="background: linear-gradient(135deg, rgba(43, 2, 3, 0.94) 0%, rgba(106, 4, 6, 0.85) 55%, rgba(36, 47, 64, 0.72) 100%);"></div>
        </div>

        {{-- Ambient decorative glows --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[var(--accent-cream)]/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb Navigation (integrated in Hero) --}}
            <nav class="flex items-center gap-2 text-xs font-semibold text-white/75 mb-8" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-[var(--accent-cream)] transition-colors">Home</a>
                <span class="opacity-50">/</span>
                <a href="{{ route('events.index') }}" class="hover:text-[var(--accent-cream)] transition-colors">Events</a>
                <span class="opacity-50">/</span>
                <span class="text-white truncate max-w-xs sm:max-w-md">{{ $event->title }}</span>
            </nav>

            <div class="max-w-4xl space-y-6">
                {{-- Badges Strip: Date, Time, Dynamic Status & Venue --}}
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    {{-- Date Pill --}}
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/15 text-white border border-white/20 backdrop-blur-xs">
                        <span>📅</span> {{ $event->formatted_date }}
                    </span>

                    {{-- Time Pill --}}
                    @if ($event->event_date)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-medium bg-white/10 text-white/90 border border-white/15 backdrop-blur-xs">
                            <span>⏰</span> {{ $event->formatted_time }}
                        </span>
                    @endif

                    {{-- Dynamic Event Status Pill --}}
                    @if ($event->is_past)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-black/40 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                            <span>✅</span> {{ $event->status_label }}
                        </span>
                    @elseif ($event->is_today)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-500 text-white shadow-md animate-pulse">
                            <span>🔥</span> Happening Today
                        </span>
                    @elseif ($event->is_future)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/25 text-emerald-200 border border-emerald-400/40 backdrop-blur-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ $event->status_label }}{{ $event->is_registration_open ? ' • Registration Open' : ' • Pre-Allocated Batches' }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 text-white/80 border border-white/15">
                            Registration Closed
                        </span>
                    @endif

                    {{-- Venue Pill --}}
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-medium bg-white/10 text-white/90 border border-white/15 backdrop-blur-xs">
                        <span>📍</span> {{ $event->venue ?? 'Ijero-Ekiti, Nigeria' }}
                    </span>
                </div>

                {{-- Hero Headline --}}
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $event->title }}
                </h1>

                {{-- Event Theme Pill (if present) --}}
                @if ($event->theme)
                    <div>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/40 backdrop-blur-xs">
                            <span>✨ Theme:</span>
                            <span class="italic font-bold">"{{ $event->theme }}"</span>
                        </span>
                    </div>
                @endif

                {{-- Hero Summary / Subheadline --}}
                <p class="text-base sm:text-lg lg:text-xl text-white/90 leading-relaxed max-w-3xl">
                    {{ $event->summary }}
                </p>

                {{-- Multi-Year Tech Day Edition Switcher (Integrated into Hero) --}}
                @if (!empty($isTechDay) && isset($techDayEditions) && $techDayEditions->isNotEmpty())
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3.5 sm:p-4 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md max-w-2xl">
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xl">🚀</span>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--accent-cream)] block">Festival Edition</span>
                                    <span class="text-xs font-bold text-white">Switch Festival Edition:</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                @foreach ($techDayEditions as $edition)
                                    @php
                                        $isActive = $edition->id === $event->id;
                                    @endphp
                                    <a href="{{ route('events.show', $edition->slug) }}" 
                                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ $isActive 
                                           ? 'bg-white text-maroon shadow-md ring-2 ring-white/60 scale-102' 
                                           : 'bg-white/10 text-white hover:bg-white/20 border border-white/15' }}">
                                        <span>{{ $edition->event_date?->year }}</span>
                                        <span class="text-[10px] opacity-80 font-normal">
                                            ({{ $edition->is_future ? 'Upcoming Flagship' : ($edition->slug === 'ijero-tech-day-2024' ? 'Inaugural' : ($edition->slug === 'ijero-tech-day-2025' ? '2nd Edition' : 'Concluded')) }})
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Hero Action CTAs --}}
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    @if ($event->is_registration_open && ! $event->is_past)
                        <a href="#register-card" 
                           class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                            Register Free &rarr;
                        </a>
                        <a href="{{ url('/get-involved') }}" 
                           class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                            Volunteer / Partner
                        </a>
                    @else
                        @php
                            $targetAlbumSlug = !empty($eventYear) ? "itd-{$eventYear}" : null;
                        @endphp
                        @if ($targetAlbumSlug)
                            <a href="#photo-gallery" 
                               class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                                Browse Photo Album &rarr;
                            </a>
                        @endif
                        <a href="{{ url('/contact') }}" 
                           class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                            Contact Event Desk
                        </a>
                    @endif
                </div>

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
                    }" x-init="update(); setInterval(() => update(), 1000)" class="pt-4 max-w-2xl">
                        <div class="p-5 sm:p-6 rounded-2xl bg-black/30 border border-white/20 backdrop-blur-md">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <span class="text-[10px] uppercase font-bold tracking-widest text-[var(--accent-cream)] block">Countdown to Flagship Gathering</span>
                                    <h4 class="text-base sm:text-lg font-black mt-0.5 text-white">Event Begins In:</h4>
                                </div>
                                <div class="flex items-center gap-2.5 font-mono">
                                    <div class="text-center px-3 py-2 rounded-xl bg-white/10 border border-white/15 min-w-[55px]">
                                        <span class="text-2xl font-black block text-white" x-text="days">0</span>
                                        <span class="text-[9px] uppercase tracking-wider text-stone-300">Days</span>
                                    </div>
                                    <div class="text-center px-3 py-2 rounded-xl bg-white/10 border border-white/15 min-w-[55px]">
                                        <span class="text-2xl font-black block text-white" x-text="hours">0</span>
                                        <span class="text-[9px] uppercase tracking-wider text-stone-300">Hours</span>
                                    </div>
                                    <div class="text-center px-3 py-2 rounded-xl bg-white/10 border border-white/15 min-w-[55px]">
                                        <span class="text-2xl font-black block text-white" x-text="minutes">0</span>
                                        <span class="text-[9px] uppercase tracking-wider text-stone-300">Mins</span>
                                    </div>
                                    <div class="text-center px-3 py-2 rounded-xl bg-white/10 border border-white/15 min-w-[55px]">
                                        <span class="text-2xl font-black block text-white" x-text="seconds">0</span>
                                        <span class="text-[9px] uppercase tracking-wider text-stone-300">Secs</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- 2. Floating 4-Metric Impact Banner (Homepage UI Layout Alignment) --}}
    @if (!empty($isTechDay))
    <section class="relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 mb-8">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-6 sm:p-10 shadow-xl transition-colors">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-0 divide-y lg:divide-y-0 lg:divide-x divide-[var(--border)]">
                {{-- Metric 1: Student Delegates --}}
                <div class="flex flex-col gap-2 pt-4 first:pt-0 lg:pt-0 lg:px-6 first:lg:pl-0">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl lg:text-5xl font-black text-[var(--heading)] tracking-tight">300</span>
                        <span class="text-sm font-bold text-mint font-sans">+</span>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-[var(--text)]">
                        Student Delegates across Ijero-Ekiti
                    </span>
                    <div class="w-full bg-[var(--border-subtle)] h-1.5 rounded-full overflow-hidden mt-3">
                        <div class="bg-mint h-full rounded-full w-[88%]"></div>
                    </div>
                </div>

                {{-- Metric 2: Cohort Graduates --}}
                <div class="flex flex-col gap-2 pt-4 lg:pt-0 lg:px-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl lg:text-5xl font-black text-[var(--heading)] tracking-tight">22</span>
                        <span class="text-sm font-bold text-crimson font-sans">Grads</span>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-[var(--text)]">
                        Certified Cohort Graduates
                    </span>
                    <div class="w-full bg-[var(--border-subtle)] h-1.5 rounded-full overflow-hidden mt-3">
                        <div class="bg-maroon dark:bg-crimson h-full rounded-full w-[95%]"></div>
                    </div>
                </div>

                {{-- Metric 3: Hands-on Stations --}}
                <div class="flex flex-col gap-2 pt-4 lg:pt-0 lg:px-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl lg:text-5xl font-black text-[var(--heading)] tracking-tight">10</span>
                        <span class="text-sm font-bold text-amber-500 font-sans">Booths</span>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-[var(--text)]">
                        Interactive Hands-on Stations
                    </span>
                    <div class="w-full bg-[var(--border-subtle)] h-1.5 rounded-full overflow-hidden mt-3">
                        <div class="bg-amber-500 h-full rounded-full w-[80%]"></div>
                    </div>
                </div>

                {{-- Metric 4: 100% Free & Sponsored --}}
                <div class="flex flex-col gap-2 pt-4 lg:pt-0 lg:px-6 last:lg:pr-0">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl lg:text-5xl font-black text-[var(--heading)] tracking-tight">100</span>
                        <span class="text-sm font-bold text-emerald-500 font-sans">%</span>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-[var(--text)]">
                        Free & Fully Sponsored Admission
                    </span>
                    <div class="w-full bg-[var(--border-subtle)] h-1.5 rounded-full overflow-hidden mt-3">
                        <div class="bg-[var(--accent-cream)] dark:bg-emerald-400 h-full rounded-full w-[100%]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 3. Balanced 2-Column Story & Media Section (Eliminates Left-Flushed Feeling) --}}
    <section class="py-16 sm:py-24 bg-[var(--bg)] transition-colors overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Column 1 (Left): 16:9 Banner Media Frame with Offset Cream Border --}}
                <div class="relative mx-auto lg:mx-0 w-full max-w-lg lg:max-w-none">
                    <div class="absolute -top-3 -left-3 sm:-top-4 sm:-left-4 w-full h-full border-3 border-[var(--accent-cream)] rounded-3xl z-0 pointer-events-none"></div>
                    <div class="relative z-10 w-full aspect-16/9 rounded-3xl overflow-hidden shadow-xl border border-[var(--border)] bg-stone-900">
                        <img src="{{ $event->banner_image_url ?: asset('front/images/techday.webp') }}" 
                             alt="{{ $event->title }}" 
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover object-center" />
                    </div>

                    {{-- Quick Event Facts Bar below Media Frame --}}
                    <div class="relative z-10 mt-6 grid grid-cols-3 gap-3 p-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] shadow-sm text-center">
                        <div>
                            <span class="text-base block mb-0.5">📍</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">Venue</span>
                            <span class="text-xs font-semibold text-[var(--text-strong)] block truncate" title="{{ $event->venue ?? 'Ijero-Ekiti, Nigeria' }}">
                                {{ $event->venue ? Str::limit($event->venue, 20) : 'Ijero-Ekiti' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-base block mb-0.5">🎟️</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">Admission</span>
                            <span class="text-xs font-semibold text-[var(--text-strong)] block">100% Free Sponsored</span>
                        </div>
                        <div>
                            <span class="text-base block mb-0.5">🤝</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">Organizer</span>
                            <span class="text-xs font-semibold text-[var(--text-strong)] block">Dzuels Educational</span>
                        </div>
                    </div>
                </div>

                {{-- Column 2 (Right): Story Narrative, Key Objectives & Context --}}
                <div class="space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                            Story & Context
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight mt-2">
                            About {{ $event->title }}
                        </h2>
                    </div>

                    {{-- Highlighted Key Objective Box --}}
                    @if ($event->objective)
                        <div class="p-5 rounded-2xl bg-maroon/5 dark:bg-maroon/20 border border-maroon/20 text-maroon dark:text-[var(--accent-cream)] shadow-xs">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl shrink-0">🎯</span>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-crimson dark:text-mint block mb-1">
                                        Key Event Objective
                                    </span>
                                    <p class="text-base sm:text-lg font-bold leading-relaxed text-[var(--heading)]">
                                        "{{ $event->objective }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Narrative Paragraphs --}}
                    <div class="text-base sm:text-lg text-[var(--text)] leading-relaxed space-y-4">
                        @foreach (explode("\n\n", $event->description) as $paragraph)
                            @if (trim($paragraph))
                                <p>{{ trim($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>

                    {{-- Quick Action Buttons --}}
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        @if ($event->is_registration_open && ! $event->is_past)
                            <a href="#register-card" 
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md">
                                <span>Reserve Attendance Spot</span>
                                <span>&rarr;</span>
                            </a>
                        @endif
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold border-2 border-maroon text-maroon hover:bg-maroon hover:text-white dark:border-[var(--accent-cream)] dark:text-[var(--accent-cream)] dark:hover:bg-[var(--accent-cream)] dark:hover:text-maroon transition-all duration-200">
                            <span>View All Events</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. 3-Column Milestone Celebration / Delegation Framework (Homepage Rhythm) --}}
    @if ($isTechDay)
    <section class="py-16 sm:py-20 bg-[var(--bg-alt)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-mint inline-flex items-center gap-1.5">
                    <span>🏆</span> Festival Summary & Impact
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-[var(--heading)]">
                    Edition Milestones Achieved
                </h3>
                <p class="text-xs sm:text-sm text-[var(--text-muted)]">
                    Key achievements, school delegations, and operational highlights from Ijero Tech Day.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                {{-- Milestone 1: Cohort Certification --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-mint flex items-center justify-center text-2xl mb-4 shadow-xs">
                            🎓
                        </div>
                        <h4 class="text-lg sm:text-xl font-bold text-[var(--heading)] mb-2">Cohort 2026 Certification</h4>
                        <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                            22 graduating cohort students successfully defended their capstone projects and received their official digital literacy certificates during the closing ceremony.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-[var(--border)] flex items-center gap-2 text-xs font-bold text-mint">
                        <span>✓</span> Certified Capstone Defenses
                    </div>
                </div>

                {{-- Milestone 2: School Delegations --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-maroon/10 text-maroon dark:bg-maroon/30 dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4 shadow-xs">
                            🏫
                        </div>
                        <h4 class="text-lg sm:text-xl font-bold text-[var(--heading)] mb-2">School Delegations</h4>
                        <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                            Attendance for 300 student delegates was coordinated directly with participating school delegations across Ijero-Ekiti for hands-on lab and station immersion.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-[var(--border)] flex items-center gap-2 text-xs font-bold text-crimson dark:text-[var(--accent-cream)]">
                        <span>✓</span> Coordinated School Batches
                    </div>
                </div>

                {{-- Milestone 3: Staggered Arrival Times --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center text-2xl mb-4 shadow-xs">
                            ⏰
                        </div>
                        <h4 class="text-lg sm:text-xl font-bold text-[var(--heading)] mb-2">Staggered Arrival Times</h4>
                        <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                            Conducted with staggered arrival times and rotating batches of 30 students to ensure optimal mentor guidance and zero wait times across all 10 interactive stations.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-[var(--border)] flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400">
                        <span>✓</span> Zero Wait Immersion
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 5. Full-Width Interactive Agenda & Schedule Timeline --}}
    @if (!empty($event->schedule))
    <section class="py-16 sm:py-20 bg-[var(--surface)] border-t border-b border-[var(--border)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 mb-8 border-b border-[var(--border)] gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                        Agenda & Schedule
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] mt-1">
                        Event Schedule Highlights
                    </h2>
                </div>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-[var(--surface-alt)] text-[var(--text-muted)] border border-[var(--border)] w-fit">
                    {{ count($event->schedule) }} Scheduled Sessions
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach ($event->schedule as $index => $slot)
                    <div class="p-5 sm:p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border)] flex flex-col justify-between gap-3 hover:border-maroon/40 transition-colors shadow-xs">
                        <div class="space-y-1.5">
                            <span class="font-mono text-xs font-bold text-maroon dark:text-[var(--accent-cream)] block">
                                {{ $slot['time'] ?? 'TBA' }}
                            </span>
                            <h3 class="font-bold text-[var(--heading)] text-base sm:text-lg">
                                {{ $slot['title'] ?? '' }}
                            </h3>
                        </div>
                        @if (!empty($slot['speaker']))
                            <div class="pt-2 border-t border-[var(--border)] flex items-center justify-between">
                                <span class="text-xs text-[var(--text-muted)]">Session Lead / Speaker</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[var(--surface-alt)] text-[var(--text-strong)] border border-[var(--border)]">
                                    🎙️ {{ $slot['speaker'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- 6. Registration Section (if registration open and not past) --}}
    @if ($event->is_registration_open && ! $event->is_past)
    <section id="register-card" class="py-16 sm:py-20 bg-[var(--bg)] transition-colors">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[var(--surface)] border-2 border-maroon/20 rounded-3xl p-8 sm:p-12 shadow-xl relative overflow-hidden space-y-6">
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-maroon via-crimson to-mint"></div>

                <div class="text-center space-y-2 pb-4 border-b border-[var(--border)]">
                    <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                        Reserve Your Spot
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black text-[var(--heading)]">
                        Register for this Event
                    </h3>
                    <p class="text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed">
                        Admission is 100% free of charge. Fill out your details below to confirm your attendance.
                    </p>
                </div>

                @if (session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs sm:text-sm text-emerald-800 dark:text-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs sm:text-sm text-rose-800 dark:text-rose-300">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('events.register', $event->slug) }}" class="space-y-5">
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
                               class="w-full px-4 py-3 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
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
                               class="w-full px-4 py-3 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
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
                               class="w-full px-4 py-3 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
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
                                class="w-full px-4 py-3 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
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
                                  class="w-full px-4 py-3 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">{{ old('notes') }}</textarea>
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
                            class="w-full py-3.5 px-6 rounded-full text-base font-bold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer flex items-center justify-center gap-2">
                        <span>Complete Free Registration</span>
                        <span>&rarr;</span>
                    </button>
                </form>
            </div>
        </div>
    </section>
    @endif

    {{-- 7. Event Highlights & Authentic Photo Gallery (Homepage Layout Alignment) --}}
    @php
        $hasEditionPhotos = isset($editionPhotos) && $editionPhotos->isNotEmpty();
        $targetAlbumSlug = !empty($eventYear) ? "itd-{$eventYear}" : null;
    @endphp
    <section id="photo-gallery" class="py-16 sm:py-24 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-[var(--border)] gap-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                        Visual Archives
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] mt-1">
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

            <p class="text-sm sm:text-base text-[var(--text)] leading-relaxed max-w-3xl">
                @if (!empty($isTechDay))
                    Authentic curated photography from Ijero Tech Day {{ $eventYear }} capturing student projects, interactive booths, and community milestones in Ijero-Ekiti:
                @else
                    Experience the energy, innovation, and smiles from our gatherings in Ijero-Ekiti:
                @endif
            </p>

            @if ($hasEditionPhotos)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6">
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
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                                <span class="text-xs font-medium text-white line-clamp-1">
                                    {{ $photoTitle }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($editionPhotos->count() > 9 && $targetAlbumSlug)
                    <div class="pt-4 text-center">
                        <a href="{{ route('gallery.album', $targetAlbumSlug) }}" 
                           class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full text-xs sm:text-sm font-bold bg-[var(--surface-alt)] hover:bg-maroon hover:text-white border border-[var(--border)] text-[var(--text-strong)] transition-all shadow-sm">
                            <span>View all {{ $editionPhotos->count() }} curated photos for ITD {{ $eventYear }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                @endif
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800 border border-[var(--border)]">
                        <img src="{{ asset('front/images/dzuelsCollage.webp') }}" alt="Digital lab training in Ijero" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800 border border-[var(--border)]">
                        <img src="{{ asset('front/images/about_1.webp') }}" alt="Community beneficiaries" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- 8. Social Share & Community Supporters --}}
    <section class="py-12 bg-[var(--bg-alt)] border-t border-[var(--border)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Social Share Strip --}}
            <div x-data="{ copied: false }" class="p-6 rounded-2xl bg-[var(--surface)] border border-[var(--border)] flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-[var(--heading)]">
                    Spread the Word & Share:
                </span>
                <div class="flex items-center flex-wrap justify-center gap-2">
                    <button type="button" 
                            @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2500)"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold bg-[var(--surface-alt)] hover:bg-stone-200 dark:hover:bg-stone-800 text-[var(--text-strong)] border border-[var(--border)] transition-colors cursor-pointer">
                        <span x-show="!copied">🔗 Copy Link</span>
                        <span x-show="copied" x-cloak class="text-emerald-600 dark:text-mint font-bold">✓ Link Copied!</span>
                    </button>
                    <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . url()->current()) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="px-4 py-2 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                        WhatsApp
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="px-4 py-2 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                        X / Twitter
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="px-4 py-2 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                        Facebook
                    </a>
                </div>
            </div>

            {{-- Event Sponsors & Supporters --}}
            @if ($event->sponsors && $event->sponsors->isNotEmpty())
                <div class="space-y-4">
                    <div class="text-center sm:text-left">
                        <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                            Community Supporters
                        </span>
                        <h3 class="text-xl font-bold text-[var(--heading)] mt-0.5">
                            Official Event Sponsors
                        </h3>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ($event->sponsors as $sponsor)
                            <div class="p-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] flex flex-col items-center text-center justify-center space-y-2 group hover:border-maroon/30 transition-all shadow-xs">
                                @if ($sponsor->logo_path)
                                    <div class="h-12 w-full flex items-center justify-center">
                                        <img src="{{ asset($sponsor->logo_path) }}" 
                                             alt="{{ $sponsor->name }}" 
                                             class="max-h-10 max-w-[100px] object-contain transition-transform group-hover:scale-105">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-maroon/10 text-maroon font-bold flex items-center justify-center text-sm">
                                        {{ substr($sponsor->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-xs font-bold text-[var(--heading)]">{{ $sponsor->name }}</span>
                                @if ($sponsor->website_url)
                                    <a href="{{ $sponsor->website_url }}" target="_blank" rel="noopener noreferrer" class="text-[10px] text-maroon hover:underline font-medium">
                                        Visit Partner &rarr;
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- 9. Full-Bleed Radial Maroon CTA Section (Homepage UI Alignment) --}}
    <section class="py-20 lg:py-28 text-white text-center relative overflow-hidden bg-radial from-crimson/40 via-maroon to-[#2B0203]">
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">
                Empower Future Innovators in Ijero-Ekiti
            </h2>
            <p class="text-base sm:text-lg text-[var(--accent-cream)] max-w-2xl mx-auto leading-relaxed">
                Whether through corporate sponsorship, volunteer mentorship, hardware donations, or legacy giving, your partnership directly equips students with lifelong digital skills.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ url('/donate') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                    Support Our Mission
                </a>
                <a href="{{ url('/get-involved') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                    Volunteer / Partner
                </a>
            </div>
        </div>
    </section>

    {{-- 10. More Foundation Gatherings --}}
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
                        <div class="p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border)] space-y-3 flex flex-col justify-between hover:border-maroon/40 transition-colors shadow-xs">
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
