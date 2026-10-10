<x-admin-layout title="Edit Partner">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Edit Partner Organization</h1>
                <p class="text-sm text-stone-500 mt-1">Update details for {{ $partner->name }}.</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
                &larr; Back to Partners
            </a>
        </div>

        <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
            @csrf
            @method('PUT')

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
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Organization Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $partner->name) }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- Logo File with Current Preview --}}
                <div class="space-y-2">
                    <label for="logo" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Partner Logo</label>
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-stone-50 border border-stone-200">
                        <div class="h-10 w-24 bg-white rounded p-1 flex items-center justify-center border border-stone-200 shrink-0">
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain" />
                        </div>
                        <span class="text-xs text-stone-500">Current logo</span>
                    </div>
                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml" 
                           class="w-full px-3.5 py-2 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200" />
                    <p class="text-[11px] text-stone-500">Leave blank to retain current logo. PNG, JPG, WebP, or SVG (max 4MB).</p>
                </div>

                {{-- Website URL --}}
                <div class="space-y-1.5">
                    <label for="website_url" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Website URL</label>
                    <input type="url" id="website_url" name="website_url" value="{{ old('website_url', $partner->website_url) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                           placeholder="https://example.org" />
                </div>

                {{-- Sort Order --}}
                <div class="space-y-1.5">
                    <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Display Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $partner->sort_order) }}" min="0" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- Active Status --}}
                <div class="pt-2">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $partner->is_active) ? 'checked' : '' }} 
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
                    Update Partner
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
