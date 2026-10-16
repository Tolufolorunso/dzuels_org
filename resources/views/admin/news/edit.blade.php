<x-admin-layout title="Edit News Article">
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header Bar --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Edit News Article</h1>
                <p class="text-sm text-stone-500 mt-1">Update details, cover image, or publication status for this story.</p>
            </div>
            <a href="{{ route('admin.news.index') }}" 
               class="text-xs font-semibold text-stone-600 hover:text-stone-900 transition-colors">
                ← Back to news
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.news.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Title & Slug Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                                Article Title <span class="text-rose-500">*</span>
                            </label>
                            <x-admin.ai-button 
                                target="#title" 
                                field-name="title" 
                                field-label="News Article Title" 
                                page-context="news" 
                                section-context="admin_news_edit" 
                                :sibling-targets="['#excerpt' => 'Summary', '#body' => 'Article Body']" 
                            />
                        </div>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               required
                               value="{{ old('title', $post->title) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border @error('title') border-rose-500 ring-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                        @error('title')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            URL Slug
                        </label>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', $post->slug) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border @error('slug') border-rose-500 ring-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                        @error('slug')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Programme & Status & Date Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="programme_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Related Programme <span class="text-stone-400 font-normal">(Optional)</span>
                        </label>
                        <select name="programme_id" 
                                id="programme_id" 
                                class="w-full px-4 py-2.5 rounded-xl border @error('programme_id') border-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm bg-white">
                            <option value="">-- General Foundation Story --</option>
                            @foreach ($programmes as $programme)
                                <option value="{{ $programme->id }}" {{ old('programme_id', $post->programme_id) == $programme->id ? 'selected' : '' }}>
                                    {{ $programme->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('programme_id')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Publication Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                required
                                class="w-full px-4 py-2.5 rounded-xl border @error('status') border-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm bg-white">
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published (Public)</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="published_at" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Publish Date
                        </label>
                        <input type="datetime-local" 
                               name="published_at" 
                               id="published_at" 
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}" 
                               class="w-full px-4 py-2.5 rounded-xl border @error('published_at') border-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm bg-white">
                        @error('published_at')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Excerpt --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="excerpt" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                            Summary / Excerpt
                        </label>
                        <x-admin.ai-button 
                            target="#excerpt" 
                            field-name="excerpt" 
                            field-label="Article Summary / Excerpt" 
                            page-context="news" 
                            section-context="admin_news_edit" 
                            :sibling-targets="['#title' => 'Article Title', '#body' => 'Article Body']" 
                        />
                    </div>
                    <textarea name="excerpt" 
                              id="excerpt" 
                              rows="2" 
                              class="w-full px-4 py-2.5 rounded-xl border @error('excerpt') border-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Article Body --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="body" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                            Article Body <span class="text-rose-500">*</span>
                        </label>
                        <x-admin.ai-button 
                            target="#body" 
                            field-name="body" 
                            field-label="News Article Body Narrative" 
                            page-context="news" 
                            section-context="admin_news_edit" 
                            :sibling-targets="['#title' => 'Article Title', '#excerpt' => 'Summary']" 
                        />
                    </div>
                    <textarea name="body" 
                              id="body" 
                              rows="12" 
                              required
                              class="w-full px-4 py-2.5 rounded-xl border @error('body') border-rose-500 @else border-stone-300 @enderror focus:border-maroon focus:ring-1 focus:ring-maroon text-sm font-sans leading-relaxed">{{ old('body', $post->body) }}</textarea>
                    @error('body')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cover Image --}}
                <div>
                    <x-admin.media-picker 
                        name="cover_image" 
                        :value="old('cover_image', $post->cover_image)" 
                        label="Cover Photo" 
                        help="Choose from existing gallery albums or upload and auto-optimize a new image (converted to lightweight WebP <= 180 KB)." />
                    @error('cover_image')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SEO Fields Section --}}
                <div class="border-t border-stone-200 pt-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900">Search Engine Optimization (SEO)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="seo_title" class="block text-xs font-semibold text-stone-600">
                                    Meta Title
                                </label>
                                <x-admin.ai-button 
                                    target="#seo_title" 
                                    field-name="seo_title" 
                                    field-label="SEO Meta Title" 
                                    page-context="news" 
                                    section-context="admin_news_edit" 
                                    :sibling-targets="['#title' => 'Article Title', '#excerpt' => 'Summary']" 
                                />
                            </div>
                            <input type="text" 
                                   name="seo_title" 
                                   id="seo_title" 
                                   value="{{ old('seo_title', $post->seo_title) }}" 
                                   class="w-full px-4 py-2 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="seo_description" class="block text-xs font-semibold text-stone-600">
                                    Meta Description
                                </label>
                                <x-admin.ai-button 
                                    target="#seo_description" 
                                    field-name="seo_description" 
                                    field-label="SEO Meta Description" 
                                    page-context="news" 
                                    section-context="admin_news_edit" 
                                    :sibling-targets="['#title' => 'Article Title', '#excerpt' => 'Summary']" 
                                />
                            </div>
                            <input type="text" 
                                   name="seo_description" 
                                   id="seo_description" 
                                   value="{{ old('seo_description', $post->seo_description) }}" 
                                   class="w-full px-4 py-2 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200">
                    <a href="{{ route('admin.news.index') }}" 
                       class="px-5 py-2.5 rounded-xl text-xs font-semibold text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                        Update Article
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
