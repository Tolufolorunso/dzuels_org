<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden max-w-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dzuels Educational Foundation | Quality Education & Skills Acquisition' }}</title>
    <meta name="description" content="{{ $description ?? 'Dzuels Educational Foundation is a Nigerian NGO addressing poverty through quality education, youth literacy interventions, and digital skills acquisition in Ekiti State and beyond.' }}">

    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Anti-flicker dark mode engine (executes synchronously before page render) -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('dzuels-theme');
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {
                // Fallback for private mode or blocked storage
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen w-full max-w-full overflow-x-hidden flex flex-col font-sans antialiased text-[var(--text)] bg-[var(--bg)] selection:bg-[var(--accent-cream)] selection:text-maroon">
    <!-- Top Utility Bar -->
    <x-utility-bar />

    <!-- Sticky Navigation Header -->
    <x-header />

    <!-- Main Content Area -->
    <main class="flex-grow w-full max-w-full overflow-x-hidden">
        {{ $slot }}
    </main>

    <!-- 4-Column Footer -->
    <x-footer />

    @stack('scripts')
</body>
</html>
