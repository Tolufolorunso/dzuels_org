@props([
    'title' => 'Dzuels Educational Foundation | Quality Education & Skills Acquisition',
    'description' => 'Dzuels Educational Foundation is a Nigerian non-profit organisation addressing poverty through quality education, youth literacy interventions, and digital skills acquisition in Ekiti State and beyond.',
    'canonical' => null,
    'ogTitle' => null,
    'ogDescription' => null,
    'ogImage' => null,
    'ogType' => 'website',
    'twitterCard' => 'summary_large_image',
    'metaRobots' => 'index, follow',
    'keywords' => null,
    'breadcrumbs' => null,
])
@php
    $pageTitle = !empty($title) ? trim((string)$title) : 'Dzuels Educational Foundation | Quality Education & Skills Acquisition';
    $pageDescription = !empty($description) ? trim((string)$description) : 'Dzuels Educational Foundation is a Nigerian non-profit organisation addressing poverty through quality education, youth literacy interventions, and digital skills acquisition in Ekiti State and beyond.';
    $pageKeywords = !empty($keywords) ? trim((string)$keywords) : 'Dzuels Educational Foundation, Nigerian charity, education support, youth literacy, digital skills, Ijero-Ekiti, Ekiti State, Ijero Tech Day, ITD 2026, tech empowerment, children library, spelling bee, reading competition';
    $canonicalUrl = $canonical ?: url()->current();
    $socialTitle = $ogTitle ?: $pageTitle;
    $socialDescription = $ogDescription ?: $pageDescription;
    $socialImage = $ogImage ?: asset('front/images/cause.png');
    $socialType = $ogType ?: 'website';
    $robotsDirective = $metaRobots ?: 'index, follow';
    $googleVerification = \App\Models\Setting::get('google_site_verification') ?: env('GOOGLE_SITE_VERIFICATION');
    $bingVerification = \App\Models\Setting::get('bing_site_verification') ?: env('BING_SITE_VERIFICATION');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden max-w-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="author" content="Dzuels Educational Foundation">
    <meta name="robots" content="{{ $robotsDirective }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @if($googleVerification)
    <meta name="google-site-verification" content="{{ $googleVerification }}">
    @endif
    @if($bingVerification)
    <meta name="msvalidate.01" content="{{ $bingVerification }}">
    @endif

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#6A0406">

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="Dzuels Educational Foundation">
    <meta property="og:type" content="{{ $socialType }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $socialTitle }}">
    <meta property="og:description" content="{{ $socialDescription }}">
    <meta property="og:image" content="{{ $socialImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $socialTitle }}">
    <meta property="og:locale" content="en_NG">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:site" content="@dzuelsfoundation">
    <meta name="twitter:creator" content="@dzuelsfoundation">
    <meta name="twitter:title" content="{{ $socialTitle }}">
    <meta name="twitter:description" content="{{ $socialDescription }}">
    <meta name="twitter:image" content="{{ $socialImage }}">
    <meta name="twitter:image:alt" content="{{ $socialTitle }}">

    <!-- Structured Data (JSON-LD) -->
    <x-schema-organization />
    @if(!empty($breadcrumbs))
        <x-schema-breadcrumbs :items="$breadcrumbs" />
    @endif
    {{ $schema ?? '' }}

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
    <!-- Accessible Skip to Content Link -->
    <a href="#main-content" 
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-5 focus:py-3 focus:bg-maroon focus:text-white focus:rounded-full focus:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon font-semibold text-sm transition-transform">
        Skip to main content
    </a>

    <!-- Top Utility Bar -->
    <x-utility-bar />

    <!-- Sticky Navigation Header -->
    <x-header />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-grow w-full max-w-full overflow-x-hidden">
        {{ $slot }}
    </main>

    <!-- 4-Column Footer -->
    <x-footer />

    @stack('scripts')
</body>
</html>
