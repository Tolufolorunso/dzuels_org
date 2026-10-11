<x-admin-layout title="Dashboard Overview">
    <div class="space-y-8">
        <!-- 1. Executive Welcome Hero Banner -->
        <div class="relative bg-gradient-to-r from-[#1F0203] via-maroon to-[#2B0203] rounded-3xl p-6 sm:p-8 text-white shadow-lg overflow-hidden border border-white/10">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-[var(--accent-cream)]/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -top-10 w-64 h-64 rounded-full bg-crimson/20 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-[var(--accent-cream)] border border-white/10 backdrop-blur-xs">
                        <span>👋</span> Welcome back, {{ auth()->user()->name }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                        Dzuels Foundation Operations Center
                    </h1>
                    <p class="text-sm text-stone-200 max-w-2xl leading-relaxed">
                        Manage community educational initiatives, flagship tech events, impact statistics, and public inquiries from one centralized workspace.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[var(--accent-cream)] text-maroon hover:bg-[#FBE9DC] transition-all shadow-xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Pages CMS</span>
                    </a>
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-white/15 text-white hover:bg-white/25 border border-white/20 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Staff Accounts</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. High-Level KPI Metric Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- KPI 1: Event Registrations -->
            <a href="{{ route('admin.events.index') }}" class="group bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs hover:shadow-md hover:border-maroon/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-stone-400 group-hover:text-maroon transition-colors flex items-center gap-1">
                        <span>Manage</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-stone-900 tracking-tight block">{{ number_format($totalRegistrations) }}</span>
                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block mt-1">Total Event Registrations</span>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">Across {{ $activeEvents }} active gathering(s)</span>
                </div>
            </a>

            <!-- KPI 2: Active Programmes -->
            <a href="{{ route('admin.programmes.index') }}" class="group bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs hover:shadow-md hover:border-maroon/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-stone-400 group-hover:text-maroon transition-colors flex items-center gap-1">
                        <span>View</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-stone-900 tracking-tight block">{{ number_format($activeProgrammes) }}</span>
                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block mt-1">Active Initiatives</span>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">Grassroots literacy & digital infrastructure</span>
                </div>
            </a>

            <!-- KPI 3: Team & Mentors -->
            <a href="{{ route('admin.team.index') }}" class="group bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs hover:shadow-md hover:border-maroon/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-stone-400 group-hover:text-maroon transition-colors flex items-center gap-1">
                        <span>Team</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-stone-900 tracking-tight block">{{ number_format($teamMembersCount) }}</span>
                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block mt-1">Foundation Leaders</span>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">Board of trustees & lead mentors</span>
                </div>
            </a>

            <!-- KPI 4: Active Partners -->
            <a href="{{ route('admin.partners.index') }}" class="group bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs hover:shadow-md hover:border-maroon/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-stone-400 group-hover:text-maroon transition-colors flex items-center gap-1">
                        <span>Partners</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-stone-900 tracking-tight block">{{ number_format($partnersCount) }}</span>
                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block mt-1">Supporting Partners</span>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">Corporate & NGO collaborators</span>
                </div>
            </a>

            <!-- KPI 5: Contact Messages -->
            <a href="{{ route('admin.messages.index') }}" class="group bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs hover:shadow-md hover:border-maroon/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center group-hover:scale-110 transition-transform relative">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        @if ($unreadMessagesCount > 0)
                            <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-rose-500 border-2 border-white"></span>
                        @endif
                    </span>
                    <span class="text-xs font-semibold text-stone-400 group-hover:text-maroon transition-colors flex items-center gap-1">
                        <span>Inbox</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-stone-900 tracking-tight">{{ number_format($totalMessagesCount) }}</span>
                        @if ($unreadMessagesCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                {{ $unreadMessagesCount }} new
                            </span>
                        @endif
                    </div>
                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block mt-1">Contact Messages</span>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">Inquiries received via public contact form</span>
                </div>
            </a>

            <!-- KPI 6: Volunteer & Partner Inquiries -->
            <a href="{{ route('admin.inquiries.index') }}" class="group bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs hover:shadow-md hover:border-maroon/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-stone-400 group-hover:text-maroon transition-colors flex items-center gap-1">
                        <span>Review</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-stone-900 tracking-tight">{{ number_format($pendingVolunteersCount + $pendingPartnersCount) }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                            pending
                        </span>
                    </div>
                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block mt-1">Get Involved Inquiries</span>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">{{ $pendingVolunteersCount }} volunteers • {{ $pendingPartnersCount }} partners</span>
                </div>
            </a>
        </div>

        <!-- 3. Two-Column Activity & Flagship Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Upcoming Flagship Event & Recent Registrations -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Flagship Event Card -->
                @if ($upcomingEvents->isNotEmpty())
                    @php $flagship = $upcomingEvents->first(); @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-2xs space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-100">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Flagship Upcoming Event
                                </span>
                                <h2 class="text-xl font-bold text-stone-900 mt-2 tracking-tight">{{ $flagship->title }}</h2>
                                @if ($flagship->theme)
                                    <p class="text-xs font-semibold text-crimson italic mt-0.5">"{{ $flagship->theme }}"</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.events.registrations', $flagship) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-stone-900 transition-colors shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>Attendees ({{ $flagship->registrations()->count() }})</span>
                                </a>
                                <a href="{{ route('admin.events.edit', $flagship) }}" class="p-2 text-stone-400 hover:text-stone-700 hover:bg-stone-100 rounded-xl transition-colors" title="Edit event details">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-stone-50 rounded-2xl p-4 border border-stone-200/60">
                                <span class="text-[10px] uppercase font-bold text-stone-400 block tracking-wider">Date & Time</span>
                                <span class="text-sm font-bold text-stone-900 block mt-1">{{ $flagship->event_date->format('M d, Y • h:i A') }}</span>
                            </div>
                            <div class="bg-stone-50 rounded-2xl p-4 border border-stone-200/60 sm:col-span-2">
                                <span class="text-[10px] uppercase font-bold text-stone-400 block tracking-wider">Venue Location</span>
                                <span class="text-sm font-bold text-stone-900 block mt-1 truncate">{{ $flagship->venue }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Registrations Table -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-2xs space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <div>
                            <h2 class="text-base font-bold text-stone-900 tracking-tight">Recent Event Registrations</h2>
                            <p class="text-xs text-stone-500 mt-0.5">Latest attendees signed up for upcoming gatherings</p>
                        </div>
                        <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-maroon hover:underline">
                            View All
                        </a>
                    </div>

                    @if ($recentRegistrations->isEmpty())
                        <div class="py-8 text-center text-stone-400 text-xs">
                            No event registrations received yet.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-stone-400 font-semibold uppercase tracking-wider border-b border-stone-100">
                                        <th class="pb-3 pl-1">Attendee</th>
                                        <th class="pb-3">Category</th>
                                        <th class="pb-3">Event</th>
                                        <th class="pb-3 text-right pr-1">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 font-medium text-stone-700">
                                    @foreach ($recentRegistrations as $reg)
                                        <tr class="hover:bg-stone-50/80 transition-colors">
                                            <td class="py-3 pl-1 font-bold text-stone-900">
                                                <div>{{ $reg->full_name }}</div>
                                                <div class="text-[11px] font-normal text-stone-400">{{ $reg->email }}</div>
                                            </td>
                                            <td class="py-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-stone-100 text-stone-700">
                                                    {{ str_replace('_', ' ', $reg->attendee_type ?? 'student') }}
                                                </span>
                                            </td>
                                            <td class="py-3 max-w-[160px] truncate">
                                                {{ $reg->event->title ?? 'N/A' }}
                                            </td>
                                            <td class="py-3 text-right pr-1 text-stone-400">
                                                {{ $reg->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Quick Operations & Recent Messages -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Quick Management Shortcuts -->
                <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs space-y-4">
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Quick Actions</h3>

                    <div class="space-y-2">
                        @if(auth()->user()->canManageContent())
                        <a href="{{ route('admin.pages.index') }}" class="flex items-center justify-between p-3 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200/60 transition-colors group">
                            <span class="text-xs font-semibold text-stone-800 group-hover:text-maroon">Edit Page Content (CMS)</span>
                            <span class="text-stone-400 group-hover:text-maroon text-sm font-bold">→</span>
                        </a>
                        <a href="{{ route('admin.programmes.create') }}" class="flex items-center justify-between p-3 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200/60 transition-colors group">
                            <span class="text-xs font-semibold text-stone-800 group-hover:text-maroon">Add New Programme</span>
                            <span class="text-stone-400 group-hover:text-maroon text-sm font-bold">→</span>
                        </a>
                        <a href="{{ route('admin.events.create') }}" class="flex items-center justify-between p-3 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200/60 transition-colors group">
                            <span class="text-xs font-semibold text-stone-800 group-hover:text-maroon">Create New Event</span>
                            <span class="text-stone-400 group-hover:text-maroon text-sm font-bold">→</span>
                        </a>
                        @endif
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between p-3 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200/60 transition-colors group">
                            <span class="text-xs font-semibold text-stone-800 group-hover:text-maroon">Manage Staff Accounts</span>
                            <span class="text-stone-400 group-hover:text-maroon text-sm font-bold">→</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center justify-between p-3 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200/60 transition-colors group">
                            <span class="text-xs font-semibold text-stone-800 group-hover:text-maroon">Update Impact Stats</span>
                            <span class="text-stone-400 group-hover:text-maroon text-sm font-bold">→</span>
                        </a>
                        @endif
                        <a href="{{ route('admin.profile.password') }}" class="flex items-center justify-between p-3 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200/60 transition-colors group">
                            <span class="text-xs font-semibold text-stone-800 group-hover:text-maroon">Change Account Password</span>
                            <span class="text-stone-400 group-hover:text-maroon text-sm font-bold">→</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Public Messages Feed -->
                <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Latest Messages</h3>
                        <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-maroon hover:underline">
                            View All
                        </a>
                    </div>

                    @if ($recentMessages->isEmpty())
                        <p class="text-xs text-stone-400 py-4 text-center">No contact inquiries recorded yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentMessages as $msg)
                                <a href="{{ route('admin.messages.index') }}" class="block p-3 rounded-2xl border border-stone-200/60 hover:border-maroon/30 transition-colors space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-stone-900 truncate">{{ $msg->first_name }} {{ $msg->last_name }}</span>
                                        <span class="text-[10px] text-stone-400 shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-stone-600 line-clamp-1 font-medium">{{ $msg->subject }}</p>
                                    <div>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $msg->status === 'unread' ? 'bg-rose-100 text-rose-800' : 'bg-stone-100 text-stone-600' }}">
                                            {{ $msg->status }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
