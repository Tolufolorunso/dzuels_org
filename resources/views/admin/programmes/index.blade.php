<x-admin-layout title="Programmes">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Foundation Programmes</h1>
                <p class="text-sm text-stone-500 mt-1">Manage public initiatives, educational literacy projects, and event showcases.</p>
            </div>
            <div>
                <a href="{{ route('admin.programmes.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Programme
                </a>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($programmes->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        📚
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No programmes found.</p>
                    <a href="{{ route('admin.programmes.create') }}" class="text-xs text-maroon hover:underline font-semibold inline-block">
                        Create the first programme
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Cover & Title</th>
                                <th scope="col" class="px-6 py-3.5">Slug</th>
                                <th scope="col" class="px-6 py-3.5">Partner</th>
                                <th scope="col" class="px-6 py-3.5">Featured</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Order</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach ($programmes as $programme)
                                <tr class="hover:bg-stone-50/75 transition-colors">
                                    {{-- Cover & Title --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-14 h-11 rounded-lg bg-stone-100 border border-stone-200 overflow-hidden shrink-0">
                                                <img src="{{ $programme->cover_image_url }}" 
                                                     alt="{{ $programme->title }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <span class="font-bold text-stone-900 block line-clamp-1">{{ $programme->title }}</span>
                                                <p class="text-xs text-stone-500 line-clamp-1 mt-0.5">{{ $programme->summary }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Slug --}}
                                    <td class="px-6 py-4 text-xs font-mono text-stone-600">
                                        /programmes/{{ $programme->slug }}
                                    </td>

                                    {{-- Partner --}}
                                    <td class="px-6 py-4 text-xs text-stone-600">
                                        @if($programme->partner_name)
                                            <span class="inline-flex items-center gap-1 font-medium text-stone-800">
                                                🤝 {{ $programme->partner_name }}
                                            </span>
                                        @else
                                            <span class="text-stone-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Featured --}}
                                    <td class="px-6 py-4">
                                        @if ($programme->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                ★ Featured
                                            </span>
                                        @else
                                            <span class="text-xs text-stone-400">Regular</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.programmes.toggle', $programme) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors {{ $programme->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-stone-100 text-stone-600 border border-stone-200 hover:bg-stone-200' }}"
                                                    title="Click to toggle active status">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $programme->is_active ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                                {{ $programme->is_active ? 'Active' : 'Hidden' }}
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Order --}}
                                    <td class="px-6 py-4 text-xs font-mono text-center text-stone-600">
                                        {{ $programme->sort_order }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- View Live --}}
                                            <a href="{{ url('/programmes/' . $programme->slug) }}" 
                                               target="_blank" 
                                               class="p-1.5 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition-colors"
                                               title="View live public page">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.programmes.edit', $programme) }}" 
                                               class="p-1.5 text-stone-500 hover:text-maroon rounded-lg hover:bg-stone-100 transition-colors"
                                               title="Edit programme">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('admin.programmes.destroy', $programme) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete programme \'{{ addslashes($programme->title) }}\'?');" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer"
                                                        title="Delete programme">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
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
