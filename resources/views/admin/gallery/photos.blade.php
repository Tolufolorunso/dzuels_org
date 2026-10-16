<x-admin-layout title="Manage Photos - {{ $album->title }}">
    <div x-data="{
        selectedPhotos: [],
        selectAll: false,
        editingPhoto: null,
        uploadFilesCount: 0,
        handleFileSelect(e) {
            this.uploadFilesCount = e.target.files.length;
        },
        toggleAll() {
            if (this.selectAll) {
                this.selectedPhotos = {{ json_encode($photos->pluck('id')->values()) }};
            } else {
                this.selectedPhotos = [];
            }
        },
        openEdit(photo) {
            this.editingPhoto = { ...photo };
        },
        closeEdit() {
            this.editingPhoto = null;
        }
    }" class="space-y-8">
        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.gallery.index') }}" class="text-xs font-semibold text-stone-500 hover:text-stone-900 flex items-center gap-1.5 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Collections
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-stone-900">{{ $album->title }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                        {{ $photos->count() }} {{ Str::plural('photo', $photos->count()) }}
                    </span>
                </div>
                <p class="text-sm text-stone-500 mt-1">
                    {{ $album->kicker ? $album->kicker . ' • ' : '' }}Manage, upload, edit labels, and organize photos in this collection.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('gallery.album', $album->slug) }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-stone-100 text-stone-700 hover:bg-stone-200 transition-colors border border-stone-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    View Public
                </a>
                <a href="{{ route('admin.gallery.edit', $album) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                    Edit Collection Details
                </a>
            </div>
        </div>

        {{-- Upload New Photos Card (Supports Multi-Photo Bulk Upload) --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-6">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-base font-bold text-stone-900">Upload & Optimize Photos (Bulk Uploader)</h2>
                <span class="text-xs font-medium px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg">
                    ⚡ Resilient Auto-WebP Pipeline
                </span>
            </div>
            <p class="text-xs text-stone-500 mb-4">
                Select one or multiple photos to upload at once. Files are automatically optimized to modern WebP with aspect ratio preservation and zero-crash storage fallback.
            </p>

            <form method="POST" action="{{ route('admin.gallery.photos.upload', $album) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Files Input --}}
                    <div>
                        <label for="photos" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Select Image(s) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" 
                               id="photos" 
                               name="photos[]" 
                               multiple 
                               required 
                               accept="image/*" 
                               @change="handleFileSelect($event)"
                               class="w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 cursor-pointer">
                        <template x-if="uploadFilesCount > 0">
                            <p class="text-xs font-semibold text-emerald-700 mt-1.5 flex items-center gap-1">
                                <span>✓</span>
                                <span x-text="uploadFilesCount + ' file(s) selected for bulk upload'"></span>
                            </p>
                        </template>
                        @error('photos')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Default Caption --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="caption" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                                Caption <span class="text-stone-400 font-normal lowercase">(optional)</span>
                            </label>
                            <x-admin.ai-button 
                                for="caption" 
                                field-name="caption" 
                                field-label="Photo Caption" 
                                page-context="Gallery Album: {{ $album->title }}" 
                                section-context="{{ $album->title }} ({{ $album->kicker ?? 'Programme' }})" 
                            />
                        </div>
                        <input type="text" 
                               id="caption" 
                               name="caption" 
                               placeholder="e.g. Students in coding session" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors">
                    </div>

                    {{-- Year --}}
                    <div>
                        <label for="year" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Event / Activity Year <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="year" 
                               name="year" 
                               placeholder="e.g. 2024, 2025" 
                               value="{{ date('Y') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors">
                    </div>

                    {{-- Default Alt Text & Submit --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="alt_text" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                                Alt Text <span class="text-stone-400 font-normal lowercase">(optional)</span>
                            </label>
                            <x-admin.ai-button 
                                for="alt_text" 
                                field-name="alt_text" 
                                field-label="Image Alt Text" 
                                page-context="Gallery Album: {{ $album->title }}" 
                                section-context="{{ $album->title }}" 
                                :sibling-selectors="['caption' => '#caption']" 
                            />
                        </div>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="alt_text" 
                                   name="alt_text" 
                                   placeholder="Defaults to collection title" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-colors">
                            <button type="submit" 
                                    class="shrink-0 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm hover:shadow cursor-pointer">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Photos Grid and Bulk Actions --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-stone-200">
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-stone-700">
                        <input type="checkbox" 
                               x-model="selectAll" 
                               @change="toggleAll()" 
                               class="rounded text-maroon focus:ring-maroon/20 border-stone-300 w-4 h-4">
                        <span>Select All (<span x-text="{{ $photos->count() }}"></span>)</span>
                    </label>
                    <span class="text-xs text-stone-400">•</span>
                    <span class="text-xs text-stone-500">
                        <span x-text="selectedPhotos.length" class="font-bold text-stone-900">0</span> selected
                    </span>
                </div>

                {{-- Bulk Delete Form --}}
                <div x-show="selectedPhotos.length > 0" x-cloak class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.gallery.photos.bulk-destroy', $album) }}" 
                          @submit.prevent="if(confirm('Are you sure you want to delete ' + selectedPhotos.length + ' selected photo(s)? This cannot be undone.')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedPhotos" :key="id">
                            <input type="hidden" name="photo_ids[]" :value="id">
                        </template>
                        <button type="submit" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-xs cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Selected (<span x-text="selectedPhotos.length"></span>)
                        </button>
                    </form>
                </div>
            </div>

            @if ($photos->isEmpty())
                <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400">
                        📸
                    </div>
                    <p class="text-stone-600 font-medium text-sm">No photos in this album yet.</p>
                    <p class="text-xs text-stone-400">Use the form above to batch upload images to this collection.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($photos as $photo)
                        <div class="bg-white rounded-xl border border-stone-200 overflow-hidden shadow-xs hover:shadow transition-shadow group flex flex-col justify-between relative"
                             :class="{ 'ring-2 ring-maroon border-maroon': selectedPhotos.includes({{ $photo->id }}) }">
                            
                            {{-- Checkbox overlay --}}
                            <div class="absolute top-2 left-2 z-10 bg-white/90 backdrop-blur-xs rounded-md p-1 shadow-xs">
                                <input type="checkbox" 
                                       value="{{ $photo->id }}" 
                                       x-model="selectedPhotos" 
                                       class="rounded text-maroon focus:ring-maroon/20 border-stone-300 w-4 h-4 cursor-pointer">
                            </div>

                            <div class="relative aspect-4/3 bg-stone-100 overflow-hidden">
                                <img src="{{ $photo->image_url }}" 
                                     alt="{{ $photo->alt_text ?? $album->title }}" 
                                     loading="lazy" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                
                                {{-- Year badge --}}
                                @if ($photo->year)
                                    <div class="absolute top-2 right-2 bg-maroon/90 backdrop-blur-xs text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-xs">
                                        {{ $photo->year }}
                                    </div>
                                @endif
                                
                                <div class="absolute bottom-1 left-2 text-white/90 text-[10px] font-mono drop-shadow">
                                    #{{ $loop->iteration }}
                                </div>
                            </div>

                            <div class="p-3 flex flex-col justify-between gap-2 border-t border-stone-100 bg-stone-50/50">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-stone-800 truncate" title="{{ $photo->display_label }}">
                                        {{ $photo->display_label }}
                                    </p>
                                    <p class="text-[10px] text-stone-400 truncate">
                                        {{ basename($photo->image_path) }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between pt-1 border-t border-stone-200/60">
                                    {{-- Edit Button --}}
                                    <button type="button" 
                                            @click="openEdit({{ json_encode([
                                                'id' => $photo->id,
                                                'caption' => $photo->caption,
                                                'alt_text' => $photo->alt_text,
                                                'year' => $photo->year,
                                                'sort_order' => $photo->sort_order,
                                                'image_url' => $photo->image_url,
                                                'update_url' => route('admin.gallery.photos.update', [$album, $photo]),
                                            ]) }})"
                                            class="text-[11px] font-semibold text-stone-600 hover:text-maroon transition-colors cursor-pointer flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    {{-- Single Delete Form --}}
                                    <form method="POST" action="{{ route('admin.gallery.photos.destroy', [$album, $photo]) }}" 
                                          onsubmit="return confirm('Delete this photo from the album?');" 
                                          class="shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1 text-stone-400 hover:text-rose-600 rounded hover:bg-rose-50 transition-colors cursor-pointer" 
                                                title="Delete photo">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Edit Photo Modal --}}
        <div x-show="editingPhoto !== null" 
             x-cloak 
             @keydown.escape.window="closeEdit()" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            
            <div @click.away="closeEdit()" 
                 class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-stone-200 space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900">Edit Photo Details</h3>
                    <button type="button" @click="closeEdit()" class="text-stone-400 hover:text-stone-600 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <template x-if="editingPhoto">
                    <form :action="editingPhoto.update_url" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-xl">
                            <img :src="editingPhoto.image_url" id="gallery_edit_photo_img" alt="" class="w-16 h-16 object-cover rounded-lg">
                            <div class="text-xs text-stone-500">
                                <p class="font-bold text-stone-800">Photo #<span x-text="editingPhoto.id"></span></p>
                                <p class="text-[11px] text-stone-400">Update label, event year, and sort order.</p>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="gallery_edit_photo_caption" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Caption</label>
                                <x-admin.ai-button 
                                    for="gallery_edit_photo_caption" 
                                    field-name="caption" 
                                    field-label="Photo Caption" 
                                    page-context="Gallery Album: {{ $album->title }}" 
                                    section-context="Photo in {{ $album->title }}" 
                                    image-selector="#gallery_edit_photo_img" 
                                />
                            </div>
                            <input type="text" id="gallery_edit_photo_caption" name="caption" x-model="editingPhoto.caption" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Event Year</label>
                                <input type="text" name="year" x-model="editingPhoto.year" placeholder="e.g. 2024" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Sort Order</label>
                                <input type="number" name="sort_order" x-model="editingPhoto.sort_order" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="gallery_edit_photo_alt" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Alt Text</label>
                                <x-admin.ai-button 
                                    for="gallery_edit_photo_alt" 
                                    field-name="alt_text" 
                                    field-label="Photo Alt Text" 
                                    page-context="Gallery Album: {{ $album->title }}" 
                                    section-context="Photo in {{ $album->title }}" 
                                    image-selector="#gallery_edit_photo_img" 
                                    :sibling-selectors="['caption' => '#gallery_edit_photo_caption']" 
                                />
                            </div>
                            <input type="text" id="gallery_edit_photo_alt" name="alt_text" x-model="editingPhoto.alt_text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-stone-100">
                            <button type="button" @click="closeEdit()" class="px-4 py-2 text-xs font-semibold text-stone-600 hover:text-stone-900 cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson rounded-xl shadow-xs cursor-pointer">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-admin-layout>
