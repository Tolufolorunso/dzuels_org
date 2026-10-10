<x-admin-layout title="News & Editorial Posts">
    <div class="space-y-6">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">News & Stories</h1>
                <p class="text-sm text-stone-500 mt-1">Publish foundation news, community impact stories, and literacy updates.</p>
            </div>
            <div>
                <a href="{{ route('admin.news.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Article
                </a>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
            @if ($posts->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        📰
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No news articles found.</p>
                    <a href="{{ route('admin.news.create') }}" class="text-xs text-maroon hover:underline font-semibold inline-block">
                        Write your first article
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-stone-700">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs font-bold uppercase tracking-wider text-stone-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Cover & Title</th>
                                <th scope="col" class="px-6 py-3.5">Slug</th>
                                <th scope="col" class="px-6 py-3.5">Programme</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5">Published Date</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-stone-50/75 transition-colors">
                                    {{-- Cover & Title --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-14 h-11 rounded-lg bg-stone-100 border border-stone-200 overflow-hidden shrink-0">
                                                <img src="{{ $post->cover_image_url }}" 
                                                     alt="{{ $post->title }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <span class="font-bold text-stone-900 block line-clamp-1">{{ $post->title }}</span>
                                                <div class="flex items-center gap-2 text-xs text-stone-500 mt-0.5">
                                                    <span>{{ $post->reading_time_minutes }} min read</span>
                                                    @if($post->excerpt)
                                                        <span>•</span>
                                                        <span class="line-clamp-1 max-w-xs">{{ $post->excerpt }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Slug --}}
                                    <td class="px-6 py-4 text-xs font-mono text-stone-600">
                                        <a href="{{ url('/news/'.$post->slug) }}" target="_blank" class="hover:text-maroon hover:underline">
                                            /news/{{ $post->slug }}
                                        </a>
                                    </td>

                                    {{-- Programme --}}
                                    <td class="px-6 py-4 text-xs text-stone-600">
                                        @if($post->programme)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                                {{ $post->programme->title }}
                                            </span>
                                        @else
                                            <span class="text-stone-400">General</span>
                                        @endif
                                    </td>

                                    {{-- Status Toggle --}}
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.news.toggle', $post) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors {{ $post->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-stone-100 text-stone-600 border border-stone-200 hover:bg-stone-200' }}"
                                                    title="Click to toggle publish status">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $post->status === 'published' ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                                {{ $post->status === 'published' ? 'Published' : 'Draft' }}
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Published Date --}}
                                    <td class="px-6 py-4 text-xs text-stone-500">
                                        {{ $post->published_at ? $post->published_at->format('M j, Y') : '—' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.news.edit', $post) }}" 
                                           class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold text-stone-600 hover:text-stone-900 bg-stone-100 hover:bg-stone-200 transition-colors">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.news.destroy', $post) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this news article?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 transition-colors cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($posts->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200">
                        {{ $posts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-admin-layout>
