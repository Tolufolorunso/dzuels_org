<x-admin-layout title="Add Partner">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Add Partner Organization</h1>
                <p class="text-sm text-stone-500 mt-1">Upload a partner logo and details for public display.</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
                &larr; Back to Partners
            </a>
        </div>

        <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
            @csrf

            {{-- Validation Errors Summary --}}
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="list-disc pl-5 mt-1 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-5">
                {{-- Organization Name --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Organization Name *</label>
                        <x-admin.ai-button 
                            target="#name" 
                            field-name="name" 
                            field-label="Partner Organization Name" 
                            page-context="partners" 
                            section-context="admin_partners_create" 
                        />
                    </div>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                           placeholder="e.g. ASCEND Education Foundation" />
                </div>

                {{-- Partner Logo via Media Picker --}}
                <div>
                    <x-admin.media-picker 
                        name="logo" 
                        :value="old('logo')" 
                        label="Partner Logo" 
                        :required="true"
                        help="Choose from the media gallery or upload a new logo (automatically optimized to WebP). Transparent PNG, SVG, or WebP recommended." 
                    />
                    @error('logo')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Website URL --}}
                <div class="space-y-1.5">
                    <label for="website_url" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Website URL</label>
                    <input type="url" id="website_url" name="website_url" value="{{ old('website_url') }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                           placeholder="https://example.org" />
                </div>

                {{-- Sort Order --}}
                <div class="space-y-1.5">
                    <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Display Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- Active Status --}}
                <div class="pt-2">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-maroon rounded border-stone-300 focus:ring-maroon" />
                        <span class="text-xs font-semibold text-stone-800">Display this partner on the public website</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.partners.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm cursor-pointer">
                    Save Partner
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
