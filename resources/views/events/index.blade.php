<x-layout 
    title="Events & Gatherings | Dzuels Educational Foundation"
    description="Join Dzuels Educational Foundation for upcoming technology events, student workshops, and community celebrations in Ijero-Ekiti, Nigeria."
    :canonical="url('/events')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Events', 'url' => url('/events')]
    ]">

    {{-- 1. Hero Section --}}
    <section class="relative py-20 lg:py-28 bg-[var(--surface)] border-b border-[var(--border)] overflow-hidden transition-colors">
        {{-- Decorative background glows --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-maroon/5 dark:bg-maroon/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[var(--accent-cream)]/30 dark:bg-[var(--accent-cream)]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-maroon/10 dark:bg-maroon/30 text-maroon dark:text-[var(--accent-cream)] border border-maroon/20 mb-6">
                <span>📅</span> Community Gatherings & Milestones
            </span>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[var(--heading)] tracking-tight max-w-4xl mx-auto leading-tight">
                Empowering Youth Through <span class="font-serif italic font-normal text-crimson dark:text-[var(--accent-cream)]">Technology</span> & Literacy
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-[var(--text-muted)] max-w-2xl mx-auto leading-relaxed">
                From our flagship Ijero Tech Day celebration to grassroots educational clinics, discover where innovation meets community development.
            </p>
        </div>
    </section>

    {{-- 2. Featured Flagship Event Spotlight --}}
    @if ($featuredEvent)
        <section class="py-16 lg:py-20 bg-[var(--bg)] transition-colors border-b border-[var(--border)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)] block">
                        Milestone Gathering
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-[var(--heading)] mt-1">
                        Featured Event Spotlight
                    </h2>
                </div>

                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 grid grid-cols-1 lg:grid-cols-12 group">
                    {{-- Banner Image Column --}}
                    <div class="lg:col-span-7 relative min-h-[320px] lg:min-h-[460px] overflow-hidden bg-stone-900">
                        <img src="{{ $featuredEvent->banner_image_url }}" 
                             alt="{{ $featuredEvent->title }}" 
                             class="w-full h-full object-cover object-center absolute inset-0 transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent lg:hidden"></div>
                        
                        {{-- Date Badge Floating Overlay --}}
                        <div class="absolute top-6 left-6 z-10 flex flex-col items-center bg-white/95 dark:bg-stone-900/95 backdrop-blur-md rounded-2xl p-3 border border-white/20 shadow-lg text-center min-w-[70px]">
                            <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)]">
                                {{ $featuredEvent->event_date ? $featuredEvent->event_date->format('M') : 'TBA' }}
                            </span>
                            <span class="text-2xl font-black text-stone-900 dark:text-white leading-none my-0.5">
                                {{ $featuredEvent->event_date ? $featuredEvent->event_date->format('d') : '--' }}
                            </span>
                            <span class="text-[10px] font-semibold text-stone-500">
                                {{ $featuredEvent->event_date ? $featuredEvent->event_date->format('Y') : '' }}
                            </span>
                        </div>

                        {{-- Registration Status Badge --}}
                        <div class="absolute top-6 right-6 z-10">
                            @if ($featuredEvent->is_registration_open)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-md">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                    Registration Open
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-stone-700 text-white shadow-md">
                                    Registration Closed
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Content Column --}}
                    <div class="lg:col-span-5 p-8 sm:p-12 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-muted)]">
                                <span>📍</span>
                                <span class="line-clamp-1">{{ $featuredEvent->venue ?? 'Ijero-Ekiti, Nigeria' }}</span>
                            </div>

                            <h3 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] leading-snug group-hover:text-maroon transition-colors">
                                <a href="{{ route('events.show', $featuredEvent->slug) }}">
                                    {{ $featuredEvent->title }}
                                </a>
                            </h3>

                            @if ($featuredEvent->objective)
                                <div class="p-3.5 rounded-2xl bg-maroon/5 dark:bg-maroon/20 border border-maroon/15 text-xs text-[var(--text)]">
                                    <span class="font-bold text-maroon dark:text-[var(--accent-cream)] block mb-1">🎯 2026 Core Objective:</span>
                                    {{ $featuredEvent->objective }}
                                </div>
                            @endif

                            <p class="text-[var(--text)] leading-relaxed text-sm sm:text-base">
                                {{ $featuredEvent->summary }}
                            </p>

                            @if ($featuredEvent->sponsors && $featuredEvent->sponsors->isNotEmpty())
                                <div class="pt-2 border-t border-[var(--border)]">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-[var(--text-muted)] block mb-2">
                                        Official Sponsors & Partners
                                    </span>
                                    <div class="flex flex-wrap items-center gap-2">
                                        @foreach ($featuredEvent->sponsors as $sponsor)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[var(--surface-alt)] border border-[var(--border)] text-xs font-semibold text-[var(--heading)]">
                                                <span class="text-maroon">🤝</span> {{ $sponsor->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Schedule Highlights Preview --}}
                            @if (!empty($featuredEvent->schedule))
                                <div class="pt-2 border-t border-[var(--border)]">
                                    <span class="text-xs font-bold uppercase tracking-wider text-[var(--heading)] block mb-2.5">
                                        Schedule Highlights
                                    </span>
                                    <div class="space-y-2">
                                        @foreach (array_slice($featuredEvent->schedule, 0, 3) as $slot)
                                            <div class="flex items-start gap-2.5 text-xs text-[var(--text)]">
                                                <span class="font-mono font-bold text-maroon dark:text-[var(--accent-cream)] shrink-0">
                                                    {{ $slot['time'] ?? '' }}
                                                </span>
                                                <span class="font-medium text-stone-700 dark:text-stone-300">
                                                    {{ $slot['title'] ?? '' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-4 flex flex-wrap items-center gap-3">
                            <a href="{{ route('events.show', $featuredEvent->slug) }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                <span>{{ $featuredEvent->is_registration_open ? 'Register to Attend' : 'View Event Details' }}</span>
                                <span>&rarr;</span>
                            </a>
                            <a href="{{ route('events.show', $featuredEvent->slug) }}" 
                               class="inline-flex items-center justify-center px-5 py-3 rounded-full text-sm font-semibold border border-[var(--border)] text-[var(--text-strong)] hover:border-maroon transition-all duration-200">
                                View Schedule
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 3. All Upcoming Events Grid --}}
    <section class="py-20 lg:py-24 bg-[var(--surface)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                    Upcoming Calendar
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Join Our Next Gatherings
                </h2>
                <p class="text-[var(--text-muted)] text-base">
                    Discover upcoming workshops, innovation exhibitions, and foundation initiatives across Ekiti State.
                </p>
            </div>

            @if ($upcomingEvents->isEmpty())
                <div class="p-16 text-center max-w-md mx-auto bg-[var(--bg)] border border-[var(--border)] rounded-3xl space-y-4">
                    <div class="w-16 h-16 rounded-full bg-[var(--surface)] flex items-center justify-center mx-auto text-3xl">
                        📅
                    </div>
                    <h3 class="text-lg font-bold text-[var(--heading)]">No Upcoming Events Announced</h3>
                    <p class="text-sm text-[var(--text-muted)]">
                        We are currently planning our next technology and literacy outreach sessions. Check back soon or join our newsletter for updates!
                    </p>
                    <a href="{{ url('/get-involved') }}" class="inline-block text-xs font-bold uppercase tracking-wider text-maroon hover:underline">
                        Get Involved as a Volunteer &rarr;
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($upcomingEvents as $event)
                        <div class="bg-[var(--bg)] border border-[var(--border)] rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                            {{-- Image Container --}}
                            <div class="relative h-52 overflow-hidden bg-stone-900">
                                <img src="{{ $event->banner_image_url }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                
                                {{-- Date Badge Overlay --}}
                                <div class="absolute top-4 left-4 flex flex-col items-center bg-white/95 dark:bg-stone-900/95 backdrop-blur-md rounded-xl px-2.5 py-1.5 shadow-md text-center">
                                    <span class="text-[10px] font-bold uppercase text-crimson dark:text-[var(--accent-cream)]">
                                        {{ $event->event_date ? $event->event_date->format('M') : 'TBA' }}
                                    </span>
                                    <span class="text-lg font-black text-stone-900 dark:text-white leading-none">
                                        {{ $event->event_date ? $event->event_date->format('d') : '--' }}
                                    </span>
                                </div>

                                {{-- Registration Badge --}}
                                <div class="absolute top-4 right-4">
                                    @if ($event->is_registration_open)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-sm">
                                            Open
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-stone-700 text-white shadow-sm">
                                            Closed
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6 flex flex-col flex-grow justify-between space-y-4">
                                <div class="space-y-2.5">
                                    <div class="flex items-center gap-1.5 text-xs text-[var(--text-muted)]">
                                        <span>📍</span>
                                        <span class="line-clamp-1">{{ $event->venue ?? 'Ijero-Ekiti, Nigeria' }}</span>
                                    </div>

                                    <h3 class="text-lg font-bold text-[var(--heading)] group-hover:text-maroon transition-colors line-clamp-2">
                                        <a href="{{ route('events.show', $event->slug) }}">
                                            {{ $event->title }}
                                        </a>
                                    </h3>

                                    <p class="text-sm text-[var(--text)] line-clamp-3 leading-relaxed">
                                        {{ $event->summary }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                                    <span class="text-xs font-semibold text-[var(--text-muted)]">
                                        {{ $event->formatted_time }}
                                    </span>

                                    <a href="{{ route('events.show', $event->slug) }}" 
                                       class="inline-flex items-center gap-1 text-xs font-bold text-maroon hover:text-crimson dark:text-[var(--accent-cream)] transition-colors">
                                        <span>Details</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- 4. Past Events & Historic Milestones Section --}}
    @if ($pastEvents->isNotEmpty())
        <section class="py-20 bg-[var(--bg)] transition-colors border-t border-[var(--border)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                            Past Impact
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight mt-1">
                            Historic Milestones & Completed Events
                        </h2>
                    </div>
                    <div>
                        <a href="{{ url('/gallery') }}" class="text-xs font-bold uppercase tracking-wider text-maroon hover:underline flex items-center gap-1">
                            <span>Browse Past Event Photos</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($pastEvents as $past)
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 sm:p-8 flex flex-col justify-between space-y-4 hover:border-maroon/50 transition-all">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between text-xs text-[var(--text-muted)]">
                                    <span class="font-bold text-stone-500">Concluded on {{ $past->formatted_date }}</span>
                                    <span class="px-2 py-0.5 rounded-full bg-stone-100 dark:bg-stone-800 text-[10px] font-semibold text-stone-600 dark:text-stone-300">
                                        Past Event
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold text-[var(--heading)]">
                                    <a href="{{ route('events.show', $past->slug) }}" class="hover:text-maroon transition-colors">
                                        {{ $past->title }}
                                    </a>
                                </h3>

                                <p class="text-sm text-[var(--text)] leading-relaxed">
                                    {{ $past->summary }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                                <span class="text-xs text-[var(--text-muted)]">📍 {{ $past->venue }}</span>
                                <a href="{{ route('events.show', $past->slug) }}" class="text-xs font-bold text-maroon hover:underline">
                                    Event Archive &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 5. Call To Action Banner --}}
    <section class="py-16 sm:py-20 bg-linear-to-r from-maroon to-crimson text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                Partner with Us on Our Next Gathering
            </h2>
            <p class="text-white/90 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                Whether you wish to sponsor event materials, mentor students during coding labs, or donate computing equipment, your partnership transforms lives.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="{{ url('/get-involved') }}" 
                   class="px-8 py-3.5 rounded-full text-sm font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    Partner or Volunteer &rarr;
                </a>
                <a href="{{ url('/contact') }}" 
                   class="px-8 py-3.5 rounded-full text-sm font-bold border border-white/40 text-white hover:bg-white/10 transition-all duration-200">
                    Contact Event Team
                </a>
            </div>
        </div>
    </section>
</x-layout>
