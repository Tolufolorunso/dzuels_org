<x-admin-layout title="Team Members">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Team Members</h1>
                <p class="text-sm text-stone-500 mt-1">Manage Board of Trustees, Management, and Advisory Board profiles.</p>
            </div>
            <div>
                <a href="{{ route('admin.team.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Team Member
                </a>
            </div>
        </div>

        {{-- Filter Pills --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-stone-200 pb-4">
            <a href="{{ route('admin.team.index') }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ empty($currentGroup) ? 'bg-maroon text-white shadow-xs' : 'bg-stone-200/70 text-stone-700 hover:bg-stone-200' }}">
                All ({{ \App\Models\TeamMember::count() }})
            </a>
            <a href="{{ route('admin.team.index', ['group' => 'trustee']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $currentGroup === 'trustee' ? 'bg-maroon text-white shadow-xs' : 'bg-stone-200/70 text-stone-700 hover:bg-stone-200' }}">
                Trustees ({{ \App\Models\TeamMember::where('group', 'trustee')->count() }})
            </a>
            <a href="{{ route('admin.team.index', ['group' => 'management']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $currentGroup === 'management' ? 'bg-maroon text-white shadow-xs' : 'bg-stone-200/70 text-stone-700 hover:bg-stone-200' }}">
                Management ({{ \App\Models\TeamMember::where('group', 'management')->count() }})
            </a>
            <a href="{{ route('admin.team.index', ['group' => 'advisor']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $currentGroup === 'advisor' ? 'bg-maroon text-white shadow-xs' : 'bg-stone-200/70 text-stone-700 hover:bg-stone-200' }}">
                Advisory Board ({{ \App\Models\TeamMember::where('group', 'advisor')->count() }})
            </a>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($members->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        👥
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No team members found in this category.</p>
                    <a href="{{ route('admin.team.create') }}" class="text-xs text-maroon hover:underline font-semibold inline-block">
                        Add a new team member
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Member</th>
                                <th scope="col" class="px-6 py-3.5">Group</th>
                                <th scope="col" class="px-6 py-3.5">Domain / Field</th>
                                <th scope="col" class="px-6 py-3.5">Order</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($members as $member)
                                <tr class="hover:bg-stone-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($member->photo_url)
                                                <img src="{{ $member->photo_url }}" 
                                                     alt="{{ $member->name }}" 
                                                     class="w-10 h-10 rounded-full object-cover border border-stone-200 shrink-0" />
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-[var(--accent-cream)] text-maroon font-bold flex items-center justify-center text-sm shrink-0">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-stone-900">{{ $member->name }}</div>
                                                <div class="text-xs text-stone-500">{{ $member->role }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($member->group === 'trustee')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                Trustee
                                            </span>
                                        @elseif ($member->group === 'management')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                Management
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                Advisor
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-stone-600">
                                        {{ $member->field ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-stone-500">
                                        {{ $member->sort_order }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.team.toggle', $member) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium cursor-pointer transition-colors {{ $member->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-stone-200 text-stone-600 hover:bg-stone-300' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $member->is_active ? 'bg-emerald-600' : 'bg-stone-400' }}"></span>
                                                {{ $member->is_active ? 'Active' : 'Hidden' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        @if ($member->linkedin_url)
                                            <a href="{{ $member->linkedin_url }}" target="_blank" class="text-stone-400 hover:text-blue-600 inline-block p-1" title="LinkedIn Profile">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.team.edit', $member) }}" 
                                           class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-stone-100 text-stone-700 hover:bg-stone-200 transition-colors">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.team.destroy', $member) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this team member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
