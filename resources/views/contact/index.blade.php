<x-layout 
    title="Contact Us | Get in Touch with Dzuels Educational Foundation"
    description="Get in touch with Dzuels Educational Foundation. Visit our headquarters in Ijero-Ekiti, call our hotlines, or send an inquiry directly to our team."
    :canonical="url('/contact')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Contact Us', 'url' => url('/contact')]
    ]">

    <div class="min-h-screen bg-[var(--bg)] transition-colors">
        {{-- 1. Hero Section --}}
        @php
            $heroSec = $sections['hero'] ?? null;
            $officeSec = $sections['office'] ?? null;
            $formSec = $sections['form'] ?? null;
            $mapSec = $sections['map'] ?? null;
            $heroBgImage = $heroSec?->image_url ?: asset('front/images/gallery/optimized/general-05.webp');
            $showForm = $formSec?->is_active ?? true;
            $showMap = $mapSec?->is_active ?? true;
        @endphp
        @if ($heroSec?->is_active ?? true)
        <section class="relative bg-maroon text-white py-16 sm:py-24 overflow-hidden">
            {{-- Contextual Background Imagery --}}
            <div class="absolute inset-0 z-0">
                <img src="{{ $heroBgImage }}" alt="" class="w-full h-full object-cover object-center transform scale-105" aria-hidden="true">
            </div>
            {{-- Rich Brand Color Gradient Overlay --}}
            <div class="absolute inset-0 z-0 pointer-events-none" 
                 style="background: linear-gradient(135deg, rgba(43, 2, 3, 0.88) 0%, rgba(106, 4, 6, 0.78) 50%, rgba(43, 2, 3, 0.85) 100%);"></div>
            <div class="absolute inset-0 z-0 pointer-events-none" 
                 style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0.40) 0%, transparent 45%, rgba(43, 2, 3, 0.85) 100%);"></div>
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
                <div class="absolute inset-0 bg-[radial-gradient(#F9DEC9_1px,transparent_1px)] [background-size:24px_24px]"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                {{-- Breadcrumbs --}}
                <nav class="flex items-center justify-center gap-2 text-sm text-cream/80 mb-6" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1.5 focus:outline-none focus:underline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Home</span>
                    </a>
                    <span class="text-cream/50" aria-hidden="true">/</span>
                    <span class="text-white font-semibold" aria-current="page">Contact Us</span>
                </nav>

                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs mb-6">
                    <span>💬</span> {!! format_cms_text($heroSec?->subheadline ?: 'Get in Touch') !!}
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight max-w-4xl mx-auto leading-tight">
                    {!! format_cms_text($heroSec?->headline ?: 'We Are Always Available to Connect With You') !!}
                </h1>

                <p class="mt-6 text-lg sm:text-xl text-white/90 max-w-2xl mx-auto leading-relaxed">
                    {!! format_cms_text($heroSec?->body_text ?: 'Have a question regarding our youth literacy initiatives, school computer labs, or community partnerships? Send us a message or visit our headquarters in Ijero-Ekiti.') !!}
                </p>
            </div>
        </section>
        @endif

        {{-- 2. Contact Information Cards Grid --}}
        @if ($officeSec?->is_active ?? true)
        <section class="py-12 border-b border-[var(--border)] bg-[var(--bg)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Card 1: Visit Us --}}
                    <div class="p-6 rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                📍
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] block mb-1">
                                {!! format_cms_text($officeSec?->subheadline ?: 'Headquarters') !!}
                            </span>
                            <h3 class="text-base font-bold text-[var(--heading)] mb-2">
                                {!! format_cms_text($officeSec?->headline ?: 'Visit Our Office') !!}
                            </h3>
                            <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                                {{ $address }}
                            </p>
                        </div>
                    </div>

                    {{-- Card 2: Phone Hotlines --}}
                    <div class="p-6 rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                📞
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] block mb-1">
                                Direct Lines
                            </span>
                            <h3 class="text-base font-bold text-[var(--heading)] mb-2">
                                Call Us
                            </h3>
                            <div class="space-y-1.5 text-sm">
                                @forelse($phoneNumbers as $phone)
                                    <div>
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="font-medium text-maroon dark:text-[var(--accent-cream)] hover:underline">
                                            {{ $phone }}
                                        </a>
                                    </div>
                                @empty
                                    <div>
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone1) }}" class="font-medium text-maroon dark:text-[var(--accent-cream)] hover:underline">
                                            {{ $phone1 }}
                                        </a>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Email Address --}}
                    <div class="p-6 rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                ✉️
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] block mb-1">
                                Electronic Mail
                            </span>
                            <h3 class="text-base font-bold text-[var(--heading)] mb-2">
                                Email Us
                            </h3>
                            <p class="text-sm">
                                <a href="mailto:{{ $email }}" class="font-medium text-maroon dark:text-[var(--accent-cream)] hover:underline break-all">
                                    {{ $email }}
                                </a>
                            </p>
                        </div>
                    </div>

                    {{-- Card 4: Working Hours --}}
                    <div class="p-6 rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                ⏰
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] block mb-1">
                                Working Hours
                            </span>
                            <h3 class="text-base font-bold text-[var(--heading)] mb-2">
                                Operating Schedule
                            </h3>
                            <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                                {{ $workingHours }} ({{ $timezone }})<br>
                                {{ $workingDaysNote }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- 3. Form & Map Section --}}
        @if ($showForm || $showMap)
        <section class="py-12 lg:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- Flash Success Alert --}}
                @if(session('success'))
                    <div class="mb-10 p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 flex items-start gap-4 shadow-sm animate-fade-in">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 flex items-center justify-center text-emerald-700 dark:text-emerald-300 text-xl shrink-0">
                            ✓
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold">Message Sent Successfully!</h3>
                            <p class="text-sm mt-1 text-emerald-800 dark:text-emerald-300 leading-relaxed">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Validation Errors Summary --}}
                @if($errors->any())
                    <div class="mb-10 p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 flex items-start gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/60 flex items-center justify-center text-rose-700 dark:text-rose-300 text-xl shrink-0">
                            ⚠
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold">Please correct the following errors:</h3>
                            <ul class="text-sm mt-1 list-disc list-inside space-y-1 text-rose-800 dark:text-rose-300">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    
                    {{-- Left Column: Contact Form --}}
                    @if ($showForm)
                    <div class="{{ $showMap ? 'lg:col-span-7' : 'lg:col-span-12 max-w-3xl mx-auto' }} bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-10 shadow-sm transition-colors">
                        <div class="mb-8 border-b border-[var(--border)] pb-6">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                                {!! format_cms_text($formSec?->headline ?: 'Send Us a Message') !!}
                            </h2>
                            <p class="text-sm text-[var(--text-muted)] mt-1.5 leading-relaxed">
                                {!! format_cms_text($formSec?->body_text ?: 'Fill out the form below and a representative from Dzuels Educational Foundation will get in touch with you promptly.') !!}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                            @csrf

                            {{-- Honeypot Anti-Spam Hidden Input --}}
                            <div style="display:none !important;" aria-hidden="true">
                                <label for="_hp_company">Company Name (Leave blank)</label>
                                <input type="text" id="_hp_company" name="_hp_company" value="" tabindex="-1" autocomplete="off">
                            </div>

                            {{-- First Name & Last Name --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        First Name <span class="text-crimson" aria-hidden="true">*</span>
                                    </label>
                                    <input type="text" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name') }}" 
                                           placeholder="e.g. Samuel"
                                           required
                                           aria-required="true"
                                           @error('first_name') aria-invalid="true" aria-describedby="first_name_error" @enderror
                                           class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('first_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                    @error('first_name')
                                        <p id="first_name_error" class="mt-1.5 text-xs text-rose-500 font-medium" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        Last Name <span class="text-crimson" aria-hidden="true">*</span>
                                    </label>
                                    <input type="text" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name') }}" 
                                           placeholder="e.g. Adeleke"
                                           required
                                           aria-required="true"
                                           @error('last_name') aria-invalid="true" aria-describedby="last_name_error" @enderror
                                           class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('last_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                    @error('last_name')
                                        <p id="last_name_error" class="mt-1.5 text-xs text-rose-500 font-medium" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email & Phone --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        Email Address <span class="text-crimson" aria-hidden="true">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="you@example.com"
                                           required
                                           aria-required="true"
                                           @error('email') aria-invalid="true" aria-describedby="email_error" @enderror
                                           class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('email') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                    @error('email')
                                        <p id="email_error" class="mt-1.5 text-xs text-rose-500 font-medium" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        Phone Number <span class="text-xs font-normal text-[var(--text-muted)]">(Optional)</span>
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="e.g. 0802 123 4567"
                                           @error('phone') aria-invalid="true" aria-describedby="phone_error" @enderror
                                           class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('phone') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                    @error('phone')
                                        <p id="phone_error" class="mt-1.5 text-xs text-rose-500 font-medium" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                    Subject <span class="text-crimson" aria-hidden="true">*</span>
                                </label>
                                <input type="text" 
                                       id="subject" 
                                       name="subject" 
                                       value="{{ old('subject') }}" 
                                       placeholder="e.g. Inquiry regarding school literacy sponsorship"
                                       required
                                       aria-required="true"
                                       @error('subject') aria-invalid="true" aria-describedby="subject_error" @enderror
                                       class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('subject') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                @error('subject')
                                    <p id="subject_error" class="mt-1.5 text-xs text-rose-500 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Message Content --}}
                            <div>
                                <label for="message" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                    Message <span class="text-crimson" aria-hidden="true">*</span>
                                </label>
                                <textarea id="message" 
                                          name="message" 
                                          rows="5" 
                                          required
                                          aria-required="true"
                                          placeholder="Please write your message, question, or proposal in detail..."
                                          @error('message') aria-invalid="true" aria-describedby="message_error" @enderror
                                          class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('message') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">{{ old('message') }}</textarea>
                                @error('message')
                                    <p id="message_error" class="mt-1.5 text-xs text-rose-500 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Privacy Notice --}}
                            <p class="text-xs text-[var(--text-muted)] flex items-start gap-1.5 leading-relaxed">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-mint shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>We respect your privacy. Information submitted is handled strictly in accordance with our <a href="{{ route('privacy-policy') }}" class="underline hover:text-maroon dark:hover:text-[var(--accent-cream)] font-medium">Privacy Policy</a>.</span>
                            </p>

                            {{-- Submit Button --}}
                            <div>
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-bold text-sm bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-maroon focus:ring-offset-2">
                                    <span>Send Message</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    {{-- Right Column: Interactive Google Map & Direction Info --}}
                    @if ($showMap)
                    <div class="{{ $showForm ? 'lg:col-span-5' : 'lg:col-span-12 max-w-3xl mx-auto' }} space-y-8">
                        {{-- Map Card --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8 shadow-sm transition-colors overflow-hidden">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-[var(--heading)]">
                                    {!! format_cms_text($mapSec?->headline ?: 'Headquarters Location') !!}
                                </h3>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-mint border border-emerald-200 dark:border-emerald-800">
                                    <span>📍</span> Ijero-Ekiti
                                </span>
                            </div>

                            <p class="text-sm text-[var(--text-muted)] mb-5 leading-relaxed">
                                {!! format_cms_text($mapSec?->body_text ?: 'Our central educational center and community operations are based in Ijero-Ekiti, Ekiti State, Nigeria.') !!}
                            </p>

                            {{-- Responsive Google Map Embed --}}
                            <div class="rounded-2xl overflow-hidden border border-[var(--border)] shadow-xs aspect-4/3 w-full relative bg-[var(--bg)]">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1291.2096870803634!2d5.065578828037818!3d7.806634035569526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104801d12e41e72f%3A0xe14703dfff48525c!2s12%20Doherty%20St%2C%20372101%2C%20Ijero%2C%20Ekiti%2C%20Nigeria!5e0!3m2!1sen!2suk!4v1696624906667!5m2!1sen!2suk" 
                                    class="w-full h-full border-0" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Dzuels Educational Foundation Ijero-Ekiti Map">
                                </iframe>
                            </div>

                            <div class="mt-5 p-4 rounded-2xl bg-[var(--bg)] border border-[var(--border)] text-xs text-[var(--text-muted)] leading-relaxed">
                                <strong>Visiting Hours:</strong> {!! format_cms_text($mapSec?->subheadline ?: 'Physical visits by appointment are warmly welcomed. Please call in advance so our field coordinators can assist with your schedule.') !!}
                            </div>
                        </div>

                        {{-- Alternative Options Card --}}
                        <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-maroon to-crimson text-white shadow-md">
                            <h3 class="text-xl font-bold text-white mb-2">Looking to Volunteer or Partner?</h3>
                            <p class="text-sm text-[var(--accent-cream)]/90 mb-5 leading-relaxed">
                                We also maintain dedicated pathways for prospective volunteer mentors and institutional corporate sponsors.
                            </p>
                            <a href="{{ route('get-involved') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all shadow-xs">
                                <span>Get Involved Page</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </section>
        @endif
    </div>
    </div>
</x-layout>
