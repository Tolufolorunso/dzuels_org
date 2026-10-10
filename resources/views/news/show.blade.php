<x-layout>
    <x-slot:title>{{ $post->seo_title ?: $post->title . ' | Dzuels Educational Foundation' }}</x-slot:title>
    <x-slot:description>{{ $post->seo_description ?: ($post->excerpt ?: Str::limit(strip_tags($post->body), 155)) }}</x-slot:description>

    {{-- 1. Article Header & Breadcrumbs --}}
    <article class="py-12 sm:py-16 bg-[var(--bg)] transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-[var(--text-muted)] mb-8" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-maroon transition-colors flex items-center gap-1 focus:outline-none focus:underline">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <span class="text-stone-300 dark:text-stone-600" aria-hidden="true">/</span>
                <a href="{{ route('news.index') }}" class="hover:text-maroon transition-colors focus:outline-none focus:underline">
                    News
                </a>
                <span class="text-stone-300 dark:text-stone-600" aria-hidden="true">/</span>
                <span class="text-[var(--text-strong)] font-semibold truncate max-w-xs" aria-current="page">{{ $post->title }}</span>
            </nav>

            {{-- Metadata Pill & Category --}}
            <div class="flex flex-wrap items-center gap-3 mb-6">
                @if ($post->programme)
                    <a href="{{ route('programmes.show', $post->programme->slug) }}" class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 text-maroon hover:bg-maroon hover:text-white transition-colors">
                        {{ $post->programme->title }}
                    </a>
                @else
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-stone-100 text-stone-700 dark:bg-stone-800 dark:text-stone-300">
                        Foundation News
                    </span>
                @endif

                <span class="text-xs text-[var(--text-muted)] flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $post->published_at ? $post->published_at->format('F j, Y') : $post->created_at->format('F j, Y') }}
                </span>

                <span class="text-xs text-[var(--text-muted)]">•</span>

                <span class="text-xs text-[var(--text-muted)] flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $post->reading_time_minutes }} min read
                </span>
            </div>

            {{-- Article Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[var(--heading)] tracking-tight leading-tight mb-8">
                {{ $post->title }}
            </h1>

            {{-- Excerpt Callout --}}
            @if ($post->excerpt)
                <p class="text-lg sm:text-xl text-[var(--text)] font-medium leading-relaxed italic border-l-4 border-maroon pl-6 py-1 mb-10 bg-[var(--surface)]/50 rounded-r-2xl">
                    {{ $post->excerpt }}
                </p>
            @endif

            {{-- Large Cover Photo --}}
            <div class="mb-12 rounded-3xl overflow-hidden shadow-xl border border-[var(--border)] bg-stone-100">
                <img src="{{ $post->cover_image_url }}" 
                     alt="{{ $post->title }}" 
                     class="w-full max-h-[520px] object-cover object-center">
            </div>

            {{-- Social Share Toolbar --}}
            <div class="py-5 px-6 mb-12 rounded-2xl bg-[var(--surface)] border border-[var(--border)] flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs" x-data="{ copied: false }">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                    <svg class="w-4 h-4 text-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    <span>Share This Story:</span>
                </div>

                <div class="flex items-center gap-2">
                    {{-- WhatsApp Share --}}
                    <a href="https://api.whatsapp.com/send?text={{ rawurlencode($post->title . ' - ' . url()->current()) }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors border border-emerald-200"
                       title="Share on WhatsApp">
                        <span>WhatsApp</span>
                    </a>

                    {{-- X / Twitter Share --}}
                    <a href="https://twitter.com/intent/tweet?text={{ rawurlencode($post->title) }}&url={{ rawurlencode(url()->current()) }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 hover:bg-black hover:text-white transition-colors border border-stone-200 dark:bg-stone-800 dark:text-stone-200"
                       title="Share on X">
                        <span>X (Twitter)</span>
                    </a>

                    {{-- Facebook Share --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode(url()->current()) }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white transition-colors border border-blue-200"
                       title="Share on Facebook">
                        <span>Facebook</span>
                    </a>

                    {{-- Copy Link Button with Alpine Feedback --}}
                    <div class="relative inline-block">
                        <button type="button" 
                                @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[var(--accent-cream-light)] text-maroon hover:bg-maroon hover:text-white transition-colors border border-[var(--border)] cursor-pointer"
                                title="Copy link to clipboard">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Copy Link</span>
                        </button>

                        <span x-show="copied" 
                              x-cloak 
                              x-transition
                              class="absolute -top-9 left-1/2 -translate-x-1/2 px-2.5 py-1 rounded-md text-[11px] font-bold bg-stone-900 text-white shadow-lg pointer-events-none whitespace-nowrap">
                            Copied!
                        </span>
                    </div>
                </div>
            </div>

            {{-- Article Body Content --}}
            <div class="article-content text-[var(--text)] text-base sm:text-lg leading-relaxed space-y-6">
                {!! nl2br(e($post->body)) !!}
            </div>

            {{-- Bottom Back Link & Org Signature --}}
            <div class="mt-14 pt-8 border-t border-[var(--border)] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-maroon dark:text-[var(--accent-cream)] hover:underline">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to all news</span>
                </a>

                <div class="text-xs text-[var(--text-muted)]">
                    Published by <strong class="text-[var(--heading)]">Dzuels Educational Foundation</strong>
                </div>
            </div>
        </div>
    </article>

    {{-- 2. Related Articles Section --}}
    @if ($relatedPosts->isNotEmpty())
        <section class="py-16 sm:py-20 bg-[var(--bg-alt)] border-t border-[var(--border)] transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-crimson dark:text-[var(--accent-cream)]">
                            More From Our Foundation
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] mt-1">
                            Related Articles & Stories
                        </h2>
                    </div>
                    <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-maroon dark:text-[var(--accent-cream)] hover:underline">
                        <span>View All News</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($relatedPosts as $related)
                        <article class="flex flex-col bg-[var(--surface)] border border-[var(--border)] rounded-3xl overflow-hidden shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                            <a href="{{ route('news.show', $related->slug) }}" class="relative aspect-16/10 overflow-hidden bg-stone-100 block">
                                <img src="{{ $related->cover_image_url }}" 
                                     alt="{{ $related->title }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </a>

                            <div class="p-6 flex flex-col flex-grow">
                                <div class="text-xs text-[var(--text-muted)] mb-2 flex items-center gap-2">
                                    <span>{{ $related->published_at ? $related->published_at->format('M j, Y') : $related->created_at->format('M j, Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $related->reading_time_minutes }} min read</span>
                                </div>

                                <h3 class="text-base font-bold text-[var(--heading)] group-hover:text-maroon transition-colors line-clamp-2 leading-snug mb-3">
                                    <a href="{{ route('news.show', $related->slug) }}">
                                        {{ $related->title }}
                                    </a>
                                </h3>

                                <div class="mt-auto pt-3 border-t border-[var(--border-subtle)] flex items-center justify-between text-xs">
                                    <a href="{{ route('news.show', $related->slug) }}" class="font-semibold text-maroon dark:text-[var(--accent-cream)] hover:underline">
                                        Read Article &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layout>
