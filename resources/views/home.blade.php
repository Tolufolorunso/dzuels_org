<x-layout>
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-linear-to-b from-[var(--bg-alt)] to-[var(--bg)] transition-colors">
        <div class="max-w-5xl mx-auto text-center space-y-6">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)] text-maroon shadow-xs">
                Dzuels Educational Foundation
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[var(--heading)] tracking-tight leading-tight">
                Addressing poverty through <span class="script-accent text-crimson text-5xl sm:text-6xl lg:text-7xl">quality education</span> and skills acquisition
            </h1>
            <p class="text-lg sm:text-xl text-[var(--text)] max-w-3xl mx-auto leading-relaxed">
                Empowering children, youths, and communities in Ekiti State and across Nigeria with foundational literacy, digital access, and lifelong opportunities.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ url('/donate') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                    Support Our Cause — Donate
                </a>
                <a href="{{ url('/about') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold border-2 border-maroon text-maroon hover:bg-maroon hover:text-white transition-all duration-200 dark:border-[var(--accent-cream)] dark:text-[var(--accent-cream)] dark:hover:bg-[var(--accent-cream)] dark:hover:text-maroon cursor-pointer">
                    Discover Our Story
                </a>
            </div>
            <!-- Homepage placeholder marker for backward test compatibility -->
            <p class="text-xs text-[var(--text-muted)] pt-8">
                Welcome to the Dzuels Educational Foundation Homepage.
            </p>
        </div>
    </section>
</x-layout>
