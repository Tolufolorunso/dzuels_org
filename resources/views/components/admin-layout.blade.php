<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard' }} | Dzuels Educational Foundation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="h-full bg-stone-100 font-sans text-stone-800 antialiased max-w-full overflow-x-hidden">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div x-show="sidebarOpen" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs z-50 lg:hidden"
             aria-hidden="true"></div>

        <!-- Off-canvas Mobile Drawer -->
        <div x-show="sidebarOpen" 
             x-cloak
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-72 bg-[#1A0203] text-white flex flex-col lg:hidden shadow-2xl border-r border-white/10">
            <!-- Mobile Drawer Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-white/10 bg-[#140102]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <span class="bg-white p-1 rounded-lg shrink-0">
                        <img src="{{ asset('front/images/logo.png') }}" alt="Dzuels Logo" class="h-7 w-auto object-contain">
                    </span>
                    <div>
                        <span class="font-bold text-sm tracking-tight text-white block">Dzuels Admin</span>
                        <span class="text-[10px] text-[var(--accent-cream)] uppercase font-semibold tracking-wider">Foundation Portal</span>
                    </div>
                </a>
                <button type="button" @click="sidebarOpen = false" class="p-2 text-stone-400 hover:text-white rounded-lg focus:outline-none" aria-label="Close menu">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
                <!-- Group: Core -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Core</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Dashboard Overview</span>
                        </a>
                    </div>
                </div>

                <!-- Group: Content Management -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Content Management</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.programmes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.programmes*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Programmes</span>
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.events*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Events & Gatherings</span>
                        </a>
                        <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.gallery*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Visual Gallery</span>
                        </a>
                    </div>
                </div>

                <!-- Group: Organization -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Organization</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.team.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.team*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Team & Mentors</span>
                        </a>
                        <a href="{{ route('admin.partners.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.partners*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Partners</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.settings*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Site Settings</span>
                        </a>
                    </div>
                </div>

                <!-- Group: Inboxes -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Inboxes</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.inquiries.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.inquiries*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Volunteer Inquiries</span>
                        </a>
                        <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.messages*') ? 'bg-white/15 text-white border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Contact Messages</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Drawer User Profile Footer -->
            <div class="p-4 border-t border-white/10 bg-[#140102]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-crimson flex items-center justify-center font-bold text-xs text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-xs">
                            <span class="font-semibold text-white block">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] uppercase font-bold text-[var(--accent-cream)]">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 text-stone-400 hover:text-rose-400 rounded-lg" title="Sign out">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Desktop Persistent Sidebar (Fixed on Desktop) -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:shrink-0 bg-[#1A0203] text-white border-r border-white/10 lg:fixed lg:inset-y-0 z-30">
            <!-- Brand Portal Header -->
            <div class="h-18 flex items-center px-6 border-b border-white/10 bg-[#140102]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <span class="bg-white p-1 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                        <img src="{{ asset('front/images/logo.png') }}" alt="Dzuels Logo" class="h-8 w-auto object-contain">
                    </span>
                    <div>
                        <span class="font-bold text-sm tracking-tight text-white block">Dzuels Admin</span>
                        <span class="text-[10px] text-[var(--accent-cream)] uppercase font-semibold tracking-wider">Foundation Portal</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links Scroll Area -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-6" aria-label="Admin Navigation">
                <!-- Group: Core -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Core</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0 text-[var(--accent-cream)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Dashboard Overview</span>
                        </a>
                    </div>
                </div>

                <!-- Group: Content Management -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Content Management</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.programmes.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.programmes*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Programmes</span>
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.events*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Events & Gatherings</span>
                        </a>
                        <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.gallery*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Visual Gallery</span>
                        </a>
                    </div>
                </div>

                <!-- Group: Organization -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Organization</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.team.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.team*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Team & Mentors</span>
                        </a>
                        <a href="{{ route('admin.partners.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.partners*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Partners</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.settings*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Site Settings</span>
                        </a>
                    </div>
                </div>

                <!-- Group: Inboxes -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-stone-400">Inboxes</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.inquiries.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.inquiries*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Volunteer Inquiries</span>
                        </a>
                        <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.messages*') ? 'bg-white/15 text-white shadow-xs border-l-3 border-[var(--accent-cream)]' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Contact Messages</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Desktop Sidebar Footer -->
            <div class="p-4 border-t border-white/10 bg-[#140102]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-crimson flex items-center justify-center font-bold text-xs text-white shrink-0 shadow-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <span class="font-semibold text-xs text-white block truncate">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] uppercase font-bold text-[var(--accent-cream)] tracking-wider">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-stone-400 hover:text-rose-400 hover:bg-white/5 rounded-xl transition-colors cursor-pointer" title="Sign out">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area with Desktop Left Offset -->
        <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
            <!-- Modern Topbar -->
            <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-md border-b border-stone-200/80 shadow-2xs">
                <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4">
                    <!-- Left: Mobile Menu Toggle & Title / Breadcrumbs -->
                    <div class="flex items-center gap-3 min-w-0">
                        <button type="button" 
                                @click="sidebarOpen = true" 
                                class="lg:hidden p-2 -ml-2 text-stone-600 hover:text-stone-900 hover:bg-stone-100 rounded-xl transition-colors focus:outline-none" 
                                aria-label="Open sidebar">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div>
                            <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">Dzuels Control Center</span>
                            <h2 class="text-base sm:text-lg font-bold text-stone-900 tracking-tight truncate">
                                {{ $title ?? 'Administration Portal' }}
                            </h2>
                        </div>
                    </div>

                    <!-- Right: Live Site Shortcut & Quick User Actions -->
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-stone-700 bg-stone-100 hover:bg-stone-200 border border-stone-200 transition-colors shadow-2xs" 
                           title="Preview live public website in new tab">
                            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span class="hidden sm:inline">Live Site</span>
                        </a>

                        <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-stone-200 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-stone-500 font-medium">Online</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Slot Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Success Notification -->
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-medium flex items-center gap-3 shadow-2xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Error Notification -->
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-sm font-medium flex items-center gap-3 shadow-2xs">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Admin Footer -->
            <footer class="bg-white border-t border-stone-200 py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-stone-500 mt-auto">
                <p>© {{ date('Y') }} Dzuels Educational Foundation • Administration Operations Portal</p>
            </footer>
        </div>
    </div>
</body>
</html>
