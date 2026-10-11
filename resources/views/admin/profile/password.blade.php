<x-admin-layout title="Profile & Security">
    <div class="max-w-3xl mx-auto space-y-8">
        <!-- Top Heading -->
        <div class="flex items-center justify-between pb-6 border-b border-stone-200">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 mb-2">
                    <span>🛡️</span> Security & Credentials
                </div>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Profile & Password Settings</h1>
                <p class="text-sm text-stone-500 mt-1">Manage your administrator account security and change your sign-in password.</p>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-white border border-stone-300 text-stone-700 hover:bg-stone-50 transition-colors">
                <span>← Dashboard</span>
            </a>
        </div>

        <!-- Account Profile Overview Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Account Overview</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div class="bg-stone-50 p-4 rounded-2xl border border-stone-200/60">
                    <span class="text-[10px] uppercase font-bold text-stone-400 block tracking-wider">Staff Name</span>
                    <span class="text-sm font-bold text-stone-900 block mt-1">{{ $user->name }}</span>
                </div>

                <div class="bg-stone-50 p-4 rounded-2xl border border-stone-200/60">
                    <span class="text-[10px] uppercase font-bold text-stone-400 block tracking-wider">Email Address</span>
                    <span class="text-sm font-bold text-stone-900 block mt-1 truncate">{{ $user->email }}</span>
                </div>

                <div class="bg-stone-50 p-4 rounded-2xl border border-stone-200/60">
                    <span class="text-[10px] uppercase font-bold text-stone-400 block tracking-wider">Portal Role</span>
                    <div class="mt-1">
                        @if ($user->role === 'super_admin' || $user->role === 'admin')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-maroon border border-rose-200">
                                Super Administrator
                            </span>
                        @elseif ($user->role === 'editor')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                                Content Editor
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-700 border border-teal-200">
                                Support Staff
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
            <div class="border-b border-stone-100 pb-4">
                <h2 class="text-base font-bold text-stone-900">Change Your Password</h2>
                <p class="text-xs text-stone-500 mt-1">Ensure your password is at least 8 characters long with a mix of numbers and letters.</p>
            </div>

            <!-- Form Errors Banner -->
            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-medium space-y-1">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        Current Password <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           required 
                           autocomplete="current-password"
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none">
                    @error('current_password')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                    <div>
                        <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            New Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Min 8 characters"
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none">
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Confirm New Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Re-type new password"
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-stone-900 text-sm focus:border-maroon focus:ring-1 focus:ring-maroon focus:outline-none">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-stone-100 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold bg-maroon text-white hover:bg-stone-900 transition-colors shadow-xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Update My Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
