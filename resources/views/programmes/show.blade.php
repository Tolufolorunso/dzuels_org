<x-layout
    :canonical="route('programmes.show', $programme->slug)"
    :og-image="$programme->featured_image_url ?? asset('front/images/cause.png')"
    og-type="article"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Programmes', 'url' => route('programmes.index')],
        ['name' => $programme->title, 'url' => route('programmes.show', $programme->slug)]
    ]">
    <x-slot:title>{{ $programme->seo_title ?: $programme->title . ' | Dzuels Educational Foundation' }}</x-slot:title>
    <x-slot:description>{{ $programme->seo_description ?: $programme->summary }}</x-slot:description>

    {{-- 1. Header Banner & Breadcrumbs --}}
    <section class="relative bg-maroon text-white py-14 sm:py-20 overflow-hidden">
        {{-- Background Pattern & Linear Gradient --}}
        <div class="absolute inset-0 z-0 opacity-20">
            <div class="absolute inset-0 bg-[radial-gradient(#F9DEC9_1px,transparent_1px)] [background-size:24px_24px]"></div>
        </div>
        <div class="absolute inset-0 bg-linear-to-b from-maroon/90 via-maroon to-[#2B0203]"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-sm text-cream/80 mb-6 flex-wrap" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1.5 focus:outline-none focus:underline">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <span class="text-cream/50" aria-hidden="true">/</span>
                <a href="{{ route('programmes.index') }}" class="hover:text-white transition-colors focus:outline-none focus:underline">
                    Programmes
                </a>
                <span class="text-cream/50" aria-hidden="true">/</span>
                <span class="text-white font-semibold truncate max-w-xs sm:max-w-md" aria-current="page">{{ $programme->title }}</span>
            </nav>

            <div class="max-w-4xl space-y-4">
                <div class="flex flex-wrap items-center gap-2.5">
                    @if ($programme->is_featured)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-400/20 text-amber-200 border border-amber-300/30">
                            ★ Featured Project
                        </span>
                    @endif
                    @if ($programme->partner_name)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-cream border border-white/20">
                            🤝 Partner: {{ $programme->partner_name }}
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $programme->title }}
                </h1>

                <p class="text-base sm:text-xl text-white/90 leading-relaxed pt-2 max-w-3xl">
                    {{ $programme->summary }}
                </p>
            </div>
        </div>
    </section>

    {{-- 2. Main Content & Sidebar Layout --}}
    <section class="py-16 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                
                {{-- Left Column: Cover Image & Narrative --}}
                <div class="lg:col-span-8 space-y-10">
                    {{-- Cover Photo Frame --}}
                    <div class="rounded-3xl overflow-hidden border border-[var(--border)] shadow-xl bg-[var(--surface)]">
                        <img src="{{ $programme->cover_image_url }}" 
                             alt="{{ $programme->title }}" 
                             class="w-full h-72 sm:h-96 md:h-[450px] object-cover object-center">
                    </div>

                    {{-- Narrative Article Body --}}
                    <div class="space-y-6 text-base sm:text-lg text-[var(--text)] leading-relaxed bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-10 shadow-xs">
                        <h2 class="text-2xl font-bold text-[var(--heading)] pb-4 border-b border-[var(--border)]">
                            About this Initiative
                        </h2>

                        @foreach (explode("\n\n", $programme->body) as $paragraph)
                            @if(trim($paragraph))
                                <p class="leading-relaxed">
                                    {{ trim($paragraph) }}
                                </p>
                            @endif
                        @endforeach
                    </div>

                    {{-- Community Callout Band --}}
                    <div class="p-8 rounded-3xl bg-linear-to-r from-maroon/10 to-mint/10 border border-maroon/20 dark:border-mint/20 space-y-4">
                        <h3 class="text-xl font-bold text-[var(--heading)]">
                            Why this programme matters to our beneficiaries
                        </h3>
                        <p class="text-sm sm:text-base text-[var(--text)] leading-relaxed">
                            Every milestone achieved through this programme directly benefits pupils, families, and teachers in rural Nigeria. We work alongside local schools to ensure sustainable long-term adoption, digital continuity, and community ownership.
                        </p>
                    </div>
                </div>

                {{-- Right Column: Project Sidebar --}}
                <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-28">
                    
                    {{-- Quick Facts Card --}}
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                        <h3 class="text-lg font-bold text-[var(--heading)] pb-3 border-b border-[var(--border)]">
                            Project Quick Facts
                        </h3>

                        <dl class="space-y-4 text-sm">
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Target Region</dt>
                                <dd class="font-semibold text-[var(--heading)] mt-0.5">Ekiti State, Nigeria</dd>
                            </div>

                            @if ($programme->partner_name)
                                <div>
                                    <dt class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Key Partner</dt>
                                    <dd class="font-semibold text-[var(--heading)] mt-0.5">{{ $programme->partner_name }}</dd>
                                </div>
                            @endif

                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Beneficiaries</dt>
                                <dd class="font-semibold text-[var(--heading)] mt-0.5">Public & Community School Students, Teachers</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Status</dt>
                                <dd class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 mt-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active Foundation Initiative
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Support & Volunteer Card --}}
                    <div class="bg-maroon text-white rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-cream/90 block">Get Involved</span>
                            <h3 class="text-2xl font-bold text-white mt-1">Support this Initiative</h3>
                        </div>

                        <p class="text-sm text-white/90 leading-relaxed">
                            Your contribution helps us equip another lab, provide literacy packs, or sponsor an indigent student to participate.
                        </p>

                        <div class="space-y-3 pt-2">
                            <a href="{{ url('/donate') }}" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-bold bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all shadow-md cursor-pointer">
                                Donate to this Project
                            </a>
                            <a href="{{ url('/get-involved') }}" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold border-2 border-white/60 text-white hover:bg-white/10 transition-all cursor-pointer">
                                Volunteer With Us
                            </a>
                        </div>
                    </div>

                    {{-- Other Programmes List --}}
                    @if ($relatedProgrammes->isNotEmpty())
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 shadow-xs space-y-4">
                            <h3 class="text-base font-bold text-[var(--heading)] pb-2 border-b border-[var(--border)]">
                                More Programmes
                            </h3>

                            <div class="space-y-3">
                                @foreach ($relatedProgrammes as $related)
                                    <a href="{{ route('programmes.show', $related->slug) }}" 
                                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-[var(--surface-alt)] transition-colors group">
                                        <div class="w-12 h-12 rounded-lg bg-[var(--surface-alt)] overflow-hidden shrink-0 border border-[var(--border)]">
                                            <img src="{{ $related->cover_image_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-[var(--heading)] group-hover:text-crimson dark:group-hover:text-mint transition-colors line-clamp-1 block">
                                                {{ $related->title }}
                                            </span>
                                            <span class="text-[11px] text-[var(--text-muted)] line-clamp-1 block mt-0.5">
                                                {{ $related->summary }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    {{-- 3. Final Call to Action --}}
    <section class="py-16 bg-[var(--surface-alt)] border-t border-[var(--border)] transition-colors">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                Want to Partner with Dzuels on this Initiative?
            </h2>
            <p class="text-base text-[var(--text-muted)] max-w-xl mx-auto">
                We welcome co-sponsors, educational volunteers, and corporate foundations. Let's create measurable, lasting transformation together.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url('/get-involved') }}" 
                   class="inline-flex items-center justify-center px-8 py-3 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md">
                    Submit Partnership Enquiry
                </a>
                <a href="{{ route('programmes.index') }}" 
                   class="inline-flex items-center justify-center px-8 py-3 rounded-full text-sm font-semibold border border-[var(--border)] text-[var(--text-strong)] hover:border-maroon transition-all">
                    View All Programmes
                </a>
            </div>
        </div>
    </section>
</x-layout>
