<x-admin-layout title="Partners">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Partner Organizations</h1>
                <p class="text-sm text-stone-500 mt-1">Manage institutional partners, sponsors, and collaborator logos.</p>
            </div>
            <div>
                <a href="{{ route('admin.partners.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Partner
                </a>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($partners->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        🤝
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No partner organizations added yet.</p>
                    <a href="{{ route('admin.partners.create') }}" class="text-xs text-maroon hover:underline font-semibold inline-block">
                        Add a new partner
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Logo</th>
                                <th scope="col" class="px-6 py-3.5">Organization</th>
                                <th scope="col" class="px-6 py-3.5">Website</th>
                                <th scope="col" class="px-6 py-3.5">Order</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($partners as $partner)
                                <tr class="hover:bg-stone-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="h-10 w-20 bg-stone-100 rounded-lg p-1.5 flex items-center justify-center border border-stone-200">
                                            <img src="{{ $partner->logo_url }}" 
                                                 alt="{{ $partner->name }}" 
                                                 class="max-h-full max-w-full object-contain" />
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-stone-900">
                                        {{ $partner->name }}
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        @if ($partner->website_url)
                                            <a href="{{ $partner->website_url }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                                <span>{{ parse_url($partner->website_url, PHP_URL_HOST) ?? $partner->website_url }}</span>
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @else
                                            <span class="text-stone-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-stone-500">
                                        {{ $partner->sort_order }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.partners.toggle', $partner) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium cursor-pointer transition-colors {{ $partner->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-stone-200 text-stone-600 hover:bg-stone-300' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $partner->is_active ? 'bg-emerald-600' : 'bg-stone-400' }}"></span>
                                                {{ $partner->is_active ? 'Active' : 'Hidden' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.partners.edit', $partner) }}" 
                                           class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-stone-100 text-stone-700 hover:bg-stone-200 transition-colors">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this partner?');">
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
