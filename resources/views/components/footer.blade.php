<footer class="bg-[var(--footer-bg)] text-[var(--footer-text)] transition-colors duration-200 mt-auto border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            <!-- Column 1: Organization Profile -->
            <div>
                <a href="{{ url('/') }}" class="inline-block mb-5">
                    <span class="inline-block bg-white p-2.5 rounded-xl shadow-xs">
                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Dzuels Educational Foundation" class="h-10 w-auto object-contain">
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
                    <span>{{ \App\Models\Setting::get('contact_address', '12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria') }}</span>
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
                        <a href="{{ url('/programmes/ijero-digital-literacy') }}" class="hover:text-[var(--accent-cream)] transition-colors">Ijero Digital Literacy</a>
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

            <!-- Column 4: Get In Touch -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-5">Get In Touch</h3>
                <p class="text-sm text-[var(--footer-muted)] leading-relaxed mb-4">
                    Have questions, want to volunteer, or interested in partnering with our educational initiatives?
                </p>
                <div class="space-y-3 text-xs text-[var(--footer-muted)]">
                    @php
                        $footerEmail = \App\Models\Setting::get('contact_email', 'info@dzuelsfoundation.org');
                        $footerPhone = \App\Models\Setting::getPrimaryPhone();
                        $footerPhoneClean = preg_replace('/[^0-9+]/', '', $footerPhone);
                    @endphp
                    <p class="flex items-center gap-2">
                        <span class="text-[var(--accent-cream)] font-semibold">Email:</span>
                        <a href="mailto:{{ $footerEmail }}" class="hover:text-white transition-colors">{{ $footerEmail }}</a>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="text-[var(--accent-cream)] font-semibold">Phone:</span>
                        <a href="tel:{{ $footerPhoneClean }}" class="hover:text-white transition-colors">{{ $footerPhone }}</a>
                    </p>
                    <div class="pt-2">
                        <a href="{{ url('/get-involved') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold bg-crimson hover:bg-crimson-hover text-white transition-colors shadow-xs">
                            <span>Volunteer or Partner</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
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
