<x-layout
    title="Dzuels Educational Foundation | Quality Education and Digital Skills in Nigeria"
    description="Dzuels Educational Foundation is a Nigerian non-profit organisation transforming lives through quality education, youth literacy interventions, and digital skills acquisition in Ekiti State."
    :canonical="url('/')"
    :og-image="asset('front/images/dzuelsCollage.webp')">
    {{-- 1. Hero Section --}}
    @php
        $heroSec = $sections['hero'] ?? null;
    @endphp
    <section class="relative bg-maroon text-white py-20 sm:py-28 lg:py-36 overflow-hidden">
        {{-- Background Image with Gradient Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ $heroSec?->getImageUrl() ?: asset('front/images/home_1.webp') }}" 
                 alt="Dzuels Educational Foundation students in a classroom" 
                 loading="eager"
                 fetchpriority="high"
                 decoding="async"
                 class="w-full h-full object-cover object-center opacity-45" />
            <div class="absolute inset-0 bg-linear-to-r from-[#2B0203]/90 via-maroon/75 to-[#242F40]/65"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                    {{ $heroSec?->subheadline ?: 'Dzuels Educational Foundation' }}
                </span>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $heroSec?->headline ?: 'Addressing poverty through quality education and skills acquisition' }}
                </h1>

                <p class="text-lg sm:text-xl text-white/90 leading-relaxed max-w-2xl">
                    {{ $heroSec?->body_text ?: 'Empowering indigent Nigerian children, youth, and educators with free digital literacy, school support, leadership mentoring, and community welfare.' }}
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a href="{{ $heroSec?->button_url ?: url('/donate') }}" 
                       class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                        {{ $heroSec?->button_text ?: 'Donate Now' }}
                    </a>
                    <a href="{{ url('/get-involved') }}" 
                       class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                        Become a Volunteer
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Floating Impact Statistics Band --}}
    <section class="relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 mb-8">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-6 sm:p-10 shadow-xl transition-colors">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-0 divide-y md:divide-y-0 md:divide-x divide-[var(--border)]">
                {{-- Stat 1: ICT Students --}}
                <div class="flex flex-col gap-2 pt-4 first:pt-0 md:pt-0 md:px-6 first:md:pl-0">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl sm:text-5xl font-black text-[var(--heading)] tracking-tight">
                            {{ number_format($stats['ict_students'] ?? 165) }}
                        </span>
                        <span class="text-sm font-bold text-mint font-sans">+</span>
                    </div>
                    <span class="text-sm sm:text-base font-medium text-[var(--text)]">
                        Ekiti students in ICT training
                    </span>
                    <div class="w-full bg-[var(--border-subtle)] h-1.5 rounded-full overflow-hidden mt-3">
                        <div class="bg-mint h-full rounded-full w-[85%]"></div>
                    </div>
                </div>

                {{-- Stat 2: Literacy Intervention --}}
                <div class="flex flex-col gap-2 pt-6 md:pt-0 md:px-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl sm:text-5xl font-black text-[var(--heading)] tracking-tight">
                            {{ number_format($stats['literacy_students'] ?? 591) }}
                        </span>
                        <span class="text-sm font-bold text-crimson font-sans">+</span>
                    </div>
                    <span class="text-sm sm:text-base font-medium text-[var(--text)]">
                        Ekiti students in literacy intervention
                    </span>
                    <div class="w-full bg-[var(--border-subtle)] h-1.5 rounded-full overflow-hidden mt-3">
                        <div class="bg-maroon dark:bg-crimson h-full rounded-full w-[92%]"></div>
                    </div>
                </div>

                {{-- Stat 3: Schools Supported --}}
                <div class="flex flex-col gap-2 pt-6 md:pt-0 md:px-6 last:md:pr-0">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl sm:text-5xl font-black text-[var(--heading)] tracking-tight">
                            {{ number_format($stats['schools_supported'] ?? 15) }}
                        </span>
                        <span class="text-sm font-bold text-amber-500 font-sans">+</span>
                    </div>
                    <span class="text-sm sm:text-base font-medium text-[var(--text)]">
                        Schools Supported
                    </span>
                    <div class="w-full bg-[var(--border-subtle)] h-1.5 rounded-full overflow-hidden mt-3">
                        <div class="bg-[var(--accent-cream)] dark:bg-amber-400 h-full rounded-full w-[75%]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Who We Are Section --}}
    @php
        $whoSec = $sections['who_we_are'] ?? null;
    @endphp
    <section class="py-16 sm:py-24 bg-[var(--bg)] transition-colors overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Image Frame with Offset Cream Border --}}
                <div class="relative mx-auto lg:mx-0 w-full max-w-lg lg:max-w-none">
                    <div class="absolute -top-3 -left-3 sm:-top-4 sm:-left-4 w-full h-full border-3 border-[var(--accent-cream)] rounded-3xl z-0 pointer-events-none"></div>
                    <img src="{{ $whoSec?->getImageUrl() ?: asset('front/images/about_1.webp') }}" 
                         alt="Dzuels foundation teachers and beneficiaries in an interactive session" 
                         loading="lazy"
                         decoding="async"
                         class="relative z-10 w-full h-[360px] sm:h-[420px] object-cover rounded-3xl shadow-xl border border-[var(--border)]" />
                </div>

                {{-- Text Content --}}
                <div class="space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                            {{ $whoSec?->subheadline ?: 'Who We Are' }}
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight mt-2">
                            {{ $whoSec?->headline ?: 'Transforming lives page by page' }}
                        </h2>
                    </div>

                    @if ($whoSec?->body_text)
                        <div class="text-base sm:text-lg text-[var(--text)] leading-relaxed space-y-4">
                            {!! nl2br(e($whoSec->body_text)) !!}
                        </div>
                    @else
                        <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed">
                            Founded on 22nd June 2015 and registered as a charity in Nigeria, Dzuels Educational Foundation is dedicated to breaking the cycle of poverty by unlocking potential through education and practical skills.
                        </p>

                        <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed">
                            Ours is an integrated approach to the total well-being of the child: paying school fees, providing educational materials, equipping non-profit schools, mentoring beneficiaries, facilitating transformational leadership training, feeding families, and enabling health services.
                        </p>
                    @endif

                    <div class="pt-2">
                        <a href="{{ $whoSec?->button_url ?: url('/about') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold border-2 border-maroon text-maroon hover:bg-maroon hover:text-white dark:border-[var(--accent-cream)] dark:text-[var(--accent-cream)] dark:hover:bg-[var(--accent-cream)] dark:hover:text-maroon transition-all duration-200">
                            {{ $whoSec?->button_text ?: 'Read Our Full Story' }} <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Vision & Mission Section --}}
    <section class="py-16 sm:py-20 bg-[var(--bg-alt)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Vision Card --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-md transition-all duration-200 flex gap-6 items-start">
                    <div class="w-14 h-14 rounded-2xl bg-[var(--accent-cream-light)] dark:bg-[var(--surface-alt)] flex items-center justify-center shrink-0 text-2xl text-maroon dark:text-[var(--accent-cream)] shadow-xs">
                        👁️
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-[var(--heading)]">Our Vision</h3>
                        <p class="text-[var(--text)] leading-relaxed text-base sm:text-lg">
                            To alleviate poverty through quality education and lifelong skills acquisition.
                        </p>
                    </div>
                </div>

                {{-- Mission Card --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-md transition-all duration-200 flex gap-6 items-start">
                    <div class="w-14 h-14 rounded-2xl bg-[var(--accent-cream-light)] dark:bg-[var(--surface-alt)] flex items-center justify-center shrink-0 text-2xl text-maroon dark:text-[var(--accent-cream)] shadow-xs">
                        🎯
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-[var(--heading)]">Our Mission</h3>
                        <p class="text-[var(--text)] leading-relaxed text-base sm:text-lg">
                            To make quality education accessible to every child within reach, regardless of circumstance.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. DRNICER Values Band --}}
    <section class="py-16 sm:py-20 bg-maroon text-white transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    The DRNICER Principles
                </h3>
                <p class="text-[var(--accent-cream)] text-sm sm:text-base">
                    The core values that guide our mentors, volunteers, and beneficiaries
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-4">
                {{-- D --}}
                <div class="bg-white/10 hover:bg-white/15 border border-white/15 hover:border-[var(--accent-cream)] rounded-2xl p-5 text-center transition-all duration-200 hover:-translate-y-1">
                    <div class="text-3xl sm:text-4xl font-black text-[var(--accent-cream)] leading-none mb-2">D</div>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white">Discipline</div>
                </div>
                {{-- R --}}
                <div class="bg-white/10 hover:bg-white/15 border border-white/15 hover:border-[var(--accent-cream)] rounded-2xl p-5 text-center transition-all duration-200 hover:-translate-y-1">
                    <div class="text-3xl sm:text-4xl font-black text-[var(--accent-cream)] leading-none mb-2">R</div>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white">Responsibility</div>
                </div>
                {{-- N --}}
                <div class="bg-white/10 hover:bg-white/15 border border-white/15 hover:border-[var(--accent-cream)] rounded-2xl p-5 text-center transition-all duration-200 hover:-translate-y-1">
                    <div class="text-3xl sm:text-4xl font-black text-[var(--accent-cream)] leading-none mb-2">N</div>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white">No-Excuse</div>
                </div>
                {{-- I --}}
                <div class="bg-white/10 hover:bg-white/15 border border-white/15 hover:border-[var(--accent-cream)] rounded-2xl p-5 text-center transition-all duration-200 hover:-translate-y-1">
                    <div class="text-3xl sm:text-4xl font-black text-[var(--accent-cream)] leading-none mb-2">I</div>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white">Integrity</div>
                </div>
                {{-- C --}}
                <div class="bg-white/10 hover:bg-white/15 border border-white/15 hover:border-[var(--accent-cream)] rounded-2xl p-5 text-center transition-all duration-200 hover:-translate-y-1">
                    <div class="text-3xl sm:text-4xl font-black text-[var(--accent-cream)] leading-none mb-2">C</div>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white">Compassion</div>
                </div>
                {{-- E --}}
                <div class="bg-white/10 hover:bg-white/15 border border-white/15 hover:border-[var(--accent-cream)] rounded-2xl p-5 text-center transition-all duration-200 hover:-translate-y-1">
                    <div class="text-3xl sm:text-4xl font-black text-[var(--accent-cream)] leading-none mb-2">E</div>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white">Excellence</div>
                </div>
                {{-- R --}}
                <div class="bg-white/10 hover:bg-white/15 border border-white/15 hover:border-[var(--accent-cream)] rounded-2xl p-5 text-center transition-all duration-200 hover:-translate-y-1">
                    <div class="text-3xl sm:text-4xl font-black text-[var(--accent-cream)] leading-none mb-2">R</div>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white">Respect</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. Our Causes Section --}}
    @php
        $causeSec = $sections['causes'] ?? null;
    @endphp
    <section class="py-20 lg:py-28 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                    {{ $causeSec?->subheadline ?: 'Our Causes' }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    {{ $causeSec?->headline ?: 'Holistic support for youth & community development' }}
                </h2>
                <p class="text-[var(--text-muted)] text-base sm:text-lg">
                    {{ $causeSec?->body_text ?: 'We combine direct education sponsorship with family stability and health awareness.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                {{-- Image Column --}}
                <div class="lg:col-span-5">
                    <img src="{{ $causeSec?->getImageUrl() ?: asset('front/images/cause.webp') }}" 
                         alt="Nigerian children and community beneficiaries supported by Dzuels Foundation" 
                         loading="lazy"
                         decoding="async"
                         class="w-full h-[380px] sm:h-[440px] object-cover rounded-3xl shadow-xl border border-[var(--border)]" />
                </div>

                {{-- Checklist Column --}}
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[var(--accent-cream)] text-maroon">
                        Integrated Intervention
                    </span>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] leading-snug">
                        We empower indigent children across 8 key pillars
                    </h3>

                    <p class="text-[var(--text)] text-base leading-relaxed">
                        Every intervention is targeted to keep children in classrooms and give youth real 21st-century earning capabilities.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Paying school fees</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Supplying books & kits</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Equipping non-profit labs</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Mentoring beneficiaries</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Transformational leadership</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Feeding indigent families</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Enabling health services</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0">✓</span>
                            <span class="text-sm sm:text-base font-medium text-[var(--text)]">Social vices awareness</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. Featured Project Card --}}
    @php
        $featSec = $sections['featured_project'] ?? null;
    @endphp
    <section class="py-16 sm:py-20 bg-[var(--bg-alt)] transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl overflow-hidden shadow-xl grid grid-cols-1 lg:grid-cols-12 transition-colors">
                <div class="lg:col-span-6 relative min-h-[300px] lg:min-h-full">
                    <img src="{{ $featSec?->getImageUrl() ?: asset('front/images/dzuelsCollage.webp') }}" 
                         alt="Dzuels Ijero-Ekiti digital literacy lab and students" 
                         loading="lazy"
                         decoding="async"
                         class="w-full h-full object-cover object-center absolute inset-0" />
                </div>
                <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-center space-y-6">
                    <div>
                        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-mint/20 text-emerald-800 dark:text-emerald-300">
                            {{ $featSec?->subheadline ?: 'Featured Project' }}
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] leading-snug mt-3">
                            {{ $featSec?->headline ?: 'Digital Infrastructure & Literacy in Ijero-Ekiti' }}
                        </h3>
                    </div>

                    <p class="text-[var(--text)] leading-relaxed text-base">
                        {{ $featSec?->body_text ?: 'Free literacy interventions and digital skills training for teachers and students in Ijero-Ekiti, Southwest Nigeria, in active partnership with the Information Technology Seed Development Initiative (SeedDev).' }}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ $featSec?->button_url ?: url('/programmes/ijero-digital-literacy') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-semibold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md">
                            {{ $featSec?->button_text ?: 'Learn More About Project' }}
                        </a>
                        <a href="{{ url('/get-involved') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-semibold border border-[var(--border)] text-[var(--text-strong)] hover:border-maroon transition-all duration-200">
                            Volunteer for ITD
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. Upcoming Event Callout / Teaser --}}
    <section class="py-16 sm:py-20 bg-[var(--bg)] transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl p-8 sm:p-12 bg-linear-to-r from-maroon to-crimson text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30">
                            Upcoming Flagship Event • Oct 17, 2026
                        </span>
                        <span class="text-xs font-semibold text-[var(--accent-cream)] italic">
                            "Learning Today, Creating Tomorrow"
                        </span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                        Ijero Tech-Day 2026 (ITD 2026)
                    </h3>
                    <p class="text-white/90 text-sm sm:text-base max-w-xl leading-relaxed">
                        Empowering 300 children with practical AI tools across 10 rotating batches of 30, inside interactive labs and garden booths, plus 22 cohort graduate certificates at AAoJ Memorial Learning Center.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ url('/events/ijero-tech-day-2026') }}" 
                       class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-sm font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Register to Attend &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 10. Partners Strip --}}
    <section class="py-16 sm:py-20 bg-[var(--bg-alt)] text-center transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                Our Valued Partners
            </span>
            <h3 class="text-2xl sm:text-3xl font-bold text-[var(--heading)] mt-2 mb-10">
                Collaborating for greater impact
            </h3>

            <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-14 max-w-5xl mx-auto">
                @if (isset($partners) && $partners->isNotEmpty())
                    @foreach ($partners as $partner)
                        @if ($partner->website_url)
                            <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-block transition-transform hover:scale-105" title="{{ $partner->name }}">
                                <img src="{{ $partner->logo_url }}" 
                                     alt="{{ $partner->name }}" 
                                     loading="lazy"
                                     decoding="async"
                                     class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm filter grayscale hover:grayscale-0 transition-all duration-300 opacity-80 hover:opacity-100" />
                            </a>
                        @else
                            <img src="{{ $partner->logo_url }}" 
                                 alt="{{ $partner->name }}" 
                                 loading="lazy"
                                 decoding="async"
                                 class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm filter grayscale hover:grayscale-0 hover:scale-105 transition-all duration-300 opacity-80 hover:opacity-100" />
                        @endif
                    @endforeach
                @else
                    <img src="{{ asset('front/images/ascend.jpg') }}" 
                         alt="ASCEND Education Foundation" 
                         loading="lazy"
                         decoding="async"
                         class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm filter grayscale hover:grayscale-0 hover:scale-105 transition-all duration-300 opacity-80 hover:opacity-100" />
                    <img src="{{ asset('front/images/SAA.jpg') }}" 
                         alt="Solape Adesuyi Associates" 
                         loading="lazy"
                         decoding="async"
                         class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm filter grayscale hover:grayscale-0 hover:scale-105 transition-all duration-300 opacity-80 hover:opacity-100" />
                    <img src="{{ asset('front/images/helpmeetaneed.png') }}" 
                         alt="Help Meet A Need" 
                         loading="lazy"
                         decoding="async"
                         class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm filter grayscale hover:grayscale-0 hover:scale-105 transition-all duration-300 opacity-80 hover:opacity-100" />
                    <img src="{{ asset('front/images/intinternational.png') }}" 
                         alt="Int International" 
                         loading="lazy"
                         decoding="async"
                         class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm filter grayscale hover:grayscale-0 hover:scale-105 transition-all duration-300 opacity-80 hover:opacity-100" />
                @endif
            </div>
        </div>
    </section>

    {{-- 11. Final Call to Action Banner --}}
    @php
        $ctaSec = $sections['cta'] ?? null;
    @endphp
    <section class="py-20 lg:py-28 text-white text-center relative overflow-hidden bg-radial from-crimson/40 via-maroon to-[#2B0203]">
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">
                {{ $ctaSec?->headline ?: 'You too can make a difference' }}
            </h2>
            <p class="text-base sm:text-lg text-[var(--accent-cream)] max-w-2xl mx-auto leading-relaxed">
                {{ $ctaSec?->body_text ?: "Whether by sponsoring a child's tuition, volunteering your skills, or partnering with our training labs, your support creates lasting change." }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ $ctaSec?->button_url ?: url('/donate') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                    {{ $ctaSec?->button_text ?: 'Make a Donation' }}
                </a>
                <a href="{{ url('/get-involved') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                    Join as a Volunteer
                </a>
            </div>
        </div>
    </section>
</x-layout>
