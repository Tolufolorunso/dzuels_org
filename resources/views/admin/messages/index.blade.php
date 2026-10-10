<x-admin-layout title="Contact Messages">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Contact Messages</h1>
                <p class="text-sm text-stone-500 mt-1">
                    Manage incoming correspondence, general inquiries, and community feedback.
                </p>
            </div>
            <div>
                <a href="{{ route('contact') }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-stone-200 text-stone-700 hover:bg-stone-50 transition-colors shadow-2xs">
                    <span>View Public Contact Page</span>
                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Filter & Search Controls Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-3 rounded-2xl border border-stone-200 shadow-2xs">
            {{-- Status Filter Pill List --}}
            <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-xl text-xs">
                @foreach(['all' => 'All Messages', 'unread' => 'Unread', 'read' => 'Read', 'replied' => 'Replied'] as $sKey => $sLabel)
                    <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => $sKey])) }}"
                       class="px-3 py-1.5 rounded-lg font-medium transition-colors flex items-center gap-1.5 {{ $status === $sKey ? 'bg-white text-stone-900 font-bold shadow-2xs' : 'text-stone-500 hover:text-stone-900' }}">
                        <span>{{ $sLabel }}</span>
                        @if($sKey === 'unread' && $unreadCount > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-rose-500 text-white font-bold">
                                {{ $unreadCount }}
                            </span>
                        @elseif($sKey === 'all')
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-stone-200 text-stone-700">
                                {{ $totalCount }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Search Box --}}
            <form method="GET" action="{{ route('admin.messages.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ $search }}" 
                           placeholder="Search messages..." 
                           class="pl-8 pr-3 py-1.5 rounded-xl border border-stone-200 text-xs bg-stone-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-maroon w-48 sm:w-60">
                    <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                @if($search || $status !== 'all')
                    <a href="{{ route('admin.messages.index') }}" class="text-xs text-rose-600 hover:underline px-1">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Messages Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($messages->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400 text-2xl">
                        ✉️
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No contact messages found.</p>
                    @if($search || $status !== 'all')
                        <p class="text-xs text-stone-400">Try clearing filters or search keywords.</p>
                    @else
                        <p class="text-xs text-stone-400">Messages sent via the public contact form will appear here.</p>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Sender Details</th>
                                <th scope="col" class="px-6 py-3.5">Subject</th>
                                <th scope="col" class="px-6 py-3.5">Message Preview</th>
                                <th scope="col" class="px-6 py-3.5">Received Date</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($messages as $msg)
                                <tr class="hover:bg-stone-50/50 transition-colors {{ $msg->status === 'unread' ? 'bg-amber-50/30' : '' }}">
                                    {{-- Sender Details --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900 flex items-center gap-2">
                                            <span>{{ $msg->full_name }}</span>
                                            @if($msg->status === 'unread')
                                                <span class="inline-block w-2 h-2 rounded-full bg-rose-500" title="New unread message"></span>
                                            @endif
                                        </div>
                                        <div class="text-xs mt-0.5">
                                            <a href="mailto:{{ $msg->email }}" class="text-maroon hover:underline font-semibold">
                                                {{ $msg->email }}
                                            </a>
                                        </div>
                                        @if($msg->phone)
                                            <div class="text-xs text-stone-500 mt-0.5">
                                                <a href="tel:{{ $msg->phone }}" class="hover:text-stone-800">
                                                    {{ $msg->phone }}
                                                </a>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Subject --}}
                                    <td class="px-6 py-4 text-xs font-semibold text-stone-800">
                                        {{ $msg->subject }}
                                    </td>

                                    {{-- Message Preview --}}
                                    <td class="px-6 py-4 text-xs text-stone-600 max-w-sm">
                                        <p class="line-clamp-3" title="{{ $msg->message }}">{{ $msg->message }}</p>
                                    </td>

                                    {{-- Received Date --}}
                                    <td class="px-6 py-4 text-xs text-stone-500 whitespace-nowrap">
                                        {{ $msg->created_at?->format('M j, Y g:i A') ?? '—' }}
                                    </td>

                                    {{-- Status Selector Form --}}
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.messages.status', $msg) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" 
                                                    onchange="this.form.submit()" 
                                                    class="text-xs font-semibold px-2.5 py-1 rounded-full border border-transparent cursor-pointer {{ $msg->status_badge_class }}">
                                                <option value="unread" {{ $msg->status === 'unread' ? 'selected' : '' }}>Unread</option>
                                                <option value="read" {{ $msg->status === 'read' ? 'selected' : '' }}>Read</option>
                                                <option value="replied" {{ $msg->status === 'replied' ? 'selected' : '' }}>Replied</option>
                                            </select>
                                        </form>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="mailto:{{ $msg->email }}?subject={{ urlencode('Re: ' . $msg->subject) }}" 
                                           class="p-1.5 text-stone-400 hover:text-maroon rounded-lg hover:bg-stone-100 transition-colors inline-block" 
                                           title="Reply via email">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                        </a>

                                        <form method="POST" 
                                              action="{{ route('admin.messages.destroy', $msg) }}" 
                                              onsubmit="return confirm('Permanently delete message from {{ addslashes($msg->full_name) }}?');" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer" 
                                                    title="Delete message">
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

                @if ($messages->hasPages())
                    <div class="px-6 py-4 border-t border-stone-100">
                        {{ $messages->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-admin-layout>
