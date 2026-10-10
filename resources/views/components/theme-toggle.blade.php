<button 
    id="themeToggleBtn" 
    type="button" 
    aria-label="Toggle light and dark theme" 
    title="Toggle color theme"
    class="relative inline-flex items-center justify-center w-10 h-10 rounded-full border border-[var(--border)] bg-[var(--surface-alt)] text-[var(--text-strong)] hover:bg-[var(--accent-cream)] hover:text-maroon hover:border-[var(--accent-cream)] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-maroon cursor-pointer shadow-sm"
>
    <!-- Sun icon (shown when dark) -->
    <svg id="themeSunIcon" class="hidden w-5 h-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <!-- Moon icon (shown when light) -->
    <svg id="themeMoonIcon" class="w-5 h-5 text-[var(--heading)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>

<script>
    (function() {
        const toggleBtn = document.getElementById('themeToggleBtn');
        const sunIcon = document.getElementById('themeSunIcon');
        const moonIcon = document.getElementById('themeMoonIcon');

        function updateIcons(isDark) {
            if (sunIcon && moonIcon) {
                if (isDark) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }
        }

        // Initialize icons to match root class
        updateIcons(document.documentElement.classList.contains('dark'));

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('dzuels-theme', isDark ? 'dark' : 'light');
                updateIcons(isDark);
            });
        }
    })();
</script>
