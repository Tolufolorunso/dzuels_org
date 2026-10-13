<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set New Password | Dzuels Educational Foundation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-stone-100 via-amber-50/30 to-stone-200 min-h-screen flex items-center justify-center p-4 antialiased font-sans">
    <div class="w-full max-w-md">
        <!-- Brand Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-stone-200/80 p-8 sm:p-10">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block p-2 rounded-2xl bg-stone-50 border border-stone-100 mb-4 hover:scale-105 transition-transform duration-200">
                    <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Dzuels Educational Foundation" class="h-12 w-auto object-contain mx-auto">
                </a>
                <h1 class="text-2xl font-bold text-maroon tracking-tight">Set New Password</h1>
                <p class="text-xs text-stone-500 mt-1">Choose a secure new password for your staff account.</p>
            </div>

            <!-- Validation Error Alert -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $email) }}" 
                        required 
                        autofocus
                        autocomplete="email"
                        class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all shadow-xs"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        New Password (Min 8 Characters)
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all shadow-xs"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Confirm New Password
                    </label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all shadow-xs"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl font-semibold text-sm bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Reset Password & Sign In</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-stone-100 text-center">
                <a href="{{ route('admin.login') }}" class="text-xs text-stone-500 hover:text-maroon transition-colors inline-flex items-center gap-1 font-semibold">
                    <span>← Cancel and return to Sign In</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
