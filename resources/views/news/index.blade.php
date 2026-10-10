<x-layout>
    <x-slot:title>News & Stories | Dzuels Educational Foundation</x-slot:title>
    <x-slot:description>Stay updated with the latest news, literacy milestones, technology trainings, and community stories from Dzuels Educational Foundation across Nigeria.</x-slot:description>

    {{-- 1. Hero & Breadcrumb Banner --}}
    <section class="relative bg-maroon text-white py-16 sm:py-24 overflow-hidden">
        {{-- Background Pattern & Linear Gradient --}}
        <div class="absolute inset-0 z-0 opacity-20">
            <div class="absolute inset-0 bg-[radial-gradient(#F9DEC9_1px,transparent_1px)] [background-size:24px_24px]"></div>
        </div>
        <div class="absolute inset-0 bg-linear-to-b from-maroon/90 via-maroon to-[#2B0203]"></div>

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
                <span class="text-white font-semibold" aria-current="page">News</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs">
                    Voices & Updates
                </span>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    News & Community <span class="script-accent text-[var(--accent-cream)] text-5xl sm:text-6xl lg:text-7xl font-normal block sm:inline">Stories</span>
                </h1>

                <p class="text-lg sm:text-xl text-white/90 leading-relaxed max-w-2xl pt-2">
                    Follow our journey of addressing educational inequity, celebrating student successes, and expanding digital literacy in Ekiti State and beyond.
                </p>
            </div>
        </div>
    </section>

    {{-- 2. News Index & Articles Grid --}}
    <section class="py-16 sm:py-20 bg-[var(--bg)] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($posts->isEmpty())
                <div class="max-w-lg mx-auto text-center py-20 px-6 bg-[var(--surface)] border border-[var(--border)] rounded-3xl shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-maroon/10 text-maroon flex items-center justify-center mx-auto mb-4 text-2xl">
                        📰
                    </div>
                    <h2 class="text-xl font-bold text-[var(--heading)] mb-2">No Articles Published Yet</h2>
                    <p class="text-sm text-[var(--text-muted)] leading-relaxed mb-6">
                        We are currently preparing inspiring updates on our digital labs and literacy campaigns. Check back soon!
                    </p>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-semibold bg-maroon text-white hover:bg-crimson transition-all">
                        Return to Homepage
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        <article class="flex flex-col bg-[var(--surface)] border border-[var(--border)] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                            {{-- Cover Photo --}}
                            <a href="{{ route('news.show', $post->slug) }}" class="relative aspect-16/10 overflow-hidden bg-stone-100 block">
                                <img src="{{ $post->cover_image_url }}" 
                                     alt="{{ $post->title }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                @if ($post->programme)
                                    <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/95 text-maroon shadow-xs backdrop-blur-xs">
                                        {{ $post->programme->title }}
                                    </span>
                                @endif
                            </a>

                            {{-- Card Body --}}
                            <div class="p-6 sm:p-7 flex flex-col flex-grow">
                                <div class="flex items-center gap-3 text-xs text-[var(--text-muted)] mb-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $post->published_at ? $post->published_at->format('M j, Y') : $post->created_at->format('M j, Y') }}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $post->reading_time_minutes }} min read
                                    </span>
                                </div>

                                <h2 class="text-xl font-bold text-[var(--heading)] group-hover:text-maroon transition-colors line-clamp-2 leading-snug mb-3">
                                    <a href="{{ route('news.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                @if ($post->excerpt)
                                    <p class="text-sm text-[var(--text)] line-clamp-3 leading-relaxed mb-6">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif

                                <div class="mt-auto pt-4 border-t border-[var(--border-subtle)] flex items-center justify-between">
                                    <a href="{{ route('news.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-maroon dark:text-[var(--accent-cream)] group-hover:underline">
                                        <span>Read Story</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>

                                    <span class="text-xs text-[var(--text-muted)] font-medium">
                                        Dzuels Foundation
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($posts->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $posts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>

    {{-- 3. Call to Action Strip --}}
    <section class="py-16 sm:py-20 bg-[var(--bg-alt)] border-t border-[var(--border)] transition-colors">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                Partner with us to create more stories of change
            </h2>
            <p class="text-[var(--text)] text-base max-w-2xl mx-auto leading-relaxed">
                Every laptop donated, volunteer hour pledged, and student sponsored helps rewrite the future of children in rural Nigeria.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="{{ url('/donate') }}" 
                   class="inline-flex items-center justify-center px-7 py-3 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:-translate-y-0.5">
                    Support Our Mission
                </a>
                <a href="{{ url('/get-involved') }}" 
                   class="inline-flex items-center justify-center px-7 py-3 rounded-full text-sm font-semibold border border-[var(--border)] text-[var(--text-strong)] hover:border-maroon transition-all">
                    Volunteer Your Time
                </a>
            </div>
        </div>
    </section>
</x-layout>
