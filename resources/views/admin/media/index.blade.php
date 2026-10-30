<x-admin-layout title="Central Media Library">
    <div x-data="{
        search: '{{ addslashes($currentSearch ?? '') }}',
        dateFilter: '{{ $currentDateFilter ?? 'all' }}',
        category: '{{ $currentCategory ?? 'all' }}',
        year: '{{ $currentYear ?? 'all' }}',
        visibility: '{{ $currentVisibility ?? 'all' }}',
        sort: '{{ $currentSort ?? 'newest' }}',
        perPage: '{{ $currentPerPage ?? 80 }}',
        zoomLevel: localStorage.getItem('dzuels_media_zoom') || 'normal',
        customDate: '{{ preg_match('/^\d{4}-\d{2}-\d{2}$/', $currentDateFilter ?? '') ? $currentDateFilter : '' }}',
        selectedItems: [],
        selectAll: false,
        showBulkAssignModal: false,
        showBulkDateModal: false,
        bulkAssignAlbumId: '',
        bulkDate: '',
        bulkYear: '',
        isPerformingBulk: false,
        isDragging: false,
        isUploading: false,
        uploadProgress: 0,
        uploadMessage: '',
        previewItem: null,
        inspectorItem: null,
        editingItem: null,
        copiedId: null,
        showNewCollectionModal: false,
        newCollection: {
            title: '',
            kicker: 'Foundation Programme',
            description: '',
            is_public: true,
            isSubmitting: false,
            errorMessage: ''
        },

        setZoom(level) {
            this.zoomLevel = level;
            localStorage.setItem('dzuels_media_zoom', level);
        },

        openInspector(item) {
            this.inspectorItem = item;
        },

        closeInspector() {
            this.inspectorItem = null;
        },

        async createCollection() {
            if (!this.newCollection.title.trim()) {
                this.newCollection.errorMessage = 'Please provide a collection title.';
                return;
            }
            this.newCollection.isSubmitting = true;
            this.newCollection.errorMessage = '';
            try {
                const response = await fetch('{{ route('admin.media.collections.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: this.newCollection.title,
                        kicker: this.newCollection.kicker,
                        description: this.newCollection.description,
                        is_public: this.newCollection.is_public
                    })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    window.location.href = '{{ route('admin.media.index') }}?category=' + data.collection.slug;
                } else {
                    this.newCollection.errorMessage = data.message || 'Failed to create collection.';
                    this.newCollection.isSubmitting = false;
                }
            } catch (e) {
                this.newCollection.errorMessage = 'Network error while creating collection.';
                this.newCollection.isSubmitting = false;
            }
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedItems = {{ json_encode($mediaItems->pluck('id')->values()) }};
            } else {
                this.selectedItems = [];
            }
        },

        copyUrl(id, url) {
            navigator.clipboard.writeText(url).then(() => {
                this.copiedId = id;
                setTimeout(() => {
                    if (this.copiedId === id) this.copiedId = null;
                }, 2000);
            });
        },

        openPreview(item) {
            this.previewItem = item;
        },

        closePreview() {
            this.previewItem = null;
        },

        openEdit(item) {
            let takenAtDate = '';
            if (item.taken_at) {
                takenAtDate = item.taken_at.includes('T') ? item.taken_at.split('T')[0] : item.taken_at.split(' ')[0];
            }
            this.editingItem = { 
                ...item,
                taken_at_date: takenAtDate
            };
        },

        closeEdit() {
            this.editingItem = null;
        },

        handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.uploadFiles(files);
            }
        },

        handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                this.uploadFiles(files);
            }
            e.target.value = '';
        },

        async uploadFiles(files) {
            if (!files || files.length === 0) return;
            this.isUploading = true;
            this.uploadProgress = 0;
            const total = files.length;
            let successCount = 0;
            const errors = [];
            const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';

            for (let i = 0; i < total; i++) {
                const file = files[i];
                this.uploadProgress = Math.round((i / total) * 100);
                this.uploadMessage = `Uploading, extracting EXIF & optimizing ${i + 1} of ${total}: ${file.name}...`;

                const formData = new FormData();
                formData.append('image', file);

                try {
                    const response = await fetch('{{ route('admin.media.upload') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    let data = null;
                    const contentType = response.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        data = await response.json();
                    }

                    if (response.ok && data && data.success) {
                        successCount++;
                    } else {
                        let errMsg = data?.message;
                        if (!errMsg && data?.errors) {
                            const firstKey = Object.keys(data.errors)[0];
                            errMsg = data.errors[firstKey][0];
                        }
                        errors.push(`${file.name}: ${errMsg || 'Upload failed'}`);
                    }
                } catch (err) {
                    errors.push(`${file.name}: Network error`);
                }
            }

            this.uploadProgress = 100;
            this.uploadMessage = `Finished processing ${total} ${total === 1 ? 'file' : 'files'}.`;

            setTimeout(() => {
                this.isUploading = false;
                if (errors.length > 0) {
                    alert(`Upload summary:\n• ${successCount} succeeded\n• ${errors.length} failed\n\nErrors:\n` + errors.slice(0, 5).join('\n') + (errors.length > 5 ? `\n...and ${errors.length - 5} more.` : ''));
                }
                window.location.reload();
            }, 800);
        },

        async executeBulkAction(action, extraPayload = {}) {
            if (this.selectedItems.length === 0) return;
            this.isPerformingBulk = true;
            try {
                const response = await fetch('{{ route('admin.media.bulk-action') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: action,
                        media_ids: this.selectedItems,
                        ...extraPayload
                    })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    window.location.reload();
                } else {
                    alert(data?.message || 'Bulk operation failed.');
                    this.isPerformingBulk = false;
                }
            } catch (e) {
                alert('Network error while processing bulk action.');
                this.isPerformingBulk = false;
            }
        },

        bulkAssignAlbum() {
            if (this.selectedItems.length === 0) return;
            this.showBulkAssignModal = true;
        },

        async submitBulkAssign() {
            await this.executeBulkAction('assign_album', {
                gallery_album_id: this.bulkAssignAlbumId || null
            });
            this.showBulkAssignModal = false;
        },

        bulkEditDate() {
            if (this.selectedItems.length === 0) return;
            this.showBulkDateModal = true;
        },

        async submitBulkDate() {
            await this.executeBulkAction('update_date', {
                taken_at: this.bulkDate || null,
                year: this.bulkYear ? parseInt(this.bulkYear) : (this.bulkDate ? new Date(this.bulkDate).getFullYear() : null)
            });
            this.showBulkDateModal = false;
        },

        async bulkToggleVisibility(isPublic) {
            await this.executeBulkAction('set_visibility', {
                is_public: isPublic
            });
        },

        async bulkDeleteSelected() {
            if (!confirm(`Are you sure you want to permanently delete ${this.selectedItems.length} selected photo(s)? This cannot be undone.`)) {
                return;
            }
            await this.executeBulkAction('delete');
        },

        async bulkSetVisibility(isPublic) {
            await this.bulkToggleVisibility(isPublic);
        },

        async saveEdit() {
            if (!this.editingItem) return;
            try {
                const url = '{{ url('admin/media') }}/' + this.editingItem.id;
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: this.editingItem.title,
                        caption: this.editingItem.caption,
                        alt_text: this.editingItem.alt_text,
                        taken_at: this.editingItem.taken_at_date || null,
                        year: this.editingItem.year ? parseInt(this.editingItem.year) : (this.editingItem.taken_at_date ? new Date(this.editingItem.taken_at_date).getFullYear() : null),
                        is_public: Boolean(this.editingItem.is_public),
                        gallery_album_id: this.editingItem.gallery_album_id || null
                    })
                });

                let data = null;
                const contentType = response.headers.get('content-type') || '';
                if (contentType.includes('application/json')) {
                    data = await response.json();
                }

                if (response.ok && data && data.success) {
                    window.location.reload();
                } else {
                    alert(data?.message || `Failed to update media details (status ${response.status}).`);
                }
            } catch (e) {
                alert('An error occurred while saving.');
            }
        }
    }" class="space-y-8">

        {{-- Top Heading & Quick Actions --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-6 border-b border-stone-200">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-crimson/10 text-crimson border border-crimson/20 mb-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Central Media Operations</span>
                </div>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Central Media Library</h1>
                <p class="text-sm text-stone-500 mt-1">Deep asset management with EXIF capture dates, color spaces, configurable pagination, and responsive grid zoom.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" 
                        @click="showNewCollectionModal = true" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-white text-stone-700 hover:text-maroon border border-stone-200 hover:border-maroon/30 transition-all shadow-2xs cursor-pointer">
                    <svg class="w-4 h-4 text-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ New Collection</span>
                </button>
                <button type="button" 
                        @click="$refs.fileInput.click()" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-xs cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>Upload New Photos</span>
                </button>
            </div>
        </div>

        {{-- Metrics & Optimization Status Bar --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Total Cataloged</span>
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-stone-900">{{ number_format($totalCount) }}</span>
                    <span class="text-xs text-stone-500">photos & assets</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Public in Gallery</span>
                    <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-emerald-600">{{ number_format($publicCount ?? 0) }}</span>
                    <span class="text-xs text-stone-500">visible on website</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Archive Library</span>
                    <span class="p-2 rounded-xl bg-stone-100 text-stone-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-stone-700">{{ number_format($archiveCount ?? 0) }}</span>
                    <span class="text-xs text-stone-500">hidden from public</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">WebP & Storage</span>
                    <span class="p-2 rounded-xl bg-purple-50 text-purple-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-stone-900">{{ $totalSizeFormatted }}</span>
                    <span class="text-xs text-emerald-600 font-semibold">{{ $webpRate }}% WebP</span>
                </div>
            </div>
        </div>

        {{-- Drag-and-Drop Uploader Card --}}
        <div @dragover.prevent="isDragging = true"
             @dragleave.prevent="isDragging = false"
             @drop.prevent="handleDrop($event)"
             :class="{ 'border-maroon bg-maroon/5 ring-4 ring-maroon/10': isDragging, 'border-stone-200 bg-stone-50/50 hover:bg-stone-50': !isDragging }"
             class="border-2 border-dashed rounded-3xl p-8 text-center transition-all cursor-pointer relative"
             @click="$refs.fileInput.click()">
            
            <input type="file" 
                   x-ref="fileInput" 
                   @change="handleFileSelect($event)" 
                   multiple 
                   accept="image/png,image/jpeg,image/webp,image/jpg" 
                   class="hidden">

            <div class="max-w-md mx-auto space-y-3">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-white border border-stone-200 shadow-2xs flex items-center justify-center text-maroon">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-stone-800">
                        Drag and drop photos here, or <span class="text-maroon underline">browse files</span>
                    </p>
                    <p class="text-xs text-stone-500 mt-1">
                        JPEG, PNG, or WebP (up to {{ $effectiveMaxUploadSize ?? '16M' }}). Automatic EXIF capture date & color space extraction, optimized to &le; 180 KB WebP.
                    </p>
                </div>
            </div>

            {{-- Upload Progress Indicator --}}
            <div x-show="isUploading" x-cloak class="mt-6 max-w-sm mx-auto">
                <div class="flex items-center justify-between text-xs font-semibold text-stone-700 mb-1.5">
                    <span x-text="uploadMessage"></span>
                    <span x-text="uploadProgress + '%'"></span>
                </div>
                <div class="w-full h-2 bg-stone-200 rounded-full overflow-hidden">
                    <div class="h-full bg-maroon transition-all duration-300" :style="'width: ' + uploadProgress + '%'"></div>
                </div>
            </div>
        </div>

        {{-- Search, Date Filter & Category Filter Form --}}
        <form method="GET" action="{{ route('admin.media.index') }}" class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-2xs space-y-4">
            <input type="hidden" name="year" value="{{ $currentYear ?? 'all' }}">

            {{-- Quick Year Filter Chips --}}
            <div class="flex flex-wrap items-center gap-2 pb-3 border-b border-stone-100">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 mr-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Event Year:</span>
                </span>
                <a href="{{ request()->fullUrlWithQuery(['year' => 'all', 'page' => 1]) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($currentYear ?? 'all') === 'all' ? 'bg-maroon text-white shadow-2xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200 hover:text-stone-900' }}">
                    All Years
                </a>
                @if(isset($availableYears) && count($availableYears))
                    @foreach($availableYears as $yr)
                        <a href="{{ request()->fullUrlWithQuery(['year' => $yr, 'page' => 1]) }}"
                           class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($currentYear ?? 'all') == $yr ? 'bg-maroon text-white shadow-2xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200 hover:text-stone-900' }}">
                            {{ $yr }}
                        </a>
                    @endforeach
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3.5">
                {{-- Search by Photo Name / Filename / Caption --}}
                <div class="lg:col-span-2 relative">
                    <label for="media-search" class="block text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Search Photos</label>
                    <div class="relative">
                        <input type="text" 
                               id="media-search" 
                               name="search" 
                               value="{{ $currentSearch }}" 
                               placeholder="Search by photo name, filename, caption..." 
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Visibility Filter (Public vs Archive) --}}
                <div>
                    <label for="visibility-filter" class="block text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Visibility</label>
                    <select id="visibility-filter" 
                            name="visibility" 
                            x-model="visibility"
                            class="w-full px-3 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-800 bg-white focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <option value="all">All Visibility</option>
                        <option value="public">👁️ Public Gallery</option>
                        <option value="archive">📁 Archive Only</option>
                    </select>
                </div>

                {{-- Date Uploaded Filter (Quick Presets + Exact Date) --}}
                <div>
                    <label for="date-filter" class="block text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Date Uploaded</label>
                    <select id="date-filter" 
                            name="date_filter" 
                            x-model="dateFilter"
                            class="w-full px-3 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-800 bg-white focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <option value="all">All Dates</option>
                        <option value="today">Uploaded Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="past_year">Past Year</option>
                    </select>
                </div>

                {{-- Category Switcher --}}
                <div>
                    <label for="category-filter" class="block text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Asset Category</label>
                    <select id="category-filter" 
                            name="category" 
                            x-model="category"
                            class="w-full px-3 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-800 bg-white focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <option value="all">All Media</option>
                        <optgroup label="System Categories">
                            <option value="gallery">All Gallery Photos</option>
                            <option value="site">Site & CMS Assets</option>
                            <option value="general">Unassigned Media</option>
                        </optgroup>
                        @if(isset($collections) && $collections->count())
                            <optgroup label="Collections & Programs">
                                @foreach($collections as $col)
                                    <option value="{{ $col->slug }}">{{ $col->title }}{{ !$col->is_public ? ' (Internal Only)' : '' }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>

                {{-- Multi-Criteria Sorting Selector --}}
                <div>
                    <label for="sort-filter" class="block text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Sort Criteria</label>
                    <select id="sort-filter" 
                            name="sort" 
                            x-model="sort"
                            class="w-full px-3 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-800 bg-white focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <option value="newest">Upload: Newest First</option>
                        <option value="oldest">Upload: Oldest First</option>
                        <option value="captured_desc">Capture Date: Newest First</option>
                        <option value="captured_asc">Capture Date: Oldest First</option>
                        <option value="size_desc">File Size: Largest First</option>
                        <option value="size_asc">File Size: Smallest First</option>
                        <option value="dimensions_desc">Resolution: Highest First</option>
                        <option value="dimensions_asc">Resolution: Lowest First</option>
                        <option value="name_asc">Name: A &rarr; Z</option>
                        <option value="name_desc">Name: Z &rarr; A</option>
                        <option value="year_desc">Event Year: Newest First</option>
                        <option value="year_asc">Event Year: Oldest First</option>
                    </select>
                </div>

                {{-- Per-Page Pagination Selector --}}
                <div>
                    <label for="per-page-filter" class="block text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Per Page</label>
                    <select id="per-page-filter" 
                            name="per_page" 
                            x-model="perPage"
                            class="w-full px-3 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-800 bg-white focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        @foreach(($availablePerPage ?? [80, 150, 250, 500]) as $sizeOption)
                            <option value="{{ $sizeOption }}">{{ $sizeOption }} per page</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Filter Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-stone-100">
                <div class="text-xs text-stone-500">
                    Showing <span class="font-bold text-stone-900">{{ $mediaItems->total() }}</span> matching {{ Str::plural('photo', $mediaItems->total()) }}
                    @if(($currentYear ?? 'all') !== 'all')
                        <span class="ml-1.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-maroon/10 text-maroon font-semibold">
                            Year: {{ $currentYear }}
                        </span>
                    @endif
                    @if(($currentVisibility ?? 'all') !== 'all')
                        <span class="ml-1.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-md {{ $currentVisibility === 'public' ? 'bg-emerald-50 text-emerald-700' : 'bg-stone-100 text-stone-700' }} font-semibold">
                            Visibility: {{ ucfirst($currentVisibility) }}
                        </span>
                    @endif
                    <span class="ml-1.5 text-stone-400">({{ $mediaItems->perPage() }} per page)</span>
                </div>
                <div class="flex items-center gap-2">
                    @if ($currentSearch || ($currentDateFilter && $currentDateFilter !== 'all') || ($currentCategory && $currentCategory !== 'all') || (($currentYear ?? 'all') !== 'all') || (($currentVisibility ?? 'all') !== 'all') || ($currentSort && $currentSort !== 'newest') || ($currentPerPage && $currentPerPage != 80))
                        <a href="{{ route('admin.media.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-stone-500 hover:text-stone-800 hover:bg-stone-100 transition-colors">
                            Reset Filters
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-stone-900 text-white hover:bg-stone-800 transition-colors cursor-pointer">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>

        {{-- Floating Sticky Batch Action Bar --}}
        <div x-show="selectedItems.length > 0" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="fixed bottom-6 inset-x-4 max-w-4xl mx-auto z-40 bg-stone-900/95 text-white backdrop-blur-md rounded-2xl p-4 shadow-2xl border border-stone-700/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full bg-amber-500 text-stone-950 flex items-center justify-center text-xs font-extrabold" x-text="selectedItems.length"></span>
                <div>
                    <p class="text-xs font-bold text-white"><span x-text="selectedItems.length"></span> photo(s) selected</p>
                    <p class="text-[10px] text-stone-400">Choose a bulk action to apply to all selected items</p>
                </div>
                <button type="button" @click="selectedItems = []; selectAll = false" class="ml-2 px-2 py-1 rounded-lg text-[10px] font-semibold text-stone-400 hover:text-white hover:bg-stone-800 transition-colors cursor-pointer">
                    Clear
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- Bulk Assign to Collection --}}
                <button type="button" 
                        @click="bulkAssignAlbum()" 
                        class="px-3 py-1.5 rounded-xl text-xs font-bold bg-stone-800 hover:bg-stone-700 text-stone-200 hover:text-white transition-all flex items-center gap-1.5 border border-stone-700 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Assign Collection</span>
                </button>

                {{-- Bulk Edit Date / Year --}}
                <button type="button" 
                        @click="bulkEditDate()" 
                        class="px-3 py-1.5 rounded-xl text-xs font-bold bg-stone-800 hover:bg-stone-700 text-stone-200 hover:text-white transition-all flex items-center gap-1.5 border border-stone-700 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Edit Date / Year</span>
                </button>

                {{-- Make Public --}}
                <button type="button" 
                        @click="bulkToggleVisibility(true)" 
                        class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-600/90 hover:bg-emerald-600 text-white transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Make Public</span>
                </button>

                {{-- Hide to Archive --}}
                <button type="button" 
                        @click="bulkToggleVisibility(false)" 
                        class="px-3 py-1.5 rounded-xl text-xs font-bold bg-stone-800 hover:bg-stone-700 text-stone-300 hover:text-white transition-all flex items-center gap-1.5 border border-stone-700 cursor-pointer">
                    <span>Archive</span>
                </button>

                {{-- Bulk Delete --}}
                <button type="button" 
                        @click="bulkDeleteSelected()" 
                        class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-600/90 hover:bg-rose-600 text-white transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Delete</span>
                </button>
            </div>
        </div>

        {{-- Display Controls Bar: Interactive Grid Zoom Controls & Select All --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-stone-50 p-3.5 rounded-2xl border border-stone-200/80">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-stone-700">
                    <input type="checkbox" 
                           x-model="selectAll" 
                           @change="toggleSelectAll()" 
                           class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon cursor-pointer">
                    <span>Select All on Page ({{ $mediaItems->count() }})</span>
                </label>
            </div>

            {{-- Zoom Controls (Compact, Normal, Large, X-Large) --}}
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 mr-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    <span>Grid View:</span>
                </span>
                <div class="inline-flex rounded-xl bg-white border border-stone-200 p-0.5 shadow-2xs">
                    <button type="button" 
                            @click="setZoom('compact')" 
                            :class="zoomLevel === 'compact' ? 'bg-maroon text-white shadow-xs' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50'"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                            title="Compact Grid (Dense thumbnail overview)">
                        <span>Compact</span>
                    </button>
                    <button type="button" 
                            @click="setZoom('normal')" 
                            :class="zoomLevel === 'normal' ? 'bg-maroon text-white shadow-xs' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50'"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                            title="Normal Grid (Default balanced cards)">
                        <span>Normal</span>
                    </button>
                    <button type="button" 
                            @click="setZoom('large')" 
                            :class="zoomLevel === 'large' ? 'bg-maroon text-white shadow-xs' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50'"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                            title="Large Grid (Detailed card preview)">
                        <span>Large</span>
                    </button>
                    <button type="button" 
                            @click="setZoom('extra-large')" 
                            :class="zoomLevel === 'extra-large' ? 'bg-maroon text-white shadow-xs' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50'"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                            title="Extra Large (High-resolution inspection)">
                        <span>X-Large</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Dynamic Media Grid with Responsive Zoom Binding --}}
        @if ($mediaItems->count() > 0)
            <div class="grid transition-all duration-300"
                 :class="{
                     'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3': zoomLevel === 'compact',
                     'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6': zoomLevel === 'normal',
                     'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6': zoomLevel === 'large',
                     'grid-cols-1 sm:grid-cols-2 gap-8': zoomLevel === 'extra-large'
                 }">
                @foreach ($mediaItems as $item)
                    <div class="group bg-white rounded-3xl border border-stone-200/80 shadow-2xs hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                        {{-- Image Thumbnail Container --}}
                        <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden cursor-pointer" @click="openInspector({{ json_encode($item) }})">
                            <img src="{{ $item->image_url }}" 
                                 alt="{{ $item->alt_text ?: $item->title }}" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                            {{-- Top Left Checkbox --}}
                            <div class="absolute top-3 left-3" @click.stop>
                                <input type="checkbox" 
                                       :value="{{ $item->id }}" 
                                       x-model="selectedItems" 
                                       class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon cursor-pointer bg-white/90">
                            </div>

                            {{-- Top Right Badges --}}
                            <div class="absolute top-3 right-3 flex flex-col items-end gap-1.5">
                                <div class="flex items-center gap-1 flex-wrap justify-end">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/60 text-white backdrop-blur-xs">
                                        {{ $item->formatted_size }}
                                    </span>
                                    @if ($item->color_space)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-900/80 text-indigo-100 backdrop-blur-xs">
                                            {{ $item->color_space }}
                                        </span>
                                    @endif
                                    @if ($item->mime_type === 'image/webp')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-600 text-white">
                                            WebP
                                        </span>
                                    @endif
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    @if ($item->is_public)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600/90 text-white backdrop-blur-xs shadow-2xs flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-200 animate-pulse"></span>
                                            Public Gallery
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-stone-800/80 text-stone-300 backdrop-blur-xs shadow-2xs flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                            Archive Only
                                        </span>
                                    @endif

                                    @php
                                        $isUsedOnSite = isset($activeSitePaths[$item->file_path]) || isset($activeSitePaths[basename($item->file_path)]);
                                    @endphp
                                    @if ($isUsedOnSite)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600/90 text-white backdrop-blur-xs shadow-2xs">
                                            🌐 Used on Site
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Bottom Left Indicators --}}
                            <div class="absolute bottom-3 left-3 flex flex-wrap items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-white/90 text-stone-700 backdrop-blur-xs shadow-2xs">
                                    {{ $item->source_type === 'gallery' ? 'Gallery' : ($item->source_type === 'cms' ? 'CMS' : 'General') }}
                                </span>
                                @if ($item->year)
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-maroon text-white backdrop-blur-xs shadow-2xs">
                                        {{ $item->year }}
                                    </span>
                                @endif
                                @if ($item->taken_at)
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-500/90 text-white backdrop-blur-xs shadow-2xs flex items-center gap-1" title="EXIF Capture Timestamp: {{ $item->formatted_taken_at }}">
                                        <span>📷</span>
                                        <span>{{ $item->capture_date_formatted }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Metadata & Actions --}}
                        <div class="p-4 space-y-3">
                            <div>
                                <h3 class="text-xs font-bold text-stone-900 truncate" title="{{ $item->title ?: $item->filename }}">
                                    {{ $item->title ?: $item->filename }}
                                </h3>
                                <div class="flex items-center justify-between text-[11px] text-stone-400 mt-1">
                                    <span title="Dimensions & Aspect Ratio">{{ $item->dimensions }} ({{ $item->aspect_ratio }})</span>
                                    <span>{{ $item->formatted_date }}</span>
                                </div>
                            </div>

                            {{-- Bottom Button Row --}}
                            <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-1.5">
                                {{-- Copy Public URL --}}
                                <button type="button" 
                                        @click="copyUrl({{ $item->id }}, '{{ $item->image_url }}')" 
                                        class="flex-1 py-1.5 px-2 rounded-xl text-[11px] font-bold transition-all text-center flex items-center justify-center gap-1 cursor-pointer"
                                        :class="copiedId === {{ $item->id }} ? 'bg-emerald-600 text-white' : 'bg-stone-100 hover:bg-stone-200 text-stone-700'">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="copiedId === {{ $item->id }} ? 'Copied!' : 'Copy URL'"></span>
                                </button>

                                {{-- Inspector Modal Trigger --}}
                                <button type="button" 
                                        @click="openInspector({{ json_encode($item) }})" 
                                        class="p-1.5 text-stone-500 hover:text-maroon hover:bg-maroon/10 rounded-xl transition-colors cursor-pointer" 
                                        title="Inspect deep EXIF & photo metadata">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>

                                {{-- Toggle Gallery Visibility --}}
                                <form method="POST" action="{{ route('admin.media.toggle-visibility', $item) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="p-1.5 rounded-xl transition-colors cursor-pointer {{ $item->is_public ? 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100' : 'text-stone-500 bg-stone-100 hover:bg-stone-200' }}" 
                                            title="{{ $item->is_public ? 'Currently visible in Public Gallery. Click to move to archive.' : 'Currently Archive. Click to display in Public Gallery.' }}">
                                        @if ($item->is_public)
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                {{-- Edit Metadata --}}
                                <button type="button" 
                                        @click="openEdit({{ json_encode($item) }})" 
                                        class="p-1.5 text-stone-500 hover:text-stone-900 hover:bg-stone-100 rounded-xl transition-colors cursor-pointer" 
                                        title="Edit title & alt text">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.media.destroy', $item) }}" onsubmit="return confirm('Permanently remove this photo from library and disk?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Delete media">
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

            {{-- Pagination Links --}}
            <div class="pt-6">
                {{ $mediaItems->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl p-12 text-center border border-stone-200/80 shadow-2xs space-y-4">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-stone-100 flex items-center justify-center text-stone-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-stone-900">No media found</h3>
                    <p class="text-xs text-stone-500 max-w-sm mx-auto mt-1">
                        Try adjusting your search query, date filter, or upload new photos using the dropzone above.
                    </p>
                </div>
                @if ($currentSearch || ($currentDateFilter && $currentDateFilter !== 'all') || ($currentCategory && $currentCategory !== 'all'))
                    <div>
                        <a href="{{ route('admin.media.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-stone-900 text-white hover:bg-stone-800 transition-colors">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        @endif

        {{-- Deep Metadata Photo Inspector Modal --}}
        <div x-show="inspectorItem" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/85 backdrop-blur-md"
             @click.self="closeInspector()">
            <div class="bg-white rounded-3xl max-w-4xl w-full overflow-hidden shadow-2xl border border-white/20 animate-in fade-in zoom-in duration-200 flex flex-col max-h-[90vh]"
                 x-show="inspectorItem" 
                 x-transition>
                
                {{-- Modal Header --}}
                <div class="p-4 sm:p-5 flex items-center justify-between border-b border-stone-200 bg-stone-50">
                    <div class="flex items-center gap-2.5 min-w-0 pr-4">
                        <span class="p-2 rounded-xl bg-maroon/10 text-maroon">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-stone-900 truncate" x-text="inspectorItem ? (inspectorItem.title || inspectorItem.filename) : 'Photo Inspector'"></h3>
                            <p class="text-[11px] text-stone-500">Comprehensive technical specifications & asset metadata.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeInspector()" class="p-2 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body: Split Image Preview & Metadata Table --}}
                <div class="overflow-y-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    {{-- Left Side: Image Viewport --}}
                    <div class="space-y-4">
                        <div class="rounded-2xl overflow-hidden bg-stone-950 border border-stone-200/80 aspect-4/3 flex items-center justify-center relative group">
                            <img :src="inspectorItem ? inspectorItem.image_url : ''" 
                                 :alt="inspectorItem ? (inspectorItem.alt_text || inspectorItem.title) : ''" 
                                 class="max-h-full max-w-full object-contain">
                            
                            <a :href="inspectorItem ? inspectorItem.image_url : '#'" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="absolute top-3 right-3 px-3 py-1.5 rounded-xl text-xs font-bold bg-black/70 text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1.5">
                                <span>Open Full Resolution</span>
                                <span>&nearr;</span>
                            </a>
                        </div>

                        {{-- URL Copy Bar --}}
                        <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200/80 space-y-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 block">Public Web URL</span>
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                       readonly 
                                       :value="inspectorItem ? inspectorItem.image_url : ''" 
                                       class="w-full px-3 py-1.5 rounded-xl border border-stone-200 bg-white font-mono text-xs text-stone-700 select-all">
                                <button type="button" 
                                        @click="copyUrl(inspectorItem.id, inspectorItem.image_url)" 
                                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-colors shrink-0 shadow-2xs cursor-pointer">
                                    <span x-text="copiedId === inspectorItem?.id ? 'Copied!' : 'Copy'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Deep Metadata Spec Table --}}
                    <div class="space-y-4">
                        <div class="bg-stone-50 rounded-2xl p-4 border border-stone-200/80 space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-stone-500 pb-2 border-b border-stone-200 flex items-center gap-1.5">
                                <span>⚙️</span> Technical Specifications
                            </h4>

                            <dl class="grid grid-cols-2 gap-y-2.5 text-xs">
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">Capture Date (EXIF)</dt>
                                    <dd class="font-semibold text-stone-800" x-text="inspectorItem?.formatted_taken_at || (inspectorItem?.taken_at ? new Date(inspectorItem.taken_at).toLocaleString() : 'Not in EXIF')"></dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">Date Uploaded</dt>
                                    <dd class="font-semibold text-stone-800" x-text="inspectorItem?.date_formatted || 'Unknown'"></dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">Pixel Dimensions</dt>
                                    <dd class="font-semibold text-stone-800" x-text="inspectorItem?.dimensions ? inspectorItem.dimensions + ' px' : 'Auto'"></dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">Aspect Ratio</dt>
                                    <dd class="font-semibold text-stone-800" x-text="inspectorItem?.aspect_ratio || 'Auto'"></dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">File Size</dt>
                                    <dd class="font-semibold text-stone-800" x-text="inspectorItem?.formatted_size || '0 KB'"></dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">Format & MIME</dt>
                                    <dd class="font-semibold text-stone-800 uppercase" x-text="inspectorItem?.mime_type || 'image/webp'"></dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">Color Space</dt>
                                    <dd class="font-semibold text-indigo-700" x-text="inspectorItem?.color_space || 'sRGB'"></dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] uppercase font-bold text-stone-400">Event Year</dt>
                                    <dd class="font-semibold text-stone-800" x-text="inspectorItem?.year || 'Unassigned'"></dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Content Metadata --}}
                        <div class="bg-stone-50 rounded-2xl p-4 border border-stone-200/80 space-y-2.5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-stone-500 pb-2 border-b border-stone-200 flex items-center gap-1.5">
                                <span>📝</span> Content & Accessibility
                            </h4>
                            <div class="space-y-1.5 text-xs">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-stone-400 block">Title</span>
                                    <p class="font-semibold text-stone-800" x-text="inspectorItem?.title || 'No title set'"></p>
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-stone-400 block">Alt Text (Screen Readers)</span>
                                    <p class="text-stone-700 text-xs" x-text="inspectorItem?.alt_text || 'No alt text set'"></p>
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-stone-400 block">Caption</span>
                                    <p class="text-stone-700 text-xs" x-text="inspectorItem?.caption || 'No caption set'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Action Buttons in Inspector --}}
                        <div class="pt-2 flex flex-wrap items-center gap-2">
                            <button type="button" 
                                    @click="closeInspector(); openEdit(inspectorItem)" 
                                    class="px-4 py-2 rounded-xl text-xs font-bold bg-stone-900 text-white hover:bg-stone-800 transition-colors flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span>Edit Metadata</span>
                            </button>
                            <button type="button" 
                                    @click="closeInspector()" 
                                    class="px-4 py-2 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-200 transition-colors cursor-pointer">
                                Close Inspector
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Preview Lightbox Modal --}}
        <div x-show="previewItem" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/80 backdrop-blur-sm"
             @click.self="closePreview()">
            <div class="bg-white rounded-3xl max-w-3xl w-full overflow-hidden shadow-2xl border border-white/20 animate-in fade-in zoom-in duration-200"
                 x-show="previewItem" 
                 x-transition>
                
                {{-- Modal Header --}}
                <div class="p-4 sm:p-5 flex items-center justify-between border-b border-stone-100 bg-stone-50/50">
                    <div class="min-w-0 pr-4">
                        <h3 class="text-sm font-bold text-stone-900 truncate" x-text="previewItem ? (previewItem.title || previewItem.filename) : ''"></h3>
                        <p class="text-[11px] text-stone-500" x-text="previewItem ? (previewItem.formatted_size + ' • ' + previewItem.dimensions + (previewItem.year ? ' • Year: ' + previewItem.year : '') + ' • ' + previewItem.formatted_date) : ''"></p>
                    </div>
                    <button type="button" @click="closePreview()" class="p-2 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 bg-stone-950 flex items-center justify-center max-h-[60vh] overflow-hidden">
                    <img :src="previewItem ? previewItem.image_url : ''" 
                         :alt="previewItem ? (previewItem.alt_text || previewItem.title) : ''" 
                         class="max-h-[55vh] w-auto max-w-full object-contain rounded-xl">
                </div>

                {{-- Modal Footer --}}
                <div class="p-4 sm:p-5 bg-stone-50 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-stone-200">
                    <div class="text-xs text-stone-600 truncate w-full sm:w-auto font-mono bg-stone-200/60 px-3 py-1.5 rounded-xl select-all" x-text="previewItem ? previewItem.image_url : ''"></div>
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <button type="button" 
                                @click="copyUrl(previewItem.id, previewItem.image_url)" 
                                class="px-4 py-2 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-colors shadow-2xs cursor-pointer">
                            Copy Public URL
                        </button>
                        <button type="button" @click="closePreview()" class="px-4 py-2 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-200 transition-colors cursor-pointer">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Metadata Modal --}}
        <div x-show="editingItem" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/80 backdrop-blur-sm"
             @click.self="closeEdit()">
            <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-white/20 animate-in fade-in zoom-in duration-200"
                 x-show="editingItem" 
                 x-transition>
                
                <div class="p-5 flex items-center justify-between border-b border-stone-100">
                    <h3 class="text-sm font-bold text-stone-900">Edit Photo Metadata</h3>
                    <button type="button" @click="closeEdit()" class="p-1.5 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3.5 p-3 bg-stone-50 rounded-2xl border border-stone-200/80" x-show="editingItem?.image_url">
                        <img :src="editingItem?.image_url" id="admin_media_edit_preview_img" alt="Photo Preview" class="w-14 h-14 object-cover rounded-xl border border-stone-200 shrink-0">
                        <div class="min-w-0 text-xs">
                            <p class="font-bold text-stone-800 truncate" x-text="editingItem?.filename || 'Photo Asset'"></p>
                            <p class="text-[11px] text-stone-400" x-text="(editingItem?.dimensions ? editingItem.dimensions + ' • ' : '') + (editingItem?.formatted_size || '')"></p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="admin_media_edit_title" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Display Title / Name</label>
                            <x-admin.ai-button 
                                for="admin_media_edit_title" 
                                field-name="title" 
                                field-label="Photo Title" 
                                page-context="Media Library" 
                                section-context="Photo Item" 
                                image-selector="#admin_media_edit_preview_img" 
                                :sibling-selectors="['caption' => '#admin_media_edit_caption']" 
                            />
                        </div>
                        <input type="text" 
                               id="admin_media_edit_title"
                               x-model="editingItem.title" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <p class="text-[11px] text-stone-400 mt-1">Used for library search and administrative labels.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Assigned Collection</label>
                            <select x-model="editingItem.gallery_album_id" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                                <option value="">Unassigned / Site Asset</option>
                                @if(isset($collections) && $collections->count())
                                    @foreach($collections as $col)
                                        <option value="{{ $col->id }}">{{ $col->title }}{{ !$col->is_public ? ' (Internal Only)' : '' }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-[11px] text-stone-400 mt-1">Assign to a collection.</p>
                        </div>

                        <div>
                            <label for="admin_media_edit_taken_at" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Capture Date (Day, Month, Year)</label>
                            <input type="date" 
                                   id="admin_media_edit_taken_at"
                                   x-model="editingItem.taken_at_date" 
                                   @change="if (editingItem.taken_at_date) { editingItem.year = new Date(editingItem.taken_at_date).getFullYear(); }"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            <p class="text-[11px] text-stone-400 mt-1">Exact calendar date photo was taken.</p>
                        </div>

                        <div>
                            <label for="admin_media_edit_year" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Event / Archive Year</label>
                            <input type="number" 
                                   id="admin_media_edit_year"
                                   x-model="editingItem.year" 
                                   min="2000"
                                   max="2035"
                                   placeholder="e.g. 2025"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            <p class="text-[11px] text-stone-400 mt-1">Kept in sync with capture date or set manually.</p>
                        </div>
                    </div>

                    {{-- Public Gallery Visibility Toggle --}}
                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200/80 flex items-center justify-between">
                        <div>
                            <label for="admin_media_edit_is_public" class="block text-xs font-bold text-stone-900 cursor-pointer">Display in Public Gallery</label>
                            <p class="text-[11px] text-stone-500">When enabled, this photo is displayed publicly on /gallery and album pages.</p>
                        </div>
                        <input type="checkbox" 
                                id="admin_media_edit_is_public" 
                                x-model="editingItem.is_public" 
                                class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon cursor-pointer">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="admin_media_edit_caption" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Caption</label>
                            <x-admin.ai-button 
                                for="admin_media_edit_caption" 
                                field-name="caption" 
                                field-label="Photo Caption" 
                                page-context="Media Library" 
                                section-context="Photo Asset" 
                                image-selector="#admin_media_edit_preview_img" 
                                :sibling-selectors="['title' => '#admin_media_edit_title']" 
                            />
                        </div>
                        <textarea id="admin_media_edit_caption"
                                  x-model="editingItem.caption" 
                                  rows="2" 
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon"></textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="admin_media_edit_alt_text" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Alt Text (Accessibility)</label>
                            <x-admin.ai-button 
                                for="admin_media_edit_alt_text" 
                                field-name="alt_text" 
                                field-label="Photo Alt Text" 
                                page-context="Media Library" 
                                section-context="Accessibility & Screen Readers" 
                                image-selector="#admin_media_edit_preview_img" 
                                :sibling-selectors="['title' => '#admin_media_edit_title', 'caption' => '#admin_media_edit_caption']" 
                            />
                        </div>
                        <input type="text" 
                               id="admin_media_edit_alt_text"
                               x-model="editingItem.alt_text" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <p class="text-[11px] text-stone-400 mt-1">Recommended for screen readers and SEO.</p>
                    </div>
                </div>

                <div class="p-4 bg-stone-50 flex items-center justify-end gap-2 border-t border-stone-100">
                    <button type="button" @click="closeEdit()" class="px-4 py-2 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-200 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="button" @click="saveEdit()" class="px-4 py-2 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-colors shadow-2xs cursor-pointer">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        {{-- New Collection Modal --}}
        <div x-show="showNewCollectionModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/80 backdrop-blur-sm"
             @click.self="showNewCollectionModal = false">
            <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-white/20 animate-in fade-in zoom-in duration-200"
                 x-show="showNewCollectionModal" 
                 x-transition>
                
                <div class="p-5 flex items-center justify-between border-b border-stone-100 bg-stone-50/50">
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-crimson/10 text-crimson">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-stone-900">New Collection / Category</h3>
                            <p class="text-[11px] text-stone-500">Add a program category or gallery collection.</p>
                        </div>
                    </div>
                    <button type="button" @click="showNewCollectionModal = false" class="p-1.5 text-stone-400 hover:text-stone-700 rounded-xl hover:bg-stone-100 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="createCollection()" class="p-6 space-y-4">
                    <template x-if="newCollection.errorMessage">
                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium" x-text="newCollection.errorMessage"></div>
                    </template>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="new_collection_title" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Collection Title <span class="text-crimson">*</span></label>
                            <x-admin.ai-button 
                                for="new_collection_title" 
                                field-name="title" 
                                field-label="Collection Title" 
                                page-context="Media Hub" 
                                section-context="New Collection" 
                            />
                        </div>
                        <input type="text" 
                               id="new_collection_title"
                               x-model="newCollection.title" 
                               placeholder="e.g. Essay Competition, Robotics Camp" 
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="new_collection_kicker" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Tagline / Category Kicker</label>
                            <x-admin.ai-button 
                                for="new_collection_kicker" 
                                field-name="kicker" 
                                field-label="Collection Kicker" 
                                page-context="Media Hub" 
                                section-context="Collection Tagline" 
                                :sibling-selectors="['title' => '#new_collection_title']" 
                            />
                        </div>
                        <input type="text" 
                               id="new_collection_kicker"
                               x-model="newCollection.kicker" 
                               placeholder="e.g. Foundation Programme, Community Welfare" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="new_collection_desc" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Description</label>
                            <x-admin.ai-button 
                                for="new_collection_desc" 
                                field-name="description" 
                                field-label="Collection Description" 
                                page-context="Media Hub" 
                                section-context="Collection Summary" 
                                :sibling-selectors="['title' => '#new_collection_title', 'kicker' => '#new_collection_kicker']" 
                            />
                        </div>
                        <textarea id="new_collection_desc"
                                  x-model="newCollection.description" 
                                  rows="2" 
                                  placeholder="Brief overview of this initiative..." 
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon"></textarea>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-stone-50 border border-stone-200/80">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   x-model="newCollection.is_public" 
                                   class="w-4 h-4 mt-0.5 rounded text-maroon focus:ring-maroon border-stone-300 cursor-pointer">
                            <div>
                                <span class="text-xs font-bold text-stone-800">Show in Public Gallery</span>
                                <p class="text-[11px] text-stone-500 leading-relaxed mt-0.5">When checked, this album appears on the public /gallery page. Uncheck for internal/dashboard organization only.</p>
                            </div>
                        </label>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2 border-t border-stone-100">
                        <button type="button" @click="showNewCollectionModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-200 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" 
                                :disabled="newCollection.isSubmitting"
                                class="px-4 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson transition-colors shadow-2xs disabled:opacity-50 flex items-center gap-2 cursor-pointer">
                            <span x-show="newCollection.isSubmitting" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span>Save Collection</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bulk Assign Collection Modal --}}
        <div x-show="showBulkAssignModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
             @click.self="showBulkAssignModal = false">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-stone-200" @click.away="showBulkAssignModal = false">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-bold text-stone-900">Assign Collection in Bulk</h3>
                    <button type="button" @click="showBulkAssignModal = false" class="p-1 text-stone-400 hover:text-stone-700 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-stone-500 mb-4">Assign <span class="font-bold text-maroon" x-text="selectedItems.length"></span> selected photo(s) to a collection or clear assignment.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Target Collection</label>
                        <select x-model="bulkAssignAlbumId" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                            <option value="">Unassigned / General Site Asset</option>
                            @if(isset($collections) && $collections->count())
                                @foreach($collections as $col)
                                    <option value="{{ $col->id }}">{{ $col->title }}{{ !$col->is_public ? ' (Internal Only)' : '' }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-stone-100">
                        <button type="button" @click="showBulkAssignModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 cursor-pointer">Cancel</button>
                        <button type="button" @click="submitBulkAssign()" :disabled="isPerformingBulk" class="px-4 py-2 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson cursor-pointer disabled:opacity-50">
                            <span x-text="isPerformingBulk ? 'Applying...' : 'Apply Collection'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bulk Date / Year Correction Modal --}}
        <div x-show="showBulkDateModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
             @click.self="showBulkDateModal = false">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-stone-200" @click.away="showBulkDateModal = false">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-bold text-stone-900">Bulk Date & Year Correction</h3>
                    <button type="button" @click="showBulkDateModal = false" class="p-1 text-stone-400 hover:text-stone-700 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-stone-500 mb-4">Set the exact capture date and archive year for <span class="font-bold text-maroon" x-text="selectedItems.length"></span> selected photo(s).</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Capture Date (Day, Month, Year)</label>
                        <input type="date" 
                               x-model="bulkDate" 
                               @change="if (bulkDate) { bulkYear = new Date(bulkDate).getFullYear(); }"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <p class="text-[11px] text-stone-400 mt-1">Calendar date photo was captured (sets taken_at).</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Archive / Event Year</label>
                        <input type="number" 
                               x-model="bulkYear" 
                               min="2000" 
                               max="2035" 
                               placeholder="e.g. 2025"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
                        <p class="text-[11px] text-stone-400 mt-1">Easily correct misdated years (e.g. 2024 to 2025).</p>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-stone-100">
                        <button type="button" @click="showBulkDateModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 cursor-pointer">Cancel</button>
                        <button type="button" @click="submitBulkDate()" :disabled="isPerformingBulk" class="px-4 py-2 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-crimson cursor-pointer disabled:opacity-50">
                            <span x-text="isPerformingBulk ? 'Updating...' : 'Update Dates'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
