<x-admin-layout title="Inquiries & Applications">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Inquiries & Applications</h1>
                <p class="text-sm text-stone-500 mt-1">
                    Manage volunteer mentor submissions and institutional partner collaboration proposals.
                </p>
            </div>
            <div>
                <a href="{{ route('get-involved') }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-stone-200 text-stone-700 hover:bg-stone-50 transition-colors shadow-2xs">
                    <span>View Public Page</span>
                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Filter & Navigation Tabs Bar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl border border-stone-200 shadow-2xs">
            {{-- Tabs for Volunteers vs Partners --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.inquiries.index', array_merge(request()->query(), ['tab' => 'volunteers', 'volunteers_page' => 1])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer {{ $tab === 'volunteers' ? 'bg-maroon text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                    <span>🙋 Volunteers</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'volunteers' ? 'bg-white/20 text-white' : 'bg-stone-200 text-stone-700' }}">
                        {{ $totalVolunteersCount }}
                    </span>
                    @if($pendingVolunteersCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-400 text-amber-950 font-bold" title="{{ $pendingVolunteersCount }} pending review">
                            {{ $pendingVolunteersCount }} new
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.inquiries.index', array_merge(request()->query(), ['tab' => 'partners', 'partners_page' => 1])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer {{ $tab === 'partners' ? 'bg-maroon text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                    <span>🏢 Partner Enquiries</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'partners' ? 'bg-white/20 text-white' : 'bg-stone-200 text-stone-700' }}">
                        {{ $totalPartnersCount }}
                    </span>
                    @if($pendingPartnersCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-400 text-amber-950 font-bold" title="{{ $pendingPartnersCount }} pending review">
                            {{ $pendingPartnersCount }} new
                        </span>
                    @endif
                </a>
            </div>

            {{-- Status Filters & Search Form --}}
            <form method="GET" action="{{ route('admin.inquiries.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">

                {{-- Status Filter Pill List --}}
                <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-xl text-xs">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'reviewed' => 'Reviewed', 'contacted' => 'Contacted'] as $sKey => $sLabel)
                        <a href="{{ route('admin.inquiries.index', array_merge(request()->query(), ['status' => $sKey])) }}"
                           class="px-2.5 py-1 rounded-lg font-medium transition-colors {{ $status === $sKey ? 'bg-white text-stone-900 font-bold shadow-2xs' : 'text-stone-500 hover:text-stone-900' }}">
                            {{ $sLabel }}
                        </a>
                    @endforeach
                </div>

                {{-- Search Box --}}
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ $search }}" 
                           placeholder="Search inquiries..." 
                           class="pl-8 pr-3 py-1.5 rounded-xl border border-stone-200 text-xs bg-stone-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-maroon w-40 sm:w-52">
                    <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                @if($search || $status !== 'all')
                    <a href="{{ route('admin.inquiries.index', ['tab' => $tab]) }}" class="text-xs text-rose-600 hover:underline px-1">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- TAB CONTENT: VOLUNTEERS --}}
        @if ($tab === 'volunteers')
            <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
                @if ($volunteers->isEmpty())
                    <div class="p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400 text-2xl">
                            🙋
                        </div>
                        <p class="text-stone-600 font-medium text-sm">No volunteer applications found.</p>
                        @if($search || $status !== 'all')
                            <p class="text-xs text-stone-400">Try clearing filters or search criteria.</p>
                        @else
                            <p class="text-xs text-stone-400">Submissions from the public Get Involved page will appear here.</p>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-stone-700">
                            <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Volunteer Applicant</th>
                                    <th scope="col" class="px-6 py-3.5">Contact Details</th>
                                    <th scope="col" class="px-6 py-3.5">Age & Work Status</th>
                                    <th scope="col" class="px-6 py-3.5">Skills / Interests</th>
                                    <th scope="col" class="px-6 py-3.5">Applied Date</th>
                                    <th scope="col" class="px-6 py-3.5">Status</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @foreach ($volunteers as $volunteer)
                                    <tr class="hover:bg-stone-50/50 transition-colors">
                                        {{-- Applicant Name --}}
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-stone-900">{{ $volunteer->full_name }}</div>
                                        </td>

                                        {{-- Contact Details --}}
                                        <td class="px-6 py-4 text-xs">
                                            <div>
                                                <a href="mailto:{{ $volunteer->email }}" class="text-maroon hover:underline font-semibold">
                                                    {{ $volunteer->email }}
                                                </a>
                                            </div>
                                            <div class="text-stone-500 mt-0.5">
                                                <a href="tel:{{ $volunteer->phone }}" class="hover:text-stone-800">
                                                    {{ $volunteer->phone }}
                                                </a>
                                            </div>
                                        </td>

                                        {{-- Age & Work Status --}}
                                        <td class="px-6 py-4 text-xs">
                                            <div class="font-medium text-stone-800">
                                                {{ $volunteer->age_band_label }}
                                            </div>
                                            <div class="text-stone-500">
                                                {{ $volunteer->work_status_label }}
                                            </div>
                                        </td>

                                        {{-- Skills / Interests --}}
                                        <td class="px-6 py-4 text-xs text-stone-600 max-w-xs">
                                            @if ($volunteer->skills)
                                                <p class="line-clamp-2" title="{{ $volunteer->skills }}">{{ $volunteer->skills }}</p>
                                            @else
                                                <span class="text-stone-400 italic">None specified</span>
                                            @endif
                                        </td>

                                        {{-- Submitted Date --}}
                                        <td class="px-6 py-4 text-xs text-stone-500 whitespace-nowrap">
                                            {{ $volunteer->created_at?->format('M j, Y') ?? '—' }}
                                        </td>

                                        {{-- Status Selector Form --}}
                                        <td class="px-6 py-4 text-xs whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.inquiries.volunteers.status', $volunteer) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" 
                                                        onchange="this.form.submit()" 
                                                        class="text-xs font-semibold px-2.5 py-1 rounded-full border border-transparent cursor-pointer {{ $volunteer->status_badge_class }}">
                                                    <option value="pending" {{ $volunteer->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="reviewed" {{ $volunteer->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                                    <option value="contacted" {{ $volunteer->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                </select>
                                            </form>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <form method="POST" 
                                                  action="{{ route('admin.inquiries.volunteers.destroy', $volunteer) }}" 
                                                  onsubmit="return confirm('Permanently delete application for {{ addslashes($volunteer->full_name) }}?');" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer" 
                                                        title="Delete application">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($volunteers->hasPages())
                        <div class="px-6 py-4 border-t border-stone-100">
                            {{ $volunteers->links() }}
                        </div>
                    @endif
                @endif
            </div>

        {{-- TAB CONTENT: PARTNERS --}}
        @else
            <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
                @if ($partners->isEmpty())
                    <div class="p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400 text-2xl">
                            🏢
                        </div>
                        <p class="text-stone-600 font-medium text-sm">No partner enquiries found.</p>
                        @if($search || $status !== 'all')
                            <p class="text-xs text-stone-400">Try clearing filters or search criteria.</p>
                        @else
                            <p class="text-xs text-stone-400">Proposals submitted from the public Get Involved page will appear here.</p>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-stone-700">
                            <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Organization & Contact</th>
                                    <th scope="col" class="px-6 py-3.5">Partnership Type</th>
                                    <th scope="col" class="px-6 py-3.5">Contact Details</th>
                                    <th scope="col" class="px-6 py-3.5">Proposal Message</th>
                                    <th scope="col" class="px-6 py-3.5">Submitted Date</th>
                                    <th scope="col" class="px-6 py-3.5">Status</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @foreach ($partners as $partner)
                                    <tr class="hover:bg-stone-50/50 transition-colors">
                                        {{-- Organization & Contact Name --}}
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-stone-900">{{ $partner->organization_name }}</div>
                                            <div class="text-xs text-stone-500">Rep: {{ $partner->contact_name }}</div>
                                        </td>

                                        {{-- Partnership Type --}}
                                        <td class="px-6 py-4 text-xs">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                                                {{ $partner->partnership_type_label }}
                                            </span>
                                        </td>

                                        {{-- Contact Details --}}
                                        <td class="px-6 py-4 text-xs">
                                            <div>
                                                <a href="mailto:{{ $partner->email }}" class="text-maroon hover:underline font-semibold">
                                                    {{ $partner->email }}
                                                </a>
                                            </div>
                                            <div class="text-stone-500 mt-0.5">
                                                @if($partner->phone)
                                                    <a href="tel:{{ $partner->phone }}" class="hover:text-stone-800">
                                                        {{ $partner->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-stone-400">No phone provided</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Message / Proposal --}}
                                        <td class="px-6 py-4 text-xs text-stone-600 max-w-xs">
                                            <p class="line-clamp-2" title="{{ $partner->message }}">{{ $partner->message }}</p>
                                        </td>

                                        {{-- Submitted Date --}}
                                        <td class="px-6 py-4 text-xs text-stone-500 whitespace-nowrap">
                                            {{ $partner->created_at?->format('M j, Y') ?? '—' }}
                                        </td>

                                        {{-- Status Selector Form --}}
                                        <td class="px-6 py-4 text-xs whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.inquiries.partners.status', $partner) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" 
                                                        onchange="this.form.submit()" 
                                                        class="text-xs font-semibold px-2.5 py-1 rounded-full border border-transparent cursor-pointer {{ $partner->status_badge_class }}">
                                                    <option value="pending" {{ $partner->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="reviewed" {{ $partner->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                                    <option value="contacted" {{ $partner->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                </select>
                                            </form>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <form method="POST" 
                                                  action="{{ route('admin.inquiries.partners.destroy', $partner) }}" 
                                                  onsubmit="return confirm('Permanently delete enquiry for {{ addslashes($partner->organization_name) }}?');" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer" 
                                                        title="Delete enquiry">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($partners->hasPages())
                        <div class="px-6 py-4 border-t border-stone-100">
                            {{ $partners->links() }}
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>
</x-admin-layout>
