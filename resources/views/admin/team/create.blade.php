<x-admin-layout title="Add Team Member">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Add Team Member</h1>
                <p class="text-sm text-stone-500 mt-1">Create a profile for a Trustee, Management staff, or Advisory Board member.</p>
            </div>
            <a href="{{ route('admin.team.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
                &larr; Back to Team
            </a>
        </div>

        <form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                           placeholder="e.g. Bolaji Agbelusi" />
                </div>

                {{-- Role --}}
                <div class="space-y-1.5">
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Role / Title *</label>
                    <input type="text" id="role" name="role" value="{{ old('role') }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                           placeholder="e.g. Trustee, Country Manager (USA), Advisor" />
                </div>

                {{-- Group --}}
                <div class="space-y-1.5">
                    <label for="group" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Category / Group *</label>
                    <select id="group" name="group" required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors bg-white">
                        <option value="trustee" {{ old('group') === 'trustee' ? 'selected' : '' }}>Board of Trustees</option>
                        <option value="management" {{ old('group') === 'management' ? 'selected' : '' }}>Management</option>
                        <option value="advisor" {{ old('group') === 'advisor' ? 'selected' : '' }}>Advisory Board</option>
                    </select>
                </div>

                {{-- Field (Domain) --}}
                <div class="space-y-1.5">
                    <label for="field" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Field / Advisory Domain</label>
                    <input type="text" id="field" name="field" value="{{ old('field') }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                           placeholder="e.g. Education Services, Medical Services" />
                </div>

                {{-- Sort Order --}}
                <div class="space-y-1.5">
                    <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Display Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- LinkedIn URL --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="linkedin_url" class="block text-xs font-bold uppercase tracking-wider text-stone-700">LinkedIn Profile URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                           placeholder="https://www.linkedin.com/in/username" />
                </div>

                {{-- Photo Upload --}}
                <div class="sm:col-span-2">
                    <x-admin.media-picker 
                        name="photo" 
                        :value="old('photo')" 
                        label="Profile Photo" 
                        help="Choose from existing gallery albums or upload and auto-optimize a new profile photo (WebP <= 180 KB)." />
                    @error('photo')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bio --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="bio" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Short Bio</label>
                    <textarea id="bio" name="bio" rows="3" 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" 
                              placeholder="Brief biography or professional background...">{{ old('bio') }}</textarea>
                </div>

                {{-- Active Status --}}
                <div class="sm:col-span-2 pt-2">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-maroon rounded border-stone-300 focus:ring-maroon" />
                        <span class="text-xs font-semibold text-stone-800">Publish this profile on the public website immediately</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.team.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm cursor-pointer">
                    Save Team Member
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
