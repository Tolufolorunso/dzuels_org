<x-admin-layout title="User & Staff Management">
    <div class="space-y-8">
        <!-- Top Heading & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-stone-200">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200 mb-2">
                    <span>👥</span> Access Control & Permissions
                </div>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Staff Accounts & Role Management</h1>
                <p class="text-sm text-stone-500 mt-1">Manage portal administrator accounts, editor permissions, and support credentials.</p>
            </div>

            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-stone-900 transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add New Staff Account</span>
            </a>
        </div>

        <!-- Users Table Card -->
        <div class="bg-white rounded-3xl border border-stone-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-stone-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-stone-900">Registered Portal Users ({{ $users->count() }})</h2>
                <span class="text-xs text-stone-400">Restricted to Super Administrators</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-stone-50/80 text-stone-500 font-bold uppercase tracking-wider border-b border-stone-200/60">
                            <th class="py-3.5 px-6">Staff Member</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Created</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 font-medium text-stone-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-stone-50/60 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-stone-900 text-sm">{{ $user->name }}</div>
                                    <div class="text-stone-400 text-xs">{{ $user->email }}</div>
                                    @if ($user->id === auth()->id())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 mt-1">
                                            Current Session (You)
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-4">
                                    @if ($user->role === 'super_admin' || $user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-rose-50 text-maroon border border-rose-200">
                                            Super Admin
                                        </span>
                                    @elseif ($user->role === 'editor')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                                            Content Editor
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-teal-50 text-teal-700 border border-teal-200">
                                            Support Staff
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-4">
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition-colors cursor-pointer {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-stone-100 text-stone-400 border border-stone-200 hover:bg-stone-200' }}"
                                                title="Click to toggle status"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                            <span>{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                        </button>
                                    </form>
                                </td>

                                <td class="py-4 px-4 text-stone-400">
                                    {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-stone-400 hover:text-stone-700 hover:bg-stone-100 rounded-xl transition-colors" title="Edit account">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this staff account?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Delete account">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
