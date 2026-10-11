<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Dzuels Educational Foundation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-stone-100 via-amber-50/30 to-stone-200 min-h-screen flex items-center justify-center p-4 antialiased font-sans">
    <div class="w-full max-w-md">
        <!-- Brand Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-stone-200/80 p-8 sm:p-10">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block p-2 rounded-2xl bg-stone-50 border border-stone-100 mb-4 hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('front/images/logo.png') }}" alt="Dzuels Educational Foundation" class="h-12 w-auto object-contain mx-auto">
                </a>
                <h1 class="text-2xl font-bold text-maroon tracking-tight">Staff Administration</h1>
                <p class="text-xs text-stone-500 mt-1">Dzuels Educational Foundation Portal</p>
            </div>

            <!-- Flash Status Messages -->
            @if (session('status'))
                <div class="mb-6 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Validation Error Alert -->
            @if ($errors->any())
                <div class="mb-6 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1">
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

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        autocomplete="email"
                        placeholder="admin@dzuelsfoundation.org"
                        class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all shadow-xs"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Password
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all shadow-xs"
                    >
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-stone-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-maroon focus:ring-maroon">
                        <span>Remember this device</span>
                    </label>

                    <a href="{{ route('admin.password.request') }}" class="text-maroon hover:underline font-semibold">
                        Forgot password?
                    </a>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl font-semibold text-sm bg-maroon text-white hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Sign in to Dashboard</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-stone-100 text-center">
                <a href="{{ url('/') }}" class="text-xs text-stone-500 hover:text-maroon transition-colors inline-flex items-center gap-1.5">
                    <span>← Return to Public Website</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
