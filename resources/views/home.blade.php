<x-layout
    title="Dzuels Educational Foundation | Quality Education and Digital Skills in Nigeria"
    description="Dzuels Educational Foundation is a Nigerian non-profit organisation transforming lives through quality education, youth literacy interventions, and digital skills acquisition in Ekiti State."
    :canonical="url('/')"
    :og-image="asset('front/images/dzuelsCollage.webp')">
    {{-- 1. Hero Section --}}
    @php
        $heroSec = $sections['hero'] ?? null;
    @endphp
    @if ($heroSec?->is_active ?? true)
    <section class="relative bg-maroon text-white py-20 sm:py-28 lg:py-36 overflow-hidden">
        {{-- Background Image with Gradient Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ $heroSec?->getImageUrl() ?: asset('front/images/home_1.webp') }}" 
                 alt="Dzuels Educational Foundation students in a classroom" 
                 loading="eager"
                 fetchpriority="high"
                 decoding="async"
                 class="w-full h-full object-cover object-center opacity-45" />
            <div class="absolute inset-0 pointer-events-none" 
                 style="background: linear-gradient(135deg, rgba(43, 2, 3, 0.90) 0%, rgba(106, 4, 6, 0.78) 55%, rgba(36, 47, 64, 0.65) 100%);"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                    {!! format_cms_text($heroSec?->subheadline ?: 'Dzuels Educational Foundation') !!}
                </span>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    {!! format_cms_text($heroSec?->headline ?: 'Addressing poverty through quality education and skills acquisition') !!}
                </h1>

                <p class="text-lg sm:text-xl text-white/90 leading-relaxed max-w-2xl">
                    {!! format_cms_text($heroSec?->body_text ?: 'Empowering indigent Nigerian children, youth, and educators with free digital literacy, school support, leadership mentoring, and community welfare.') !!}
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a href="{{ $heroSec?->button_url ?: url('/donate') }}" 
                       class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                        {!! format_cms_text($heroSec?->button_text ?: 'Donate Now') !!}
                    </a>
                    <a href="{{ url('/get-involved') }}" 
                       class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                        Become a Volunteer
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 2. Floating Impact Statistics Band --}}
    <section class="relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 mb-8">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl sm:rounded-3xl p-6 sm:p-10 shadow-xl transition-colors">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-0 divide-y md:divide-y-0 md:divide-x divide-[var(--border)]">
                {{-- Stat 1: ICT Students --}}
                <div class="flex flex-col gap-2 pt-4 first:pt-0 md:pt-0 md:px-6 first:md:pl-0">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl sm:text-5xl font-black text-[var(--heading)] tracking-tight">
                            {{ number_format($stats['ict_students'] ?? 500) }}
                        </span>
                        <span class="text-sm font-bold text-mint font-sans">+</span>
                    </div>
                    <span class="text-sm sm:text-base font-medium text-[var(--text)]">
                        Ekiti students in computer lab & coding training
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

                {{-- Stat 3: Teachers Trained --}}
                <div class="flex flex-col gap-2 pt-6 md:pt-0 md:px-6 last:md:pr-0">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl sm:text-5xl font-black text-[var(--heading)] tracking-tight">
                            {{ number_format($stats['schools_supported'] ?? 30) }}
                        </span>
                        <span class="text-sm font-bold text-amber-500 font-sans">+</span>
                    </div>
                    <span class="text-sm sm:text-base font-medium text-[var(--text)]">
                        Teachers Trained in Digital Skills
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
    @if ($whoSec?->is_active ?? true)
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
                            {!! format_cms_text($whoSec?->subheadline ?: 'Who We Are') !!}
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight mt-2">
                            {!! format_cms_text($whoSec?->headline ?: 'Transforming lives page by page') !!}
                        </h2>
                    </div>

                    @if ($whoSec?->body_text)
                        <div class="text-base sm:text-lg text-[var(--text)] leading-relaxed space-y-4">
                            {!! format_cms_text($whoSec->body_text) !!}
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
                            {!! format_cms_text($whoSec?->button_text ?: 'Read Our Full Story') !!} <span aria-hidden="true">&rarr;</span>
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
    @endif

    {{-- 6. Our Impact Causes Section --}}
    @php
        $causeSec = $sections['causes'] ?? null;
        $defaultPillars = [
            [
                'title' => 'Education Support',
                'ministry' => "Paul's academy",
                'description' => 'Paying school fees, supplying curriculum textbooks, notebooks, uniforms, and educational kits to keep children in classrooms.',
                'icon' => '🎓',
            ],
            [
                'title' => 'Skills Acquisition',
                'ministry' => "Peter's net",
                'description' => 'Hands-on digital literacy, computer coding, and practical vocational training equipping youth with 21st-century self-reliance.',
                'icon' => '💻',
            ],
            [
                'title' => 'Advocacy',
                'ministry' => 'Deborah congress',
                'description' => 'Championing child protection, education policy awareness, equal opportunity for indigent girls, and youth rights.',
                'icon' => '📢',
            ],
            [
                'title' => 'Feeding families',
                'ministry' => "Naomi's kitchen",
                'description' => 'Targeted nutritional relief, food assistance, and hunger alleviation ensuring indigent pupils have the energy to learn.',
                'icon' => '🍲',
            ],
            [
                'title' => 'Transformative leadership Training',
                'ministry' => "Priscilla's upper room",
                'description' => 'Values-driven mentorship, ethical leadership training, and mindset transformation for emerging community leaders.',
                'icon' => '🌟',
            ],
            [
                'title' => 'Health awareness and support',
                'ministry' => "Luke's Tent",
                'description' => 'Health education, medical screenings, personal hygiene kits, and basic medical support for vulnerable children and families.',
                'icon' => '🩺',
            ],
        ];
        $activePillars = $causeSec?->meta_data['pillars'] ?? $defaultPillars;
        $taglineBanner = $causeSec?->meta_data['tagline'] ?? 'AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD';
    @endphp
    @if ($causeSec?->is_active ?? true)
    <section class="py-20 lg:py-28 bg-[var(--bg)] transition-colors relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Section Heading --}}
            <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-maroon/10 dark:bg-maroon/20 text-maroon dark:text-[var(--accent-cream)] border border-maroon/15 dark:border-maroon/30">
                    <span>🌟</span> {!! format_cms_text($causeSec?->subheadline ?: 'Our 6 Impact Causes') !!}
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[var(--heading)] tracking-tight">
                    {!! format_cms_text($causeSec?->headline ?: 'Programs that Bridge the Opportunity Divide') !!}
                </h2>
                <p class="text-[var(--text-muted)] text-base sm:text-lg leading-relaxed">
                    {!! format_cms_text($causeSec?->body_text ?: 'From digital hubs in Ijero-Ekiti to annual literacy contest, and much more in between, our causes are designed to uplift communities from the ground up.') !!}
                </p>

                {{-- Foundational Tagline Banner (from cause.jpg) --}}
                @if(!empty($taglineBanner))
                    <div class="pt-2">
                        <div class="inline-block px-5 py-2.5 rounded-2xl bg-[var(--surface-alt)] border border-[var(--border)] shadow-xs">
                            <span class="text-xs sm:text-sm font-extrabold text-maroon dark:text-[var(--accent-cream)] tracking-wider uppercase font-sans">
                                ✦ {!! format_cms_text($taglineBanner) !!} ✦
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                {{-- Left Side: Visual Foundation Graphic / Imagery Frame --}}
                <div class="lg:col-span-5 relative group">
                    <div class="absolute -top-3 -left-3 sm:-top-4 sm:-left-4 w-full h-full border-3 border-[var(--accent-cream)] dark:border-maroon/40 rounded-3xl z-0 pointer-events-none group-hover:translate-x-1 group-hover:translate-y-1 transition-transform duration-300"></div>
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-xl border border-[var(--border)] bg-[var(--surface)] p-2 sm:p-3 flex items-center justify-center">
                        <img src="{{ $causeSec?->getImageUrl() ?: asset('front/images/cause.webp') }}" 
                             alt="Dzuels Educational Foundation - An Integrated Approach to the Total Well Being of the Child" 
                             loading="lazy"
                             decoding="async"
                             class="w-full h-auto max-h-[580px] object-contain rounded-2xl transition-transform duration-500 group-hover:scale-101" />
                    </div>
                </div>

                {{-- Right Side: The 6 Authentic Pillars Grid --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[var(--accent-cream)] text-maroon">
                            6 Foundational Pillars
                        </span>
                        <a href="{{ url('/donate') }}" class="text-xs font-bold text-maroon dark:text-[var(--accent-cream)] hover:underline inline-flex items-center gap-1">
                            <span>Sponsor a Cause</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($activePillars as $index => $pillar)
                            <div class="p-5 rounded-2xl bg-[var(--surface)] border border-[var(--border)] shadow-2xs hover:shadow-md hover:border-maroon/30 dark:hover:border-maroon/50 transition-all duration-200 flex flex-col justify-between group">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2.5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-lg bg-[var(--bg-alt)] border border-[var(--border)] text-sm flex items-center justify-center shrink-0 shadow-2xs">
                                                {{ $pillar['icon'] ?? '🌟' }}
                                            </span>
                                            <span class="text-xs font-black text-stone-400 dark:text-stone-500 font-mono">0{{ $index + 1 }}</span>
                                        </div>
                                        @if(!empty($pillar['ministry']))
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-maroon/10 text-maroon dark:bg-mint/15 dark:text-mint border border-maroon/15 dark:border-mint/20">
                                                {{ $pillar['ministry'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <h4 class="text-base font-bold text-[var(--heading)] group-hover:text-maroon dark:group-hover:text-[var(--accent-cream)] transition-colors mb-1.5 leading-snug">
                                        {!! format_cms_text($pillar['title'] ?? '') !!}
                                    </h4>

                                    <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                        {!! format_cms_text($pillar['description'] ?? '') !!}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 7. Famoyinka Memorial Children's Library Showcase --}}
    @php
        $librarySec = $sections['famoyinka_library'] ?? null;
        $defaultLibPhotos = [
            ['image' => 'front/images/gallery/optimized/famoyinka-01.webp', 'label' => 'Free Book Loans', 'alt' => "Student selecting books at Famoyinka Memorial Children's Library"],
            ['image' => 'front/images/gallery/optimized/famoyinka-03.webp', 'label' => 'Creative Arts', 'alt' => 'Creative watercolor art workshop'],
            ['image' => 'front/images/gallery/optimized/famoyinka-02.webp', 'label' => 'Leadership Games', 'alt' => 'Leadership teamwork games and sports'],
            ['image' => 'front/images/gallery/optimized/famoyinka-04.webp', 'label' => 'Ikogosi Excursion', 'alt' => 'Ikogosi Warm Springs educational excursion'],
        ];
        $libPhotos = $librarySec?->meta_data['photos'] ?? $defaultLibPhotos;
        $p1 = $libPhotos[0] ?? $defaultLibPhotos[0];
        $p2 = $libPhotos[1] ?? $defaultLibPhotos[1];
        $p3 = $libPhotos[2] ?? $defaultLibPhotos[2];
        $p4 = $libPhotos[3] ?? $defaultLibPhotos[3];
    @endphp
    @if ($librarySec?->is_active ?? true)
    <section class="py-20 lg:py-28 bg-[var(--surface-alt)] transition-colors relative overflow-hidden border-y border-[var(--border)]">
        {{-- Subtle background pattern --}}
        <div class="absolute inset-0 z-0 opacity-15 pointer-events-none">
            <div class="absolute inset-0 bg-[radial-gradient(#800000_1px,transparent_1px)] [background-size:28px_28px]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                {{-- Left Side: Text and 4 Pillars --}}
                <div class="lg:col-span-7 space-y-8">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon dark:bg-mint/15 dark:text-mint border border-maroon/20 dark:border-mint/30">
                            <span>📚 {!! format_cms_text($librarySec?->subheadline ?: 'Permanent Literacy Sanctuary') !!}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[var(--heading)] tracking-tight leading-tight mt-3">
                            {!! format_cms_text($librarySec?->headline ?: "Famoyinka Memorial Children's Library") !!}
                        </h2>
                        <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed mt-4">
                            {!! format_cms_text($librarySec?->body_text ?: 'A vibrant community sanctuary in Ijero-Ekiti founded to nurture inquisitive minds, ignite a lifelong love for reading, and inspire creative expression in every child.') !!}
                        </p>
                    </div>

                    {{-- 4 Core Pillars Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Pillar 1 --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs hover:border-maroon/40 dark:hover:border-mint/40 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint flex items-center justify-center text-lg mb-3">
                                📖
                            </div>
                            <h3 class="text-base font-bold text-[var(--heading)]">Free Book Loans</h3>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] mt-1 leading-relaxed">
                                Curated fiction, non-fiction, science, and cultural literature accessible to every child and family without subscription fees.
                            </p>
                        </div>

                        {{-- Pillar 2 --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs hover:border-maroon/40 dark:hover:border-mint/40 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:bg-amber-400/10 dark:text-amber-400 flex items-center justify-center text-lg mb-3">
                                🎨
                            </div>
                            <h3 class="text-base font-bold text-[var(--heading)]">Creative Arts & Crafts</h3>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] mt-1 leading-relaxed">
                                Guided watercolor painting, model crafts, and expressive creative sessions building cognitive agility and artistic confidence.
                            </p>
                        </div>

                        {{-- Pillar 3 --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs hover:border-maroon/40 dark:hover:border-mint/40 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400 flex items-center justify-center text-lg mb-3">
                                🏆
                            </div>
                            <h3 class="text-base font-bold text-[var(--heading)]">Leadership & Teamwork</h3>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] mt-1 leading-relaxed">
                                Transformational leadership activities, sportsmanship challenges, and problem-solving circles anchored on our DRNICER values.
                            </p>
                        </div>

                        {{-- Pillar 4 --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs hover:border-maroon/40 dark:hover:border-mint/40 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:bg-sky-400/10 dark:text-sky-400 flex items-center justify-center text-lg mb-3">
                                🌿
                            </div>
                            <h3 class="text-base font-bold text-[var(--heading)]">Educational Excursions</h3>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] mt-1 leading-relaxed">
                                Horizon-broadening expeditions including trips to the Ikogosi Warm Springs, expanding children's environmental perspective.
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ url('/programmes/famoyinka-memorial-library') }}" 
                           class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            {!! format_cms_text($librarySec?->button_text ?: 'Explore Library Programme') !!} &rarr;
                        </a>
                        <a href="{{ url('/gallery?album=famoyinka-library') }}" 
                           class="inline-flex items-center justify-center px-6 py-3.5 rounded-full text-sm font-semibold border border-[var(--border)] bg-[var(--surface)] text-[var(--heading)] hover:border-maroon hover:text-maroon dark:hover:border-mint dark:hover:text-mint transition-all duration-200">
                            Browse Photo Gallery
                        </a>
                    </div>
                </div>

                {{-- Right Side: Action Photography Mosaic --}}
                <div class="lg:col-span-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <div class="space-y-3 sm:space-y-4">
                            <div class="rounded-2xl sm:rounded-3xl overflow-hidden border border-[var(--border)] shadow-md aspect-4/5 group relative">
                                <img src="{{ str_starts_with($p1['image'] ?? '', 'http') ? $p1['image'] : asset($p1['image'] ?? 'front/images/gallery/optimized/famoyinka-01.webp') }}" 
                                     alt="{{ $p1['alt'] ?? 'Student selecting books at Famoyinka Memorial Children\'s Library' }}" 
                                     loading="lazy" decoding="async" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent flex items-end p-3.5">
                                    <span class="text-xs font-semibold text-white drop-shadow-xs">{{ $p1['label'] ?? 'Free Book Loans' }}</span>
                                </div>
                            </div>
                            <div class="rounded-2xl sm:rounded-3xl overflow-hidden border border-[var(--border)] shadow-md aspect-square group relative">
                                <img src="{{ str_starts_with($p2['image'] ?? '', 'http') ? $p2['image'] : asset($p2['image'] ?? 'front/images/gallery/optimized/famoyinka-03.webp') }}" 
                                     alt="{{ $p2['alt'] ?? 'Creative watercolor art workshop' }}" 
                                     loading="lazy" decoding="async" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent flex items-end p-3.5">
                                    <span class="text-xs font-semibold text-white drop-shadow-xs">{{ $p2['label'] ?? 'Creative Arts' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-4 pt-6 sm:pt-8">
                            <div class="rounded-2xl sm:rounded-3xl overflow-hidden border border-[var(--border)] shadow-md aspect-square group relative">
                                <img src="{{ str_starts_with($p3['image'] ?? '', 'http') ? $p3['image'] : asset($p3['image'] ?? 'front/images/gallery/optimized/famoyinka-02.webp') }}" 
                                     alt="{{ $p3['alt'] ?? 'Leadership teamwork games and sports' }}" 
                                     loading="lazy" decoding="async" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent flex items-end p-3.5">
                                    <span class="text-xs font-semibold text-white drop-shadow-xs">{{ $p3['label'] ?? 'Leadership Games' }}</span>
                                </div>
                            </div>
                            <div class="rounded-2xl sm:rounded-3xl overflow-hidden border border-[var(--border)] shadow-md aspect-4/5 group relative">
                                <img src="{{ str_starts_with($p4['image'] ?? '', 'http') ? $p4['image'] : asset($p4['image'] ?? 'front/images/gallery/optimized/famoyinka-04.webp') }}" 
                                     alt="{{ $p4['alt'] ?? 'Ikogosi Warm Springs educational excursion' }}" 
                                     loading="lazy" decoding="async" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent flex items-end p-3.5">
                                    <span class="text-xs font-semibold text-white drop-shadow-xs">{{ $p4['label'] ?? 'Ikogosi Excursion' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 8. Featured Project Card --}}
    @php
        $featSec = $sections['featured_project'] ?? null;
    @endphp
    @if ($featSec?->is_active ?? true)
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
                            {!! format_cms_text($featSec?->subheadline ?: 'Featured Project') !!}
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] leading-snug mt-3">
                            {!! format_cms_text($featSec?->headline ?: 'Digital Infrastructure & Literacy in Ijero-Ekiti') !!}
                        </h3>
                    </div>

                    <p class="text-[var(--text)] leading-relaxed text-base">
                        {!! format_cms_text($featSec?->body_text ?: 'Establishing a well-equipped computer laboratory and a resourceful library at Dzuels Foundation, providing children with access to practical computer training, coding skills, reading materials, and a supportive learning environment. To date, over 500 students have benefited from these learning opportunities.') !!}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ $featSec?->button_url ?: url('/programmes/ijero-digital-literacy') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-semibold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md">
                            {!! format_cms_text($featSec?->button_text ?: 'Learn More About Project') !!}
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
    @endif

    {{-- 8. Flagship Event Callout / Teaser (Dynamic Status) --}}
    @php
        $flagship = $flagshipEvent ?? null;
        $isConcluded = $flagship ? $flagship->is_past : (now()->parse('2026-10-17 08:00:00')->isPast());
        $isToday = $flagship ? $flagship->is_today : (now()->toDateString() === '2026-10-17');
        $dateFormatted = $flagship && $flagship->event_date ? $flagship->event_date->format('M j, Y') : 'Oct 17, 2026';
        $eventSlug = $flagship?->slug ?? 'ijero-tech-day-2026';
        $eventTitle = $flagship?->title ?? 'Ijero Tech-Day 2026 (ITD 2026)';
        $eventTheme = $flagship?->theme ?? 'Learning Today, Creating Tomorrow';
        $eventSummary = $flagship?->summary ?: 'Empowering 300 children with practical AI tools across 10 rotating batches of 30, inside interactive labs and garden booths, plus 22 cohort graduate certificates at AAoJ Memorial Learning Center.';
    @endphp
    <section class="py-16 sm:py-20 bg-[var(--bg)] transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl p-8 sm:p-12 bg-linear-to-r from-maroon to-crimson text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($isToday)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30">
                                Happening Today • {{ $dateFormatted }}
                            </span>
                        @elseif ($isConcluded)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30">
                                Recent Flagship Event • {{ $dateFormatted }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-white border border-white/30">
                                Highlights
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30">
                                Upcoming Flagship Event • {{ $dateFormatted }}
                            </span>
                        @endif
                        <span class="text-xs font-semibold text-[var(--accent-cream)] italic">
                            "{{ $eventTheme }}"
                        </span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                        {{ $eventTitle }}
                    </h3>
                    <p class="text-white/90 text-sm sm:text-base max-w-xl leading-relaxed">
                        {{ $eventSummary }}
                    </p>
                </div>
                <div class="shrink-0 flex flex-col sm:flex-row items-center gap-3">
                    @if ($isConcluded)
                        <a href="{{ url('/events/' . $eventSlug) }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-center">
                            Explore Event Highlights &rarr;
                        </a>
                        <a href="{{ url('/events/' . $eventSlug . '#photo-gallery') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 rounded-full text-sm font-bold bg-white/10 text-white hover:bg-white/20 border border-white/30 transition-all duration-200 text-center">
                            View Photo Gallery &rarr;
                        </a>
                    @else
                        <a href="{{ url('/events/' . $eventSlug) }}" 
                           class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-sm font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Register to Attend &rarr;
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- 10. Partners Strip --}}
    @if (isset($partners) && $partners->isNotEmpty())
    <section class="py-16 sm:py-20 bg-[var(--bg-alt)] text-center transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                Our Valued Partners
            </span>
            <h3 class="text-2xl sm:text-3xl font-bold text-[var(--heading)] mt-2 mb-10">
                Collaborating for greater impact
            </h3>

            <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-14 max-w-5xl mx-auto">
                @foreach ($partners as $partner)
                    @if ($partner->website_url)
                        <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-block transition-transform hover:scale-105" title="{{ $partner->name }}">
                            <img src="{{ $partner->logo_url }}" 
                                 alt="{{ $partner->name }}" 
                                 loading="lazy"
                                 decoding="async"
                                 class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm hover:grayscale-0 transition-all duration-300 opacity-80 hover:opacity-100" />
                        </a>
                    @else
                        <img src="{{ $partner->logo_url }}" 
                             alt="{{ $partner->name }}" 
                             loading="lazy"
                             decoding="async"
                             class="h-12 sm:h-14 w-auto object-contain rounded-lg p-2 bg-white dark:bg-white/90 shadow-sm  hover:grayscale-0 hover:scale-105 transition-all duration-300 opacity-80 hover:opacity-100" />
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- 11. Final Call to Action Banner --}}
    @php
        $ctaSec = $sections['cta'] ?? null;
    @endphp
    @if ($ctaSec?->is_active ?? true)
    <section class="py-20 lg:py-28 text-white text-center relative overflow-hidden bg-radial from-crimson/40 via-maroon to-[#2B0203]">
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">
                {!! format_cms_text($ctaSec?->headline ?: 'You too can make a difference') !!}
            </h2>
            <p class="text-base sm:text-lg text-[var(--accent-cream)] max-w-2xl mx-auto leading-relaxed">
                {!! format_cms_text($ctaSec?->body_text ?: 'Whether through individual, family or corporate sponsorship, volunteer mentorship, financial donations or legacy giving, your partnership empowers the next generation.') !!}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ $ctaSec?->button_url ?: url('/donate') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                    {!! format_cms_text($ctaSec?->button_text ?: 'Make a Donation') !!}
                </a>
                <a href="{{ url('/get-involved') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                    Join as a Volunteer
                </a>
            </div>
        </div>
    </section>
    @endif
</x-layout>
