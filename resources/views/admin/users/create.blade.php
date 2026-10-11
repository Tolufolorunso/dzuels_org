<x-admin-layout title="Create Staff Account">
    <div class="max-w-3xl mx-auto space-y-8">
        <!-- Breadcrumb & Top Bar -->
        <div class="flex items-center justify-between pb-6 border-b border-stone-200">
            <div>
                <nav class="flex items-center gap-2 text-xs text-stone-400 mb-2">
                    <a href="{{ route('admin.users.index') }}" class="hover:text-stone-700 transition-colors">Users</a>
                    <span>/</span>
                    <span class="text-stone-900 font-bold">New Account</span>
                </nav>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Create New Staff Account</h1>
                <p class="text-sm text-stone-500 mt-1">Add a new portal user and assign appropriate access privileges.</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-white border border-stone-300 text-stone-700 hover:bg-stone-50 transition-colors">
                <span>← All Users</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                           placeholder="e.g. Samuel Adeyemi">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none" 
                           placeholder="staff@dzuelsfoundation.org">
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        Authorization Role <span class="text-rose-500">*</span>
                    </label>
                    <select id="role" 
                            name="role" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none bg-white">
                        <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Content Editor (Manage Pages, Events, Programmes, Gallery)</option>
                        <option value="support" {{ old('role') === 'support' ? 'selected' : '' }}>Support Staff (View Inquiries, Messages, Registrations)</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Administrator (Full platform access, Users, Settings)</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passwords -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                    <div>
                        <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Password (Min 8 Characters) <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none">
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none">
                    </div>
                </div>

                <!-- Active Status -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }} 
                               class="w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon">
                        <span class="text-xs font-bold text-stone-700">Account is Active and allowed to log in immediately</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-stone-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-stone-900 transition-colors shadow-xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Create Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
