<footer class="bg-[var(--footer-bg)] text-[var(--footer-text)] transition-colors duration-200 mt-auto border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            <!-- Column 1: Organization Profile -->
            <div>
                <a href="{{ url('/') }}" class="inline-block mb-5">
                    <span class="inline-block bg-white p-2.5 rounded-xl shadow-xs">
                        <img src="{{ asset('front/images/logo.png') }}" alt="Dzuels Educational Foundation" class="h-10 w-auto object-contain">
                    </span>
                </a>
                <p class="text-[var(--footer-muted)] text-sm leading-relaxed mb-5">
                    Dzuels Educational Foundation is a registered Nigerian non-governmental organization founded in 2015, committed to poverty alleviation through quality education, literacy interventions, and digital skills acquisition.
                </p>
                <div class="flex items-start gap-2.5 text-xs text-[var(--accent-cream)] leading-normal">
                    <svg class="w-4 h-4 shrink-0 mt-0.5 text-[var(--accent-cream)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria</span>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-5">Quick Links</h3>
                <ul class="space-y-3 text-sm text-[var(--footer-muted)]">
                    <li>
                        <a href="{{ url('/about') }}" class="hover:text-[var(--accent-cream)] transition-colors">About Our Story</a>
                    </li>
                    <li>
                        <a href="{{ url('/about#trustees') }}" class="hover:text-[var(--accent-cream)] transition-colors">Board of Trustees</a>
                    </li>
                    <li>
                        <a href="{{ url('/about#management') }}" class="hover:text-[var(--accent-cream)] transition-colors">Management & Advisory</a>
                    </li>
                    <li>
                        <a href="{{ url('/gallery') }}" class="hover:text-[var(--accent-cream)] transition-colors">Photo Gallery</a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="hover:text-[var(--accent-cream)] transition-colors">Contact Us</a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Programmes & Giving -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-5">Programmes & Giving</h3>
                <ul class="space-y-3 text-sm text-[var(--footer-muted)]">
                    <li>
                        <a href="{{ url('/programmes') }}" class="hover:text-[var(--accent-cream)] transition-colors">Ijero Digital Literacy</a>
                    </li>
                    <li>
                        <a href="{{ url('/events') }}" class="hover:text-[var(--accent-cream)] transition-colors">Innovation & Tech Day</a>
                    </li>
                    <li>
                        <a href="{{ url('/gallery') }}" class="hover:text-[var(--accent-cream)] transition-colors">Reading Competitions</a>
                    </li>
                    <li>
                        <a href="{{ url('/gallery') }}" class="hover:text-[var(--accent-cream)] transition-colors">Spelling Bee Contests</a>
                    </li>
                    <li>
                        <a href="{{ url('/donate') }}" class="hover:text-[var(--accent-cream)] transition-colors font-medium text-[var(--accent-cream)]">Online Giving (Flutterwave)</a>
                    </li>
                    <li>
                        <a href="{{ url('/donate#bank-transfer') }}" class="hover:text-[var(--accent-cream)] transition-colors">GTBank Direct Transfer</a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Stay Connected -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-5">Stay Connected</h3>
                <p class="text-sm text-[var(--footer-muted)] leading-relaxed mb-4">
                    Subscribe for quarterly community impact stories, student achievements, and event invitations.
                </p>
                <form id="footerNewsletterForm" class="flex flex-col sm:flex-row gap-2" onsubmit="event.preventDefault(); document.getElementById('footerNewsletterMsg').classList.remove('hidden'); this.reset();">
                    <input 
                        type="email" 
                        required 
                        placeholder="Enter email address" 
                        aria-label="Email for newsletter"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-white/10 text-white placeholder-white/50 text-sm border border-white/20 focus:outline-none focus:ring-2 focus:ring-[var(--accent-cream)] focus:border-transparent transition-all"
                    >
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-lg bg-crimson hover:bg-crimson-hover text-white text-sm font-semibold transition-colors duration-200 cursor-pointer shrink-0 shadow-xs"
                    >
                        Join
                    </button>
                </form>
                <p id="footerNewsletterMsg" class="hidden text-xs text-[var(--accent-mint)] mt-2 font-medium">
                    ✓ Thank you for subscribing to Dzuels updates!
                </p>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="mt-14 pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-[var(--footer-muted)]">
            <p>© {{ date('Y') }} Dzuels Educational Foundation. All rights reserved. Registered in Nigeria.</p>
            <div class="flex items-center gap-6">
                <a href="{{ url('/privacy-policy') }}" class="hover:text-[var(--accent-cream)] transition-colors">Privacy Policy</a>
                <a href="{{ url('/contact') }}" class="hover:text-[var(--accent-cream)] transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
