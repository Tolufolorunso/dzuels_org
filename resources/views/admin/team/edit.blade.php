<x-admin-layout title="Edit Team Member">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900">Edit Team Member</h1>
                <p class="text-sm text-stone-500 mt-1">Update profile information for {{ $member->name }}.</p>
            </div>
            <a href="{{ route('admin.team.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
                &larr; Back to Team
            </a>
        </div>

        <form method="POST" action="{{ route('admin.team.update', $member) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- Role --}}
                <div class="space-y-1.5">
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Role / Title *</label>
                    <input type="text" id="role" name="role" value="{{ old('role', $member->role) }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- Group --}}
                <div class="space-y-1.5">
                    <label for="group" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Category / Group *</label>
                    <select id="group" name="group" required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors bg-white">
                        <option value="trustee" {{ old('group', $member->group) === 'trustee' ? 'selected' : '' }}>Board of Trustees</option>
                        <option value="management" {{ old('group', $member->group) === 'management' ? 'selected' : '' }}>Management</option>
                        <option value="advisor" {{ old('group', $member->group) === 'advisor' ? 'selected' : '' }}>Advisory Board</option>
                    </select>
                </div>

                {{-- Field (Domain) --}}
                <div class="space-y-1.5">
                    <label for="field" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Field / Advisory Domain</label>
                    <input type="text" id="field" name="field" value="{{ old('field', $member->field) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- Sort Order --}}
                <div class="space-y-1.5">
                    <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Display Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $member->sort_order) }}" min="0" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- LinkedIn URL --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="linkedin_url" class="block text-xs font-bold uppercase tracking-wider text-stone-700">LinkedIn Profile URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors" />
                </div>

                {{-- Photo Upload & Current Photo --}}
                <div class="sm:col-span-2 space-y-2">
                    <label for="photo" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Profile Photo</label>
                    @if ($member->photo_url)
                        <div class="flex items-center gap-4 p-3 rounded-xl bg-stone-50 border border-stone-200">
                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-14 h-14 rounded-full object-cover border border-stone-200" />
                            <div class="space-y-1">
                                <span class="text-xs text-stone-700 font-medium block">Current photo attached</span>
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-600 hover:text-rose-700">
                                    <input type="checkbox" name="remove_photo" value="1" class="w-3.5 h-3.5 text-rose-600 rounded border-stone-300 focus:ring-rose-500" />
                                    <span>Remove current photo</span>
                                </label>
                            </div>
                        </div>
                    @endif
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" 
                           class="w-full px-3.5 py-2 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200" />
                    <p class="text-[11px] text-stone-500">Leave blank to keep existing photo. Supports JPEG, PNG, or WebP (max 4MB).</p>
                </div>

                {{-- Bio --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="bio" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Short Bio</label>
                    <textarea id="bio" name="bio" rows="3" 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-maroon focus:ring-1 focus:ring-maroon text-sm outline-none transition-colors">{{ old('bio', $member->bio) }}</textarea>
                </div>

                {{-- Active Status --}}
                <div class="sm:col-span-2 pt-2">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }} 
                               class="w-4 h-4 text-maroon rounded border-stone-300 focus:ring-maroon" />
                        <span class="text-xs font-semibold text-stone-800">Publish this profile on the public website</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.team.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-all shadow-sm cursor-pointer">
                    Update Team Member
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
