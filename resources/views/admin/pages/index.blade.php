<x-admin-layout title="Site Content Management">
    <div class="space-y-8">
        <!-- Top Heading & Overview -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-stone-200">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 mb-2">
                    <span>📝</span> WordPress-Style Visual CMS
                </div>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Site Pages & Content Management</h1>
                <p class="text-sm text-stone-500 mt-1">Update headlines, body copy, vision, mission, and section images across all public pages.</p>
            </div>
            
            <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-stone-900 text-white hover:bg-stone-800 transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Preview Website</span>
            </a>
        </div>

        <!-- Pages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($pages as $page)
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-stone-200/80 shadow-xs hover:shadow-md hover:border-maroon/20 transition-all flex flex-col justify-between space-y-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-stone-100 text-stone-700">
                                {{ $page['sections_count'] }} {{ Str::plural('Section', $page['sections_count']) }}
                            </span>
                            @if ($page['last_updated'])
                                <span class="text-[11px] text-stone-400">
                                    Updated {{ $page['last_updated']->diffForHumans() }}
                                </span>
                            @endif
                        </div>

                        <h2 class="text-lg font-bold text-stone-900 tracking-tight">
                            {{ $page['title'] }}
                        </h2>

                        <p class="text-xs text-stone-500 leading-relaxed">
                            {{ $page['description'] }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-stone-100 flex items-center justify-between gap-3">
                        <a href="{{ route('admin.pages.edit', $page['key']) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-stone-900 transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit Content</span>
                        </a>

                        <a href="{{ url($page['public_url']) }}" target="_blank" class="p-2.5 text-stone-400 hover:text-stone-700 hover:bg-stone-100 rounded-xl transition-colors" title="View live page">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-admin-layout>
