<x-layout
    :canonical="url('/programmes')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Programmes', 'url' => url('/programmes')]
    ]">
    <x-slot:title>Programmes | Dzuels Educational Foundation - Educational & Literacy Initiatives</x-slot:title>
    <x-slot:description>Discover Dzuels Educational Foundation programmes: Digital Infrastructure in Ijero-Ekiti, Innovation & Tech Day, Reading Competitions, and Spelling Bee Contests.</x-slot:description>

    {{-- 1. Hero & Breadcrumb Banner --}}
    @php
        $heroSec = $sections['hero'] ?? null;
        $heroBgImage = $heroSec?->image_url ?: asset('front/images/gallery/optimized/itd-2025-05.webp');
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
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-sm text-cream/80 mb-6" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1.5 focus:outline-none focus:underline">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <span class="text-cream/50" aria-hidden="true">/</span>
                <span class="text-white font-semibold" aria-current="page">Programmes</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                    {{ $heroSec?->subheadline ?: 'Impact In Action' }}
                </span>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $heroSec?->headline ?: 'Our Impact Programmes' }}
                </h1>

                <p class="text-lg sm:text-xl text-white/90 leading-relaxed max-w-2xl pt-2">
                    {{ $heroSec?->body_text ?: 'Empowering Nigerian children, teachers, and schools through grassroots literacy campaigns, digital labs, and STEM innovation showcases.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- 2. Featured Initiative Spotlight --}}
    @if ($featured)
        <section class="py-12 -mt-10 sm:-mt-14 relative z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl overflow-hidden shadow-xl grid grid-cols-1 lg:grid-cols-12 transition-colors">
                    {{-- Cover Photo --}}
                    <div class="lg:col-span-6 relative min-h-[300px] lg:min-h-full overflow-hidden group">
                        <img src="{{ $featured->cover_image_url }}" 
                             alt="{{ $featured->title }}" 
                             class="w-full h-full object-cover object-center absolute inset-0 group-hover:scale-105 transition-transform duration-500">
                    </div>

                    {{-- Content details --}}
                    <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-center space-y-6">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 dark:bg-amber-900/30 dark:text-amber-300">
                                    ★ Featured Initiative
                                </span>
                                @if ($featured->partner_name)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-maroon/10 text-maroon dark:bg-mint/15 dark:text-mint">
                                        Partner: {{ $featured->partner_name }}
                                    </span>
                                @endif
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] leading-snug">
                                {{ $featured->title }}
                            </h2>
                        </div>

                        <p class="text-[var(--text)] leading-relaxed text-base">
                            {{ $featured->summary }}
                        </p>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="{{ route('programmes.show', $featured->slug) }}" 
                               class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-semibold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:-translate-y-0.5 cursor-pointer">
                                Learn More About Project
                            </a>
                            <a href="{{ url('/get-involved') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-semibold border border-[var(--border)] text-[var(--text-strong)] hover:border-maroon transition-all duration-200 cursor-pointer">
                                Partner or Volunteer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 3. Programmes Catalog Grid --}}
    <section class="py-16 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-mint">
                        Intervention Catalog
                    </span>
                    <h2 class="text-3xl font-extrabold text-[var(--heading)] tracking-tight mt-1">
                        All Foundation Initiatives
                    </h2>
                </div>
                <p class="text-sm text-[var(--text-muted)] max-w-md">
                    Explore our continuous projects supporting classrooms, libraries, digital hubs, and young scholars.
                </p>
            </div>

            @if ($programmes->isEmpty())
                <div class="text-center py-16 px-4 bg-[var(--surface)] border border-[var(--border)] rounded-3xl max-w-xl mx-auto">
                    <p class="text-sm text-[var(--text-muted)] font-medium">Programme details are currently being updated.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 sm:gap-10">
                    @foreach ($programmes as $programme)
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
                            {{-- Cover Card Image with 5% zoom on hover --}}
                            <div class="aspect-16/10 bg-[var(--surface-alt)] overflow-hidden relative">
                                <img src="{{ $programme->cover_image_url }}" 
                                     alt="{{ $programme->title }}" 
                                     class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">
                                @if ($programme->is_featured)
                                    <div class="absolute top-4 left-4 bg-maroon text-cream text-[11px] font-bold px-3 py-1 rounded-full shadow-md">
                                        ★ Featured
                                    </div>
                                @endif
                                @if ($programme->partner_name)
                                    <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-xs text-white text-[11px] font-medium px-3 py-1 rounded-full">
                                        🤝 {{ $programme->partner_name }}
                                    </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6 sm:p-8 flex-grow flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <h3 class="text-xl sm:text-2xl font-bold text-[var(--heading)] group-hover:text-crimson dark:group-hover:text-mint transition-colors">
                                        <a href="{{ route('programmes.show', $programme->slug) }}" class="focus:outline-none focus:underline">
                                            {{ $programme->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-[var(--text)] leading-relaxed line-clamp-3">
                                        {{ $programme->summary }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                                    <a href="{{ route('programmes.show', $programme->slug) }}" 
                                       class="inline-flex items-center gap-2 text-sm font-bold text-maroon dark:text-mint group-hover:translate-x-1 transition-transform">
                                        <span>View Programme</span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                    <span class="text-xs text-[var(--text-muted)] font-mono">
                                        /programmes/{{ $programme->slug }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- 4. Call to Action --}}
    <section class="relative bg-maroon text-white py-20 overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-crimson/30 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-cream/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                Empower a Classroom
            </span>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                Help Us Expand These <span class="script-accent text-[var(--accent-cream)] text-4xl sm:text-5xl lg:text-6xl font-normal block sm:inline">Transformative Initiatives</span>
            </h2>

            <p class="text-base sm:text-lg text-white/90 max-w-2xl mx-auto leading-relaxed">
                Your partnership provides desktop computers, school books, scholarship grants, and expert mentorship to children who need it most.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ url('/donate') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                    Sponsor an Initiative
                </a>
                <a href="{{ url('/get-involved') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                    Volunteer as a Facilitator
                </a>
            </div>
        </div>
    </section>
</x-layout>
