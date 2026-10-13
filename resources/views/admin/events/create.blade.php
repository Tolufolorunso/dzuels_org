<x-admin-layout title="Create Event">
    <div class="max-w-4xl mx-auto space-y-6" x-data="{
        schedule: [
            { time: '09:00 AM - 10:00 AM', title: 'Registration & Welcome', speaker: 'Host Team' },
            { time: '10:00 AM - 11:30 AM', title: 'Keynote Address', speaker: 'Guest Speaker' }
        ],
        addRow() {
            this.schedule.push({ time: '', title: '', speaker: '' });
        },
        removeRow(index) {
            this.schedule.splice(index, 1);
        }
    }">
        {{-- Breadcrumb / Header --}}
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold text-stone-500 hover:text-stone-800 flex items-center gap-1.5 mb-1">
                    &larr; Back to Events
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Create New Event</h1>
            </div>
        </div>

        {{-- Form Container --}}
        <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 space-y-6 shadow-xs">
                <h2 class="text-base font-bold text-stone-900 pb-3 border-b border-stone-100 flex items-center gap-2">
                    <span>📌</span> Event Details
                </h2>

                {{-- Title & Slug --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Event Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required 
                               placeholder="e.g. Ijero Tech Day & 10th Anniversary" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                        @error('title')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Slug (Optional - auto-generated)
                        </label>
                        <input type="text" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug') }}" 
                               placeholder="e.g. ijero-tech-day-10th-anniversary" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm font-mono focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                        @error('slug')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Date, End Date, Venue & Theme --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Start Date & Time
                        </label>
                        <input type="datetime-local" 
                               id="event_date" 
                               name="event_date" 
                               value="{{ old('event_date') }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                        @error('event_date')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            End Date & Time
                        </label>
                        <input type="datetime-local" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date') }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                        @error('end_date')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="venue" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Venue Location
                        </label>
                        <input type="text" 
                               id="venue" 
                               name="venue" 
                               value="{{ old('venue') }}" 
                               placeholder="e.g. AAoJ Memorial Center, Ijero-Ekiti" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                        @error('venue')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="theme" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Theme / Slogan
                        </label>
                        <input type="text" 
                               id="theme" 
                               name="theme" 
                               value="{{ old('theme') }}" 
                               placeholder="e.g. Learning Today, Creating Tomorrow" 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">
                        @error('theme')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Summary --}}
                <div>
                    <label for="summary" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Short Summary / Teaser <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="summary" 
                              name="summary" 
                              rows="2" 
                              required 
                              placeholder="Brief highlight shown in cards, banners and search previews..." 
                              class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">{{ old('summary') }}</textarea>
                    @error('summary')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Detailed Description --}}
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                        Full Event Narrative & Overview <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="6" 
                              required 
                              placeholder="Detailed write-up of what to expect, who should attend, keynote themes, and activities..." 
                              class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none transition-all">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Banner Image Picker --}}
                <div>
                    <x-admin.media-picker 
                        name="banner_image" 
                        :value="old('banner_image')" 
                        label="Event Banner Image" 
                        help="Choose from the existing photo gallery or upload a new image (automatically optimized to WebP). If blank, defaults to the high-resolution Tech Day banner." 
                    />
                    @error('banner_image')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Schedule Timeline Builder --}}
            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 space-y-4 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                        <span>⏱️</span> Schedule Highlights
                    </h2>
                    <button type="button" 
                            @click="addRow()" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-stone-100 hover:bg-stone-200 text-stone-700 transition-colors cursor-pointer">
                        + Add Session
                    </button>
                </div>
                <p class="text-xs text-stone-500">Provide the agenda items for attendees. These are displayed as an interactive timeline on the public page.</p>

                <div class="space-y-3">
                    <template x-for="(item, index) in schedule" :key="index">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 p-3 bg-stone-50 border border-stone-200 rounded-xl">
                            <div class="w-full sm:w-1/4">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-stone-500 mb-1">Time Slot</label>
                                <input type="text" 
                                       :name="'schedule[' + index + '][time]'" 
                                       x-model="item.time" 
                                       placeholder="e.g. 09:00 AM - 10:00 AM" 
                                       class="w-full px-3 py-1.5 bg-white rounded-lg border border-stone-300 text-xs outline-none focus:border-maroon">
                            </div>
                            <div class="w-full sm:w-1/2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-stone-500 mb-1">Session / Activity Title</label>
                                <input type="text" 
                                       :name="'schedule[' + index + '][title]'" 
                                       x-model="item.title" 
                                       placeholder="e.g. Hands-On Student Coding Demos" 
                                       class="w-full px-3 py-1.5 bg-white rounded-lg border border-stone-300 text-xs outline-none focus:border-maroon">
                            </div>
                            <div class="w-full sm:w-1/4">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-stone-500 mb-1">Speaker / Lead</label>
                                <input type="text" 
                                       :name="'schedule[' + index + '][speaker]'" 
                                       x-model="item.speaker" 
                                       placeholder="e.g. SeedDev Mentors" 
                                       class="w-full px-3 py-1.5 bg-white rounded-lg border border-stone-300 text-xs outline-none focus:border-maroon">
                            </div>
                            <button type="button" 
                                    @click="removeRow(index)" 
                                    class="sm:mt-5 p-1.5 text-stone-400 hover:text-rose-600 rounded-lg transition-colors cursor-pointer" 
                                    title="Remove this slot">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Settings & SEO --}}
            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 space-y-6 shadow-xs">
                <h2 class="text-base font-bold text-stone-900 pb-3 border-b border-stone-100 flex items-center gap-2">
                    <span>⚙️</span> Publication & SEO Settings
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               id="is_registration_open" 
                               name="is_registration_open" 
                               value="1" 
                               {{ old('is_registration_open', '1') == '1' ? 'checked' : '' }} 
                               class="w-4 h-4 text-maroon rounded border-stone-300 focus:ring-maroon">
                        <label for="is_registration_open" class="text-sm font-semibold text-stone-800">
                            Open for Registration
                        </label>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }} 
                               class="w-4 h-4 text-maroon rounded border-stone-300 focus:ring-maroon">
                        <label for="is_active" class="text-sm font-semibold text-stone-800">
                            Published (Visible Publicly)
                        </label>
                    </div>

                    <div>
                        <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                            Display Order
                        </label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', '0') }}" 
                               min="0" 
                               class="w-full px-4 py-2 rounded-xl border border-stone-300 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-stone-100">
                    <div>
                        <label for="seo_title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            SEO Meta Title
                        </label>
                        <input type="text" 
                               id="seo_title" 
                               name="seo_title" 
                               value="{{ old('seo_title') }}" 
                               placeholder="Custom page title for search engines..." 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm">
                    </div>

                    <div>
                        <label for="seo_description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            SEO Meta Description
                        </label>
                        <input type="text" 
                               id="seo_description" 
                               name="seo_description" 
                               value="{{ old('seo_description') }}" 
                               placeholder="Custom search snippet description..." 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm">
                    </div>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.events.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-stone-600 hover:bg-stone-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg cursor-pointer">
                    Save Event
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
