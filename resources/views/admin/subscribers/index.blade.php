<x-admin-layout title="Newsletter Subscribers">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Newsletter Subscribers</h1>
                <p class="text-sm text-stone-500 mt-1">
                    Manage email subscriptions, mailing lists, and unsubscription preferences.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.subscribers.export') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-sm hover:shadow cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        {{-- Filter & Search Controls Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-3 rounded-2xl border border-stone-200 shadow-2xs">
            {{-- Status Filter Pill List --}}
            <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-xl text-xs">
                @foreach(['all' => 'All Subscribers', 'active' => 'Active', 'unsubscribed' => 'Unsubscribed'] as $fKey => $fLabel)
                    <a href="{{ route('admin.subscribers.index', array_merge(request()->query(), ['filter' => $fKey])) }}"
                       class="px-3 py-1.5 rounded-lg font-medium transition-colors flex items-center gap-1.5 {{ $filter === $fKey ? 'bg-white text-stone-900 font-bold shadow-2xs' : 'text-stone-500 hover:text-stone-900' }}">
                        <span>{{ $fLabel }}</span>
                        @if($fKey === 'active')
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-bold">
                                {{ $activeCount }}
                            </span>
                        @elseif($fKey === 'unsubscribed')
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-stone-200 text-stone-700">
                                {{ $unsubscribedCount }}
                            </span>
                        @else
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-stone-200 text-stone-700">
                                {{ $totalCount }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Search Box --}}
            <form method="GET" action="{{ route('admin.subscribers.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search subscribers..." 
                           class="pl-8 pr-3 py-1.5 rounded-xl border border-stone-200 text-xs bg-stone-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-maroon w-48 sm:w-60">
                    <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                @if(request('search') || $filter !== 'all')
                    <a href="{{ route('admin.subscribers.index') }}" class="text-xs text-rose-600 hover:underline px-1">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($subscribers->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        📬
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No subscribers found matching your criteria.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Email Address</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5">Subscribed Date</th>
                                <th scope="col" class="px-6 py-3.5">Unsubscribed Date</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach ($subscribers as $subscriber)
                                <tr class="hover:bg-stone-50/75 transition-colors">
                                    {{-- Email Address --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-stone-900">{{ $subscriber->email }}</span>
                                        </div>
                                    </td>

                                    {{-- Status Pill --}}
                                    <td class="px-6 py-4">
                                        @if ($subscriber->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-600 border border-stone-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                                Unsubscribed
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Subscribed Date --}}
                                    <td class="px-6 py-4 text-xs text-stone-500">
                                        {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('M j, Y g:i A') : $subscriber->created_at->format('M j, Y') }}
                                    </td>

                                    {{-- Unsubscribed Date --}}
                                    <td class="px-6 py-4 text-xs text-stone-500">
                                        {{ $subscriber->unsubscribed_at ? $subscriber->unsubscribed_at->format('M j, Y g:i A') : '—' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber) }}" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this subscriber?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 transition-colors cursor-pointer"
                                                    title="Delete subscriber">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($subscribers->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200">
                        {{ $subscribers->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-admin-layout>
