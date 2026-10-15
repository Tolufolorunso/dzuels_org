<x-layout
    :canonical="url('/about')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'About Us', 'url' => url('/about')]
    ]">
    <x-slot:title>About Us | Dzuels Educational Foundation - A Decade of Transforming Lives</x-slot:title>
    <x-slot:description>Learn about Dzuels Educational Foundation, established in 2015 to alleviate poverty through quality education, youth literacy interventions, and digital skills acquisition in Nigeria.</x-slot:description>

    {{-- 1. Hero & Breadcrumb Banner --}}
    @php
        $heroSec = $sections['hero'] ?? null;
        $storySec = $sections['story'] ?? null;
        $heroBgImage = $heroSec?->image_url ?: asset('front/images/gallery/optimized/general-01.webp');
    @endphp
    <section class="relative bg-maroon text-white py-16 sm:py-24 overflow-hidden">
        {{-- Contextual Background Imagery --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ $heroBgImage }}" alt="" class="w-full h-full object-cover object-center transform scale-105" aria-hidden="true">
        </div>
        {{-- Rich Brand Color Gradient Overlay --}}
        <div class="absolute inset-0 z-0 pointer-events-none" 
             style="background: linear-gradient(135deg, rgba(43, 2, 3, 0.88) 0%, rgba(106, 4, 6, 0.78) 50%, rgba(180, 7, 10, 0.62) 100%);"></div>
        <div class="absolute inset-0 z-0 pointer-events-none" 
             style="background: linear-gradient(to bottom, rgba(43, 2, 3, 0.45) 0%, transparent 45%, rgba(43, 2, 3, 0.85) 100%);"></div>
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
            <div class="absolute inset-0 bg-[radial-gradient(#F9DEC9_1px,transparent_1px)] [background-size:24px_24px]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center gap-2 text-sm text-cream/80 mb-6" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1.5 focus:outline-none focus:underline">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <span class="text-cream/50" aria-hidden="true">/</span>
                <span class="text-white font-semibold" aria-current="page">About Us</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                    {{ $heroSec?->subheadline ?: 'About Dzuels Educational Foundation' }}
                </span>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $heroSec?->headline ?: 'Transforming Lives Page by Page' }}
                </h1>

                <p class="text-lg sm:text-xl text-white/90 leading-relaxed max-w-2xl pt-2">
                    {{ $heroSec?->body_text ?: 'A decade of dedicated service alleviating poverty through quality education, youth literacy interventions, and digital empowerment in Nigeria.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- 2. Our Story & 10-Year Journey --}}
    <section class="py-20 bg-[var(--bg)] transition-colors overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                {{-- Story Text Column --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-mint">
                            {{ $storySec?->subheadline ?: 'Our Heritage & Purpose' }}
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                            {{ $storySec?->headline ?: 'A Decade of Dedicated Service (Since 2015)' }}
                        </h2>
                    </div>

                    @if ($storySec?->body_text)
                        <div class="text-base sm:text-lg text-[var(--text)] leading-relaxed space-y-4">
                            {!! nl2br(e($storySec->body_text)) !!}
                        </div>
                    @else
                        <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed">
                            <strong class="font-semibold text-[var(--heading)]">Dzuels Educational Foundation</strong> is a registered non-profit charity founded on <span class="font-semibold text-crimson dark:text-mint">22nd June 2015</span>. Established with an unwavering commitment to child welfare, we exist to provide comprehensive educational support, life skills, and digital literacy for indigent Nigerian children and youth.
                        </p>

                        <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed">
                            Over the last ten years, our programs have operated directly within public and community schools in Ekiti State and beyond. We believe every child deserves the tools, dignity, and encouragement to pursue knowledge without the burden of unpaid tuition fees or missing textbooks.
                        </p>

                        <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed">
                            A cornerstone of this commitment is the <a href="{{ url('/programmes/famoyinka-memorial-library') }}" class="font-semibold text-maroon dark:text-mint underline decoration-crimson/40 hover:text-crimson">Famoyinka Memorial Children's Library</a> in Ijero-Ekiti—a permanent reading sanctuary providing free book loans, weekend study circles, creative arts, and transformational leadership activities for local youth.
                        </p>

                        <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed">
                            Recognizing that learning does not happen in isolation, our work also reaches vulnerable households: providing nutritional assistance to indigent families through widows, creating deep awareness of social vices, and fostering transformational leadership in every classroom we touch.
                        </p>
                    @endif

                    {{-- Quick Milestone Highlights --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-[var(--border)]">
                        <div class="p-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] shadow-xs">
                            <span class="block text-2xl sm:text-3xl font-black text-crimson dark:text-mint">10+</span>
                            <span class="text-xs sm:text-sm font-medium text-[var(--text-muted)]">Years of Service</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] shadow-xs">
                            <span class="block text-2xl sm:text-3xl font-black text-crimson dark:text-mint">750+</span>
                            <span class="text-xs sm:text-sm font-medium text-[var(--text-muted)]">Students Trained</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] shadow-xs col-span-2 sm:col-span-1">
                            <span class="block text-2xl sm:text-3xl font-black text-crimson dark:text-mint">15+</span>
                            <span class="text-xs sm:text-sm font-medium text-[var(--text-muted)]">Schools Supported</span>
                        </div>
                    </div>
                </div>

                {{-- Photo Collage & 10-Year Badge Column --}}
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        {{-- Decorative background offset ring --}}
                        <div class="absolute -inset-4 bg-linear-to-tr from-maroon/20 to-mint/20 rounded-3xl blur-lg -z-10"></div>

                        {{-- Main About Image --}}
                        <div class="rounded-3xl overflow-hidden border-4 border-[var(--surface)] shadow-2xl bg-[var(--surface)]">
                            <img src="{{ $storySec?->getImageUrl() ?: asset('front/images/about_1.webp') }}" 
                                 alt="Dzuels Educational Foundation beneficiaries in Ekiti State" 
                                 class="w-full h-96 sm:h-[460px] object-cover object-center" 
                                 loading="lazy">
                        </div>

                        {{-- Floating 10-Year Impact Card --}}
                        <div class="absolute -bottom-6 left-2 sm:-left-6 bg-[var(--surface)] border border-[var(--border)] p-4 sm:p-5 rounded-2xl shadow-xl flex items-center gap-4 max-w-[calc(100%-1rem)] sm:max-w-xs transition-colors">
                            <div class="w-12 h-12 rounded-xl bg-maroon text-cream flex items-center justify-center font-bold shrink-0 shadow-md">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-mint">Milestone</p>
                                <p class="text-sm font-bold text-[var(--heading)]">10+ Years of Direct Community Impact</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Our Holistic Child Approach (6 Integrated Pillars) --}}
    <section class="py-20 bg-[var(--surface-alt)] border-y border-[var(--border)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint">
                    Total Well-Being of the Child
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Our Integrated Intervention Approach
                </h2>
                <p class="text-base sm:text-lg text-[var(--text-muted)] leading-relaxed">
                    We believe sustainable education requires a whole-child model: combining academic sponsorship, classroom infrastructure, moral mentorship, and household nutritional stability.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Pillar 1: Tuition Support --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-8 shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 rounded-2xl bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Tuition & School Fees Support</h3>
                    <p class="text-sm text-[var(--text)] leading-relaxed">
                        Paying school fees and registration costs directly for indigent students in public and non-profit schools to prevent dropout and keep vulnerable children in class.
                    </p>
                </div>

                {{-- Pillar 2: Educational Materials --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-8 shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 rounded-2xl bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Educational Materials & Uniforms</h3>
                    <p class="text-sm text-[var(--text)] leading-relaxed">
                        Providing textbooks, exercise books, writing materials, school bags, and uniforms so that students can attend school with confidence, dignity, and pride.
                    </p>
                </div>

                {{-- Pillar 3: Equipping Non-Profit Schools --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-8 shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 rounded-2xl bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Equipping Non-Profit Schools & Labs</h3>
                    <p class="text-sm text-[var(--text)] leading-relaxed">
                        Setting up digital ICT facilities, supplying desktop computers, and upgrading library resources in community schools to bridge the digital divide.
                    </p>
                </div>

                {{-- Pillar 4: Mentorship & Leadership --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-8 shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 rounded-2xl bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Mentorship & Leadership Training</h3>
                    <p class="text-sm text-[var(--text)] leading-relaxed">
                        Instilling the DRNICER values (Discipline, Responsibility, No-Excuse, Integrity, Compassion, Excellence, Respect) through structured mentorship and leadership camps.
                    </p>
                </div>

                {{-- Pillar 5: Family Nutrition & Widow Support --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-8 shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 rounded-2xl bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Family Nutrition & Widow Welfare</h3>
                    <p class="text-sm text-[var(--text)] leading-relaxed">
                        Supporting struggling households and vulnerable widows with food relief packages, micro-support, and welfare assistance so children are nourished and ready to learn.
                    </p>
                </div>

                {{-- Pillar 6: Health & Social Awareness --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-8 shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 rounded-2xl bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Health Services & Social Awareness</h3>
                    <p class="text-sm text-[var(--text)] leading-relaxed">
                        Conducting health screenings, promoting hygiene, and raising awareness regarding social vices, substance abuse, and cyber-safety to protect youth development.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3b. Permanent Community Sanctuary Showcase: Famoyinka Memorial Children's Library --}}
    <section class="py-16 sm:py-20 bg-[var(--surface)] transition-colors border-b border-[var(--border)] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[var(--surface-alt)] border border-[var(--border)] rounded-3xl p-8 sm:p-12 lg:p-14 shadow-lg grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon dark:bg-mint/15 dark:text-mint border border-maroon/20 dark:border-mint/30">
                        <span>🏛️ Permanent Community Hub</span>
                    </div>
                    
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                        Famoyinka Memorial Children's Library
                    </h2>

                    <p class="text-base sm:text-lg text-[var(--text)] leading-relaxed">
                        Established as a permanent physical sanctuary in Ijero-Ekiti, the Famoyinka Memorial Children's Library brings our 10-year holistic child development vision to life every day. It provides children and families with open access to rich literature, quiet study workstations, and weekend mentorship circles.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span class="text-sm font-medium text-[var(--text)]">Free fiction, science & culture book loans</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span class="text-sm font-medium text-[var(--text)]">Weekend study circles & digital lab access</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span class="text-sm font-medium text-[var(--text)]">Creative arts, crafts & watercolor workshops</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span class="text-sm font-medium text-[var(--text)]">Leadership games & Ikogosi nature trips</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <a href="{{ url('/programmes/famoyinka-memorial-library') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md">
                            <span>Explore Library Programme</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="{{ url('/gallery?album=famoyinka-library') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-semibold border border-[var(--border)] bg-[var(--surface)] text-[var(--heading)] hover:border-maroon transition-all">
                            <span>View Photo Gallery</span>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden border border-[var(--border)] shadow-xl aspect-4/3 sm:aspect-16/10 lg:aspect-4/3 group">
                        <img src="{{ asset('front/images/gallery/optimized/famoyinka-01.webp') }}" 
                             alt="Children reading at Famoyinka Memorial Children's Library" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                             loading="lazy">
                        <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--accent-cream)]">Ijero-Ekiti, Nigeria</span>
                            <p class="text-white font-bold text-lg mt-0.5">A Living Sanctuary for Young Readers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Board of Trustees --}}
    <section id="trustees" class="py-20 bg-[var(--bg)] transition-colors scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint">
                    Stewardship & Governance
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Board of Trustees
                </h2>
                <p class="text-base sm:text-lg text-[var(--text-muted)] leading-relaxed">
                    Our governing trustees provide strategic guidance, fiduciary integrity, and long-term vision to uphold our educational mission.
                </p>
            </div>

            @if($trustees->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($trustees as $trustee)
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
                            {{-- Photo Frame --}}
                            <div class="aspect-4/5 bg-[var(--surface-alt)] overflow-hidden relative">
                                <img src="{{ $trustee->photo_url }}" 
                                     alt="{{ $trustee->name }}" 
                                     class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                                @if(str_contains(strtolower($trustee->role), 'founder') || str_contains(strtolower($trustee->name), 'bolaji'))
                                    <div class="absolute top-3 right-3 bg-maroon text-cream text-[11px] font-bold px-2.5 py-1 rounded-full shadow-md">
                                        Founder
                                    </div>
                                @endif
                            </div>

                            {{-- Member Info --}}
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--heading)] group-hover:text-crimson dark:group-hover:text-mint transition-colors">
                                        {{ $trustee->name }}
                                    </h3>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-crimson dark:text-mint mt-1">
                                        {{ $trustee->role }}
                                    </p>
                                    @if($trustee->bio)
                                        <p class="text-xs text-[var(--text-muted)] mt-2 line-clamp-3 leading-relaxed">
                                            {{ $trustee->bio }}
                                        </p>
                                    @endif
                                </div>

                                @if($trustee->linkedin_url)
                                    <div class="mt-4 pt-3 border-t border-[var(--border)] flex justify-end">
                                        <a href="{{ $trustee->linkedin_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           aria-label="View {{ $trustee->name }}'s LinkedIn profile"
                                           class="text-[var(--text-muted)] hover:text-maroon dark:hover:text-mint transition-colors p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-maroon">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.738-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 px-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] max-w-xl mx-auto">
                    <p class="text-sm text-[var(--text-muted)]">Trustee profiles are currently being updated.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- 5. Management Team --}}
    <section id="management" class="py-20 bg-[var(--surface-alt)] border-y border-[var(--border)] transition-colors scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint">
                    Operational Leadership
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Management Team
                </h2>
                <p class="text-base sm:text-lg text-[var(--text-muted)] leading-relaxed">
                    Driving day-to-day operations, international partnerships, and grassroots program delivery in Nigeria and abroad.
                </p>
            </div>

            @if($management->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-center max-w-4xl mx-auto">
                    @foreach($management as $manager)
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
                            {{-- Photo Frame --}}
                            <div class="aspect-4/5 bg-[var(--surface-alt)] overflow-hidden relative">
                                <img src="{{ $manager->photo_url }}" 
                                     alt="{{ $manager->name }}" 
                                     class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                                @if(str_contains(strtolower($manager->role), 'usa'))
                                    <div class="absolute top-3 right-3 bg-[var(--accent-cream)] text-maroon text-[11px] font-bold px-2.5 py-1 rounded-full shadow-xs">
                                        USA Chapter
                                    </div>
                                @elseif(str_contains(strtolower($manager->role), 'nigeria'))
                                    <div class="absolute top-3 right-3 bg-mint text-[#140607] text-[11px] font-bold px-2.5 py-1 rounded-full shadow-xs">
                                        Nigeria Chapter
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-6 text-center flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--heading)] group-hover:text-crimson dark:group-hover:text-mint transition-colors">
                                        {{ $manager->name }}
                                    </h3>
                                    <p class="text-sm font-semibold text-crimson dark:text-mint mt-1">
                                        {{ $manager->role }}
                                    </p>
                                    @if($manager->bio)
                                        <p class="text-xs text-[var(--text-muted)] mt-2 line-clamp-3 leading-relaxed">
                                            {{ $manager->bio }}
                                        </p>
                                    @endif
                                </div>

                                @if($manager->linkedin_url)
                                    <div class="mt-4 pt-3 border-t border-[var(--border)] flex justify-center">
                                        <a href="{{ $manager->linkedin_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           aria-label="View {{ $manager->name }}'s LinkedIn profile"
                                           class="text-[var(--text-muted)] hover:text-maroon dark:hover:text-mint transition-colors p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-maroon">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.738-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 px-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] max-w-xl mx-auto">
                    <p class="text-sm text-[var(--text-muted)]">Management profiles are currently being updated.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- 6. Advisory Board --}}
    <section class="py-20 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint">
                    Domain Expertise
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Advisory Board
                </h2>
                <p class="text-base sm:text-lg text-[var(--text-muted)] leading-relaxed">
                    Industry leaders, educators, medical practitioners, and technology innovators guiding our programmatic growth.
                </p>
            </div>

            @if($advisors->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($advisors as $advisor)
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-200 hover:-translate-y-1 flex flex-col justify-between group">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <h3 class="text-lg font-bold text-[var(--heading)] group-hover:text-crimson dark:group-hover:text-mint transition-colors">
                                        {{ $advisor->name }}
                                    </h3>
                                    @if($advisor->linkedin_url)
                                        <a href="{{ $advisor->linkedin_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           aria-label="View {{ $advisor->name }}'s LinkedIn profile"
                                           class="text-[var(--text-muted)] hover:text-maroon dark:hover:text-mint transition-colors shrink-0 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-maroon">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.738-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                @if($advisor->field)
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-maroon/10 text-maroon dark:bg-mint/15 dark:text-mint mb-3">
                                        {{ $advisor->field }}
                                    </span>
                                @endif

                                @if($advisor->bio)
                                    <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                        {{ $advisor->bio }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 px-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] max-w-xl mx-auto">
                    <p class="text-sm text-[var(--text-muted)]">Advisory board roster is currently being updated.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- 7. Partners in Progress Showcase --}}
    <section class="py-20 bg-[var(--surface-alt)] border-t border-[var(--border)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-14">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint">
                    Allies & Funders
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Partners in Progress
                </h2>
                <p class="text-base sm:text-lg text-[var(--text-muted)] leading-relaxed">
                    Collaborating with trusted foundations, legal practices, and international development organizations to scale our impact.
                </p>
            </div>

            @if($partners->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-8 items-center justify-center">
                    @foreach($partners as $partner)
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 sm:p-8 flex items-center justify-center shadow-xs hover:shadow-md transition-all duration-200 hover:-translate-y-1 h-32 group">
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="flex items-center justify-center w-full h-full focus:outline-none focus:ring-2 focus:ring-maroon rounded-lg"
                                   title="{{ $partner->name }}">
                                    <img src="{{ $partner->logo_url }}" 
                                         alt="{{ $partner->name }}" 
                                         class="max-h-16 max-w-[85%] object-contain group-hover:grayscale-0 transition-all duration-300"
                                         loading="lazy">
                                </a>
                            @else
                                <img src="{{ $partner->logo_url }}" 
                                     alt="{{ $partner->name }}" 
                                     class="max-h-16 max-w-[85%] object-contain group-hover:grayscale-0 transition-all duration-300"
                                     loading="lazy">
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-sm text-[var(--text-muted)]">Interested in partnering with Dzuels Educational Foundation?</p>
                    <a href="{{ url('/get-involved') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold uppercase tracking-wider text-maroon dark:text-[var(--accent-cream)] hover:underline">
                        <span>Join Our Institutional Partners</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- 8. Final Call to Action (CTA) --}}
    <section class="relative bg-maroon text-white py-20 overflow-hidden">
        {{-- Subtle background circles --}}
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-crimson/30 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-cream/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                Make a Difference Today
            </span>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                Join Hands with Us to <span class="script-accent text-[var(--accent-cream)] text-4xl sm:text-5xl lg:text-6xl font-normal block sm:inline">Transform More Lives</span>
            </h2>

            <p class="text-base sm:text-lg text-white/90 max-w-2xl mx-auto leading-relaxed">
                Whether through volunteer teaching, mentoring, sponsoring an indigent pupil's fees, or corporate partnership, your contribution builds a brighter tomorrow.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ url('/get-involved') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                    Become a Volunteer
                </a>
                <a href="{{ url('/donate') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                    Support Our Mission (Donate)
                </a>
            </div>
        </div>
    </section>
</x-layout>
