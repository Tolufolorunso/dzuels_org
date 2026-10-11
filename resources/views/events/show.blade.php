<x-layout 
    :title="$event->seo_title ?? ($event->title . ' | Dzuels Educational Foundation')"
    :description="$event->seo_description ?? $event->summary"
    :canonical="route('events.show', $event->slug)"
    :og-image="$event->featured_image_url ?? asset('front/images/techday.webp')"
    og-type="article"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Events', 'url' => route('events.index')],
        ['name' => $event->title, 'url' => route('events.show', $event->slug)]
    ]">

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
    <section class="py-12 lg:py-16 bg-[var(--surface)] border-b border-[var(--border)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Date Badge --}}
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-maroon/10 text-maroon dark:bg-maroon/30 dark:text-[var(--accent-cream)] border border-maroon/20">
                    <span>📅</span> {{ $event->formatted_date }}
                </span>

                {{-- Time Badge --}}
                @if ($event->event_date)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-stone-100 dark:bg-stone-800 text-stone-700 dark:text-stone-300">
                        <span>⏰</span> {{ $event->formatted_time }}
                    </span>
                @endif

                {{-- Registration Status Badge --}}
                @if ($event->is_registration_open)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Registration Open
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-stone-200 dark:bg-stone-800 text-stone-700 dark:text-stone-300">
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
        </div>
    </section>

    {{-- Main Content & Registration Layout --}}
    <section class="py-16 lg:py-20 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                {{-- Left Column: Banner, Narrative, Schedule & Highlights (7 Cols) --}}
                <div class="lg:col-span-7 space-y-12">
                    {{-- Banner Media Card --}}
                    <div class="rounded-3xl overflow-hidden border border-[var(--border)] shadow-xl bg-stone-900">
                        <img src="{{ $event->banner_image_url }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-auto max-h-[460px] object-cover object-center">
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

                    {{-- Event Highlights & Gallery Preview --}}
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-10 shadow-sm space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-[var(--border)]">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-[var(--accent-cream)] block">
                                    Moments of Impact
                                </span>
                                <h2 class="text-2xl font-bold text-[var(--heading)] mt-0.5">
                                    Event Highlights & Gallery
                                </h2>
                            </div>
                            <a href="{{ url('/gallery') }}" class="text-xs font-bold text-maroon hover:underline">
                                Full Gallery &rarr;
                            </a>
                        </div>

                        <p class="text-sm text-[var(--text)] leading-relaxed">
                            Experience the energy, innovation, and smiles from our past technology gatherings in Ijero-Ekiti:
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800">
                                <img src="{{ asset('front/images/techday.webp') }}" alt="Tech Day students and attendees" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800">
                                <img src="{{ asset('front/images/dzuelsCollage.webp') }}" alt="Digital lab training in Ijero" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="rounded-2xl overflow-hidden aspect-4/3 bg-stone-100 dark:bg-stone-800">
                                <img src="{{ asset('front/images/about_1.webp') }}" alt="Community beneficiaries" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        </div>
                    </div>

                    {{-- Social Share Strip --}}
                    <div class="p-6 rounded-2xl bg-[var(--surface)] border border-[var(--border)] flex flex-wrap items-center justify-between gap-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--heading)]">
                            Spread the Word:
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . url()->current()) }}" 
                               target="_blank" 
                               class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                                WhatsApp
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(url()->current()) }}" 
                               target="_blank" 
                               class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 hover:bg-stone-200 transition-colors">
                                X / Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                               target="_blank" 
                               class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                Facebook
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Registration Card & Facts Sidebar (5 Cols) --}}
                <div class="lg:col-span-5 space-y-8 sticky top-28">

                    {{-- Registration Card --}}
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-9 shadow-xl relative overflow-hidden" id="register">
                        {{-- Top Accent Bar --}}
                        <div class="absolute top-0 left-0 right-0 h-2 bg-linear-to-r from-maroon via-crimson to-mint"></div>

                        {{-- Flash Success Message --}}
                        @if (session('success'))
                            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700 text-emerald-900 dark:text-emerald-200 text-sm font-medium flex items-start gap-3">
                                <span class="text-emerald-600 text-lg">✓</span>
                                <div>
                                    <h4 class="font-bold">Registration Confirmed!</h4>
                                    <p class="text-xs mt-1">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Flash Error Message --}}
                        @if (session('error'))
                            <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-300 dark:border-rose-700 text-rose-900 dark:text-rose-200 text-sm font-medium flex items-start gap-3">
                                <span class="text-rose-600 text-lg">⚠️</span>
                                <div>
                                    <h4 class="font-bold">Notice</h4>
                                    <p class="text-xs mt-1">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($event->is_registration_open)
                            <div class="space-y-2 mb-6">
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
                                           value="{{ old('full_name') }}" 
                                           required 
                                           placeholder="e.g. Adeola Olawale" 
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
                                           value="{{ old('email') }}" 
                                           required 
                                           placeholder="e.g. adeola@example.com" 
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
                                           value="{{ old('phone') }}" 
                                           required 
                                           placeholder="e.g. +234 803 123 4567" 
                                           class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                                    @error('phone')
                                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Category / Attendee Type --}}
                                <div>
                                    <label for="attendee_type" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-1.5">
                                        I am Attending As:
                                    </label>
                                    <select id="attendee_type" 
                                            name="attendee_type" 
                                            class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                                        <option value="student" {{ old('attendee_type') == 'student' ? 'selected' : '' }}>Student / Youth (Batch Participant)</option>
                                        <option value="teacher" {{ old('attendee_type') == 'teacher' ? 'selected' : '' }}>Teacher / School Representative</option>
                                        <option value="cohort_graduate" {{ old('attendee_type') == 'cohort_graduate' ? 'selected' : '' }}>Cohort Graduate (Certificate Recipient)</option>
                                        <option value="guest" {{ old('attendee_type') == 'guest' ? 'selected' : '' }}>Community Guest / Dignitary</option>
                                        <option value="educator" {{ old('attendee_type') == 'educator' ? 'selected' : '' }}>Educator / School Administrator</option>
                                        <option value="tech_enthusiast" {{ old('attendee_type') == 'tech_enthusiast' ? 'selected' : '' }}>Tech Enthusiast / Developer</option>
                                        <option value="partner" {{ old('attendee_type') == 'partner' ? 'selected' : '' }}>Partner / Sponsor Representative</option>
                                        <option value="general" {{ old('attendee_type') == 'general' ? 'selected' : '' }}>General Guest</option>
                                    </select>
                                    @error('attendee_type')
                                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Notes / Expectations --}}
                                <div>
                                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-1.5">
                                        School / Batch / Special Notes (Optional)
                                    </label>
                                    <textarea id="notes" 
                                              name="notes" 
                                              rows="2" 
                                              placeholder="School name, class, batch or special notes (e.g. Batch participant, teacher, or cohort graduate)..." 
                                              class="w-full px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] text-sm text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">{{ old('notes') }}</textarea>
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit" 
                                        class="w-full py-3.5 px-6 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer flex items-center justify-center gap-2">
                                    <span>Complete Free Registration</span>
                                    <span>&rarr;</span>
                                </button>
                            </form>
                        @else
                            {{-- Registration Closed State --}}
                            <div class="text-center py-6 space-y-4">
                                <div class="w-14 h-14 rounded-full bg-stone-100 dark:bg-stone-800 flex items-center justify-center mx-auto text-2xl">
                                    🔒
                                </div>
                                <h3 class="text-xl font-bold text-[var(--heading)]">Registration is Closed</h3>
                                <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                    Registration for this event has reached capacity or concluded. If you have an inquiry, please contact our event coordinator.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ url('/contact') }}" class="inline-block text-xs font-bold text-maroon hover:underline">
                                        Contact Us &rarr;
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

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
                                    <span class="font-bold text-[var(--text-strong)] block">Co-Organizers</span>
                                    <span class="text-[var(--text-muted)]">Dzuels Educational Foundation & SeedDev Initiative</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Other Upcoming Events Section --}}
    @if ($otherEvents->isNotEmpty())
        <section class="py-16 bg-[var(--surface)] border-t border-[var(--border)] transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-[var(--heading)]">
                        More Upcoming Events
                    </h2>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-maroon hover:underline">
                        View All Events &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($otherEvents as $other)
                        <div class="p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border)] space-y-3 flex flex-col justify-between hover:border-maroon/40 transition-colors">
                            <div class="space-y-2">
                                <span class="text-xs font-mono font-bold text-crimson dark:text-[var(--accent-cream)]">
                                    {{ $other->formatted_date }}
                                </span>
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
