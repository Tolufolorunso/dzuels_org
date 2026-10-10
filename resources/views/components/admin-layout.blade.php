<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard' }} | Dzuels Educational Foundation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 min-h-screen flex flex-col font-sans text-stone-800 antialiased">
    <!-- Top Administrative Navbar -->
    <header class="bg-[var(--utility-bg)] text-white border-b border-white/10 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Portal Indicator -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3">
                        <span class="bg-white p-1 rounded-lg">
                            <img src="{{ asset('front/images/logo.png') }}" alt="Dzuels Logo" class="h-7 w-auto object-contain">
                        </span>
                        <div class="hidden sm:block">
                            <span class="font-bold text-sm tracking-tight text-white">Dzuels Administration</span>
                            <span class="block text-[10px] text-[var(--accent-cream)] uppercase tracking-wider">Foundation Portal</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Tabs -->
                <nav class="hidden md:flex items-center gap-1" aria-label="Admin Navigation">
                    <a href="{{ route('admin.settings') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-white/15 text-white' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                        Site Settings
                    </a>
                    <a href="{{ route('admin.programmes.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin.programmes*') ? 'bg-white/15 text-white' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                        Programmes
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin.events*') ? 'bg-white/15 text-white' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                        Events
                    </a>
                    <a href="{{ route('admin.gallery.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin.gallery*') ? 'bg-white/15 text-white' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                        Gallery
                    </a>
                    <a href="{{ route('admin.team.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin.team*') ? 'bg-white/15 text-white' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                        Team Members
                    </a>
                    <a href="{{ route('admin.partners.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin.partners*') ? 'bg-white/15 text-white' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                        Partners
                    </a>
                </nav>

                <!-- User Profile & Actions -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block text-xs">
                        <span class="font-semibold text-white block">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] uppercase font-bold text-[var(--accent-cream)]">{{ auth()->user()->role }}</span>
                    </div>

                    <a href="{{ url('/') }}" target="_blank" class="text-xs text-stone-300 hover:text-white transition-colors border border-white/20 rounded-lg px-2.5 py-1.5 flex items-center gap-1.5" title="View live public website">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span class="hidden sm:inline">View Site</span>
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-1.5 text-stone-400 hover:text-rose-400 hover:bg-white/5 rounded-lg transition-colors cursor-pointer" title="Sign out">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Success Notification -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-medium flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Flash Error Notification -->
        @if (session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-sm font-medium flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-stone-200 py-4 text-center text-xs text-stone-500">
        <p>© {{ date('Y') }} Dzuels Educational Foundation • Administration Portal</p>
    </footer>
</body>
</html>
