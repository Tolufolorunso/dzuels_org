<x-layout
    title="Page Not Found (404) | Dzuels Educational Foundation"
    description="The page you requested could not be found. Return to Dzuels Educational Foundation home page or explore our educational initiatives."
    meta-robots="noindex, follow">

    <section class="min-h-[70vh] flex items-center justify-center py-16 sm:py-24 bg-[var(--surface)] transition-colors">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">

            {{-- 404 Brand Badge & Icon --}}
            <div class="inline-flex items-center justify-center">
                <div class="relative">
                    <span class="text-8xl sm:text-9xl font-black text-maroon/10 dark:text-[var(--accent-cream)]/10 select-none tracking-tighter">
                        404
                    </span>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-maroon/10 dark:bg-maroon/30 text-maroon dark:text-[var(--accent-cream)] border border-maroon/20">
                            <span>🔍</span> Page Not Found
                        </span>
                    </div>
                </div>
            </div>

            {{-- Explanatory Heading & Message --}}
            <div class="space-y-3">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    We Couldn't Find That Page
                </h1>
                <p class="text-base sm:text-lg text-[var(--text-muted)] max-w-xl mx-auto leading-relaxed">
                    The link you followed might be broken, mistyped, or the page may have been moved during our recent website overhaul.
                </p>
            </div>

            {{-- Primary Action Buttons: Home and Donate --}}
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full font-bold text-sm bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-maroon focus:ring-offset-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Return to Homepage</span>
                </a>

                <a href="{{ url('/donate') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full font-bold text-sm bg-crimson text-white hover:bg-crimson-hover transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-crimson focus:ring-offset-2">
                    <span>💚</span>
                    <span>Support With a Donation</span>
                </a>
            </div>

            {{-- Quick Directory Links --}}
            <div class="pt-10 border-t border-[var(--border)]">
                <p class="text-xs uppercase tracking-wider font-bold text-[var(--text-muted)] mb-4">
                    Or explore these active sections:
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3 text-sm">
                    <a href="{{ route('about') }}" class="px-3.5 py-1.5 rounded-lg bg-[var(--bg)] border border-[var(--border)] text-[var(--text-strong)] hover:text-maroon hover:border-maroon/30 transition-colors">
                        About Our Story
                    </a>
                    <a href="{{ route('programmes.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[var(--bg)] border border-[var(--border)] text-[var(--text-strong)] hover:text-maroon hover:border-maroon/30 transition-colors">
                        Educational Programmes
                    </a>
                    <a href="{{ route('events.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[var(--bg)] border border-[var(--border)] text-[var(--text-strong)] hover:text-maroon hover:border-maroon/30 transition-colors">
                        Events & ITD 2026
                    </a>
                    <a href="{{ route('gallery.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[var(--bg)] border border-[var(--border)] text-[var(--text-strong)] hover:text-maroon hover:border-maroon/30 transition-colors">
                        Photo Gallery
                    </a>
                    <a href="{{ route('contact') }}" class="px-3.5 py-1.5 rounded-lg bg-[var(--bg)] border border-[var(--border)] text-[var(--text-strong)] hover:text-maroon hover:border-maroon/30 transition-colors">
                        Contact Us
                    </a>
                </div>
            </div>

        </div>
    </section>

</x-layout>
