<header class="sticky top-0 z-50 bg-[var(--surface)]/95 backdrop-blur-md border-b border-[var(--border)] transition-colors duration-200 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-maroon rounded-lg p-1">
                <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Dzuels Educational Foundation" class="h-13 sm:h-13 w-auto object-contain transition-transform group-hover:scale-105 duration-200">
                <span class="sr-only">Dzuels Educational Foundation</span>
            </a>

            <!-- Desktop Navigation Links (>= 1024px) -->
            <nav class="hidden lg:flex items-center gap-1 xl:gap-2" aria-label="Main Navigation">
                <a href="{{ url('/') }}" class="px-3.5 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->is('/') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] dark:hover:text-[var(--accent-cream)]' }}">
                    Home
                </a>
                <a href="{{ url('/about') }}" class="px-3.5 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->is('about*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] dark:hover:text-[var(--accent-cream)]' }}">
                    About Us
                </a>
                <a href="{{ url('/programmes') }}" class="px-3.5 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->is('programmes*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] dark:hover:text-[var(--accent-cream)]' }}">
                    Programmes
                </a>
                <a href="{{ url('/events') }}" class="px-3.5 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->is('events*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] dark:hover:text-[var(--accent-cream)]' }}">
                    Events
                </a>
                <a href="{{ url('/gallery') }}" class="px-3.5 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->is('gallery*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] dark:hover:text-[var(--accent-cream)]' }}">
                    Gallery
                </a>
                <a href="{{ url('/get-involved') }}" class="px-3.5 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->is('get-involved*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] dark:hover:text-[var(--accent-cream)]' }}">
                    Get Involved
                </a>
                <a href="{{ url('/contact') }}" class="px-3.5 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->is('contact*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] dark:hover:text-[var(--accent-cream)]' }}">
                    Contact
                </a>
            </nav>

            <!-- Header Actions: Theme Toggle, Donate Button, Mobile Hamburger -->
            <div class="flex items-center gap-3">
                <x-theme-toggle />

                <a href="{{ url('/donate') }}" class="hidden sm:inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-maroon focus:ring-offset-2">
                    <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    <span>Donate</span>
                </a>

                <!-- Mobile Hamburger Button (< 1024px) -->
                <button 
                    id="mobileMenuToggle" 
                    type="button" 
                    aria-label="Toggle navigation menu" 
                    aria-expanded="false" 
                    aria-controls="mobileNav"
                    class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-[var(--text-strong)] hover:text-maroon hover:bg-[var(--accent-cream-light)] focus:outline-none focus:ring-2 focus:ring-maroon cursor-pointer transition-colors"
                >
                    <svg id="hamburgerIcon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="closeIcon" class="hidden w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileNav" class="hidden lg:hidden border-t border-[var(--border)] bg-[var(--surface)] px-4 pt-4 pb-6 transition-colors duration-200">
        <nav class="flex flex-col gap-1.5" aria-label="Mobile Navigation">
            <a href="{{ url('/') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->is('/') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:bg-[var(--accent-cream-light)] hover:text-maroon' }}">
                Home
            </a>
            <a href="{{ url('/about') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->is('about*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:bg-[var(--accent-cream-light)] hover:text-maroon' }}">
                About Us
            </a>
            <a href="{{ url('/programmes') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->is('programmes*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:bg-[var(--accent-cream-light)] hover:text-maroon' }}">
                Programmes
            </a>
            <a href="{{ url('/events') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->is('events*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:bg-[var(--accent-cream-light)] hover:text-maroon' }}">
                Events
            </a>
            <a href="{{ url('/gallery') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->is('gallery*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:bg-[var(--accent-cream-light)] hover:text-maroon' }}">
                Gallery
            </a>
            <a href="{{ url('/get-involved') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->is('get-involved*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:bg-[var(--accent-cream-light)] hover:text-maroon' }}">
                Get Involved
            </a>
            <a href="{{ url('/contact') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->is('contact*') ? 'text-maroon font-semibold bg-[var(--accent-cream-light)] dark:text-[var(--accent-cream)]' : 'text-[var(--text-strong)] hover:bg-[var(--accent-cream-light)] hover:text-maroon' }}">
                Contact
            </a>

            <div class="pt-3 mt-2 border-t border-[var(--border)]">
                <a href="{{ url('/donate') }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-full text-base font-semibold bg-maroon text-white hover:bg-crimson transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    <span>Support Our Cause — Donate</span>
                </a>
            </div>
        </nav>
    </div>
</header>

<script>
    (function() {
        const toggle = document.getElementById('mobileMenuToggle');
        const menu = document.getElementById('mobileNav');
        const hamburger = document.getElementById('hamburgerIcon');
        const close = document.getElementById('closeIcon');

        if (toggle && menu) {
            toggle.addEventListener('click', function() {
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !isExpanded);
                menu.classList.toggle('hidden');
                if (hamburger && close) {
                    hamburger.classList.toggle('hidden');
                    close.classList.toggle('hidden');
                }
            });

            // Close on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                    if (hamburger && close) {
                        hamburger.classList.remove('hidden');
                        close.classList.add('hidden');
                    }
                }
            });
        }
    })();
</script>
