<!DOCTYPE html>
<html lang="en" class="scroll-smooth overflow-x-hidden max-w-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error (500) | Dzuels Educational Foundation</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Anti-flicker dark mode engine -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('dzuels-theme');
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen w-full flex flex-col font-sans antialiased text-[var(--text)] bg-[var(--bg)] selection:bg-[var(--accent-cream)] selection:text-maroon">

    <!-- Header Bar -->
    <header class="bg-[var(--surface)] border-b border-[var(--border)] py-4 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('front/images/logo.png') }}" alt="Dzuels Educational Foundation" class="h-10 sm:h-11 w-auto object-contain">
                <span class="sr-only">Dzuels Educational Foundation</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-xs sm:text-sm font-semibold text-[var(--text-strong)] hover:text-maroon transition-colors">
                    Home
                </a>
                <a href="{{ url('/donate') }}" class="text-xs sm:text-sm font-semibold px-4 py-1.5 rounded-full bg-crimson text-white hover:bg-crimson-hover transition-colors">
                    Donate
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow flex items-center justify-center py-16 sm:py-24 px-4 sm:px-6 lg:px-8 bg-[var(--surface)] transition-colors">
        <div class="max-w-2xl mx-auto text-center space-y-8">

            {{-- 500 Badge --}}
            <div class="inline-flex items-center justify-center">
                <div class="relative">
                    <span class="text-8xl sm:text-9xl font-black text-rose-500/10 dark:text-rose-400/10 select-none tracking-tighter">
                        500
                    </span>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                            <span>⚠️</span> Temporary Technical Issue
                        </span>
                    </div>
                </div>
            </div>

            {{-- Heading & Message --}}
            <div class="space-y-3">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Something Went Unexpectedly Wrong
                </h1>
                <p class="text-base sm:text-lg text-[var(--text-muted)] max-w-xl mx-auto leading-relaxed">
                    Our server encountered a temporary glitch while fulfilling your request. Our technical team has been notified and is working to restore normal service.
                </p>
            </div>

            {{-- Recovery Action Buttons: Home and Donate --}}
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
                    <span>Support Our Mission</span>
                </a>
            </div>

            {{-- Direct Support Fallback --}}
            <div class="pt-8 border-t border-[var(--border)] text-xs text-[var(--text-muted)] space-y-2">
                <p>
                    Need urgent assistance or have questions regarding our educational programs?
                </p>
                <p class="font-medium text-[var(--text-strong)]">
                    Email us directly at <a href="mailto:info@dzuelsfoundation.org" class="text-maroon dark:text-[var(--accent-cream)] underline">info@dzuelsfoundation.org</a> or call <a href="tel:+2348033889576" class="text-maroon dark:text-[var(--accent-cream)] underline">+234 803 388 9576</a>.
                </p>
            </div>

        </div>
    </main>

    <!-- Minimal Footer -->
    <footer class="bg-[var(--surface-raised)] border-t border-[var(--border)] py-6 text-center text-xs text-[var(--text-muted)]">
        <p>&copy; {{ date('Y') }} Dzuels Educational Foundation &bull; Registered Incorporated Trustee CAC/IT/NO 123456</p>
    </footer>

</body>
</html>
