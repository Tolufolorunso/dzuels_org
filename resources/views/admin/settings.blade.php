<x-admin-layout>
    <div class="space-y-8">
        <!-- Top Heading & Overview -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-stone-200">
            <div>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Site Settings & Configuration</h1>
                <p class="text-sm text-stone-500 mt-1">Manage verified impact metrics, hotlines, Flutterwave giving link, and banking details.</p>
            </div>
            
            @if(($settings['stat_feed_mode'] ?? 'manual') === 'api' && !empty($settings['stat_api_endpoint']))
                <form method="POST" action="{{ route('admin.settings.sync') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-stone-900 text-white hover:bg-stone-800 transition-colors shadow-xs cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Sync from API Now</span>
                    </button>
                </form>
            @endif
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

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- SECTION 1: Brand Identity & Site Logo -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Brand Identity & Official Site Logo</h2>
                        <p class="text-xs text-stone-500">Logo displayed on public navigation bars, footers, auth screens, and admin dashboard</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                    <div class="lg:col-span-2">
                        <x-admin.media-picker 
                            name="site_logo" 
                            :value="old('site_logo', $settings['site_logo'] ?? 'front/images/logo.png')" 
                            label="Official Site Logo" 
                            help="Select an existing logo from the media library or upload a new logo (automatically optimized). Transparent PNG, SVG, or WebP recommended. If unset, automatically defaults to front/images/logo.png."
                        />
                    </div>
                    <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200/60 flex flex-col items-center justify-center text-center space-y-3">
                        <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Active Logo Preview</span>
                        <div class="p-3 bg-white rounded-xl shadow-2xs border border-stone-200 inline-flex items-center justify-center min-h-[70px] min-w-[140px]">
                            <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Site Logo Preview" class="h-10 w-auto object-contain max-w-full">
                        </div>
                        <p class="text-[11px] text-stone-400">Current live brand logo rendered across the application.</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Verified Community Impact Metrics -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between flex-wrap gap-2 pb-4 border-b border-stone-100">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">2</span>
                        <div>
                            <h2 class="text-base font-bold text-stone-900">Verified Community Impact Metrics</h2>
                            <p class="text-xs text-stone-500">Key performance numbers displayed on the homepage impact band</p>
                        </div>
                    </div>
                    <!-- Live Source Badge -->
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $activeStats['source'] === 'api' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                        Current Source: {{ strtoupper($activeStats['source']) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="stat_ict_students" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Students in ICT Training
                        </label>
                        <input 
                            id="stat_ict_students" 
                            type="number" 
                            name="stat_ict_students" 
                            min="0"
                            value="{{ old('stat_ict_students', $settings['stat_ict_students'] ?? 165) }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                        <p class="text-[11px] text-stone-500 mt-1">Verified baseline: 165 students</p>
                    </div>

                    <div>
                        <label for="stat_literacy_students" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Students in Literacy Interventions
                        </label>
                        <input 
                            id="stat_literacy_students" 
                            type="number" 
                            name="stat_literacy_students" 
                            min="0"
                            value="{{ old('stat_literacy_students', $settings['stat_literacy_students'] ?? 591) }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                        <p class="text-[11px] text-stone-500 mt-1">Verified baseline: 591 students</p>
                    </div>

                    <div>
                        <label for="stat_schools_supported" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Schools Supported
                        </label>
                        <input 
                            id="stat_schools_supported" 
                            type="number" 
                            name="stat_schools_supported" 
                            min="0"
                            value="{{ old('stat_schools_supported', $settings['stat_schools_supported'] ?? 15) }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                        <p class="text-[11px] text-stone-500 mt-1">Verified baseline: 15 schools</p>
                    </div>
                </div>

                <!-- Feed Mode Toggle & Endpoint -->
                <div class="pt-4 border-t border-stone-100 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Metric Update Mode
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-stone-200 cursor-pointer hover:bg-stone-50 has-checked:border-maroon has-checked:bg-amber-50/40">
                                <input type="radio" name="stat_feed_mode" value="manual" {{ old('stat_feed_mode', $settings['stat_feed_mode'] ?? 'manual') === 'manual' ? 'checked' : '' }} class="text-maroon focus:ring-maroon">
                                <span class="text-xs font-semibold text-stone-800">Manual Entry</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-stone-200 cursor-pointer hover:bg-stone-50 has-checked:border-maroon has-checked:bg-amber-50/40">
                                <input type="radio" name="stat_feed_mode" value="api" {{ old('stat_feed_mode', $settings['stat_feed_mode'] ?? 'manual') === 'api' ? 'checked' : '' }} class="text-maroon focus:ring-maroon">
                                <span class="text-xs font-semibold text-stone-800">External API Feed</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="stat_api_endpoint" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            External API JSON Endpoint (Optional)
                        </label>
                        <input 
                            id="stat_api_endpoint" 
                            type="url" 
                            name="stat_api_endpoint" 
                            placeholder="https://api.example.com/dzuels/metrics"
                            value="{{ old('stat_api_endpoint', $settings['stat_api_endpoint'] ?? '') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                        <p class="text-[11px] text-stone-500 mt-1">If enabled, queries JSON keys <code>ict_students</code>, <code>literacy_students</code>, <code>schools_supported</code> with offline fallback.</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Organization Contacts -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Contact & Organization Information</h2>
                        <p class="text-xs text-stone-500">Official channels displayed on the header, utility bar, and contact page</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-3">Telephone Hotlines (Up to 5 Lines)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div>
                                <label for="contact_phone_1" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Primary Hotline *
                                </label>
                                <input 
                                    id="contact_phone_1" 
                                    type="text" 
                                    name="contact_phone_1" 
                                    value="{{ old('contact_phone_1', $settings['contact_phone_1'] ?? '+234 904 249 3883') }}" 
                                    required 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="+234 904 249 3883"
                                >
                                <p class="text-[11px] text-stone-500 mt-1">Main number on utility bar & footer</p>
                            </div>

                            <div>
                                <label for="contact_phone_2" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Phone Line 2 (Optional)
                                </label>
                                <input 
                                    id="contact_phone_2" 
                                    type="text" 
                                    name="contact_phone_2" 
                                    value="{{ old('contact_phone_2', $settings['contact_phone_2'] ?? '') }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="+234 810 000 0000"
                                >
                            </div>

                            <div>
                                <label for="contact_phone_3" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Phone Line 3 (Optional)
                                </label>
                                <input 
                                    id="contact_phone_3" 
                                    type="text" 
                                    name="contact_phone_3" 
                                    value="{{ old('contact_phone_3', $settings['contact_phone_3'] ?? '') }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="+234 700 000 0000"
                                >
                            </div>

                            <div>
                                <label for="contact_phone_4" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Phone Line 4 (Optional)
                                </label>
                                <input 
                                    id="contact_phone_4" 
                                    type="text" 
                                    name="contact_phone_4" 
                                    value="{{ old('contact_phone_4', $settings['contact_phone_4'] ?? '') }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="+234 800 000 0000"
                                >
                            </div>

                            <div>
                                <label for="contact_phone_5" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Phone Line 5 (Optional)
                                </label>
                                <input 
                                    id="contact_phone_5" 
                                    type="text" 
                                    name="contact_phone_5" 
                                    value="{{ old('contact_phone_5', $settings['contact_phone_5'] ?? '') }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="+234 900 000 0000"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-stone-100">
                        <h3 class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-3">Email & Physical Address</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="contact_email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Official Inquiries Email *
                                </label>
                                <input 
                                    id="contact_email" 
                                    type="email" 
                                    name="contact_email" 
                                    value="{{ old('contact_email', $settings['contact_email'] ?? 'info@dzuelsfoundation.org') }}" 
                                    required 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                >
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="contact_address" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                                        Physical Headquarters Address *
                                    </label>
                                    <x-admin.ai-button 
                                        for="contact_address" 
                                        field-name="contact_address" 
                                        field-label="Headquarters Address" 
                                        page-context="Site Settings" 
                                        section-context="Foundation Secretariat" 
                                    />
                                </div>
                                <input 
                                    id="contact_address" 
                                    type="text" 
                                    name="contact_address" 
                                    value="{{ old('contact_address', $settings['contact_address'] ?? '12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria') }}" 
                                    required 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-stone-100">
                        <h3 class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-3">Operating Schedule & Timezone</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="contact_working_hours" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                                        Working Hours
                                    </label>
                                    <x-admin.ai-button 
                                        for="contact_working_hours" 
                                        field-name="contact_working_hours" 
                                        field-label="Working Hours" 
                                        page-context="Site Settings" 
                                        section-context="Operating Schedule" 
                                    />
                                </div>
                                <input 
                                    id="contact_working_hours" 
                                    type="text" 
                                    name="contact_working_hours" 
                                    value="{{ old('contact_working_hours', $settings['contact_working_hours'] ?? 'Mon – Fri: 8:00 AM – 5:00 PM') }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="Mon – Fri: 8:00 AM – 5:00 PM"
                                >
                            </div>

                            <div>
                                <label for="contact_timezone" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                    Timezone (e.g. GMT +1)
                                </label>
                                <input 
                                    id="contact_timezone" 
                                    type="text" 
                                    name="contact_timezone" 
                                    value="{{ old('contact_timezone', $settings['contact_timezone'] ?? 'GMT +1') }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="GMT +1"
                                >
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="contact_working_days_note" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                                        Holiday / Schedule Note
                                    </label>
                                    <x-admin.ai-button 
                                        for="contact_working_days_note" 
                                        field-name="contact_working_days_note" 
                                        field-label="Schedule Note" 
                                        page-context="Site Settings" 
                                        section-context="Office Availability" 
                                    />
                                </div>
                                <input 
                                    id="contact_working_days_note" 
                                    type="text" 
                                    name="contact_working_days_note" 
                                    value="{{ old('contact_working_days_note', $settings['contact_working_days_note'] ?? 'Closed on public holidays') }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                                    placeholder="Closed on public holidays"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Giving & Banking Details -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">4</span>
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Giving & Banking Channels</h2>
                        <p class="text-xs text-stone-500">Destination channels for donors giving online or via direct wire</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="flutterwave_url" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Flutterwave Hosted Giving URL (Card & Local Payments)
                        </label>
                        <input 
                            id="flutterwave_url" 
                            type="url" 
                            name="flutterwave_url" 
                            value="{{ old('flutterwave_url', $settings['flutterwave_url'] ?? 'https://flutterwave.com/pay/j0byxgjs0nma') }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="givebutter_url" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Givebutter Hosted Giving URL (U.S. Donors Only)
                        </label>
                        <input 
                            id="givebutter_url" 
                            type="url" 
                            name="givebutter_url" 
                            value="{{ old('givebutter_url', $settings['givebutter_url'] ?? 'https://givebutter.com/dzuels-foundation') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                            placeholder="https://givebutter.com/dzuels-foundation"
                        >
                    </div>

                    <div>
                        <label for="bank_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Bank Name
                        </label>
                        <input 
                            id="bank_name" 
                            type="text" 
                            name="bank_name" 
                            value="{{ old('bank_name', $settings['bank_name'] ?? 'Guaranty Trust Bank (GTBank) Plc.') }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="bank_account_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Account Name
                        </label>
                        <input 
                            id="bank_account_name" 
                            type="text" 
                            name="bank_account_name" 
                            value="{{ old('bank_account_name', $settings['bank_account_name'] ?? 'Dzuels Educational Foundation') }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="bank_account_number" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Account Number (NGN)
                        </label>
                        <input 
                            id="bank_account_number" 
                            type="text" 
                            name="bank_account_number" 
                            value="{{ old('bank_account_number', $settings['bank_account_number'] ?? '0450072850') }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="registration_number" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Charity Registration Number
                        </label>
                        <input 
                            id="registration_number" 
                            type="text" 
                            name="registration_number" 
                            value="{{ old('registration_number', $settings['registration_number'] ?? 'CAC/IT/NO 123456') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Social Media Links -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">5</span>
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Social Media & Public Profiles</h2>
                        <p class="text-xs text-stone-500">Official organization handles linked from the header and footer</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="social_linkedin" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            LinkedIn Profile URL
                        </label>
                        <input 
                            id="social_linkedin" 
                            type="url" 
                            name="social_linkedin" 
                            value="{{ old('social_linkedin', $settings['social_linkedin'] ?? 'https://linkedin.com') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="social_facebook" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Facebook Page URL
                        </label>
                        <input 
                            id="social_facebook" 
                            type="url" 
                            name="social_facebook" 
                            value="{{ old('social_facebook', $settings['social_facebook'] ?? 'https://facebook.com') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="social_twitter" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            X (Twitter) Profile URL
                        </label>
                        <input 
                            id="social_twitter" 
                            type="url" 
                            name="social_twitter" 
                            value="{{ old('social_twitter', $settings['social_twitter'] ?? 'https://twitter.com') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="social_instagram" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Instagram Profile URL
                        </label>
                        <input 
                            id="social_instagram" 
                            type="url" 
                            name="social_instagram" 
                            value="{{ old('social_instagram', $settings['social_instagram'] ?? 'https://instagram.com') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>
                </div>
            </div>

            <!-- SECTION 6: AI Content Assistant (Google Gemini) -->
            <div 
                x-data="{
                    testing: false,
                    status: null,
                    message: '',
                    testConnection() {
                        const key = document.getElementById('gemini_api_key')?.value || '';
                        const model = document.getElementById('gemini_model')?.value || '';
                        this.testing = true;
                        this.status = null;
                        this.message = '';

                        fetch('{{ route('admin.settings.test-gemini') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ api_key: key, model: model })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.status = data.success ? 'success' : 'error';
                            this.message = data.message;
                        })
                        .catch(err => {
                            this.status = 'error';
                            this.message = 'Network error while contacting server.';
                        })
                        .finally(() => {
                            this.testing = false;
                        });
                    }
                }"
                class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6"
            >
                <div class="flex items-center justify-between pb-4 border-b border-stone-100 flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-sm">6</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-bold text-stone-900">AI Writing Assistant (Google Gemini)</h2>
                                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide bg-purple-100 text-purple-700 rounded-md">New</span>
                            </div>
                            <p class="text-xs text-stone-500">Powers contextual text generation, prompt tweaks, and multimodal vision photo captions across the dashboard</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="testConnection()" 
                        :disabled="testing"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-stone-900 text-white hover:bg-stone-800 transition-colors shadow-xs disabled:opacity-50 cursor-pointer"
                    >
                        <template x-if="testing">
                            <svg class="animate-spin w-3.5 h-3.5 text-cream" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <template x-if="!testing">
                            <svg class="w-3.5 h-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </template>
                        <span x-text="testing ? 'Testing Connection...' : 'Test Connection'"></span>
                    </button>
                </div>

                <!-- Test Connection Feedback Banner -->
                <div x-show="message" x-cloak class="p-3.5 rounded-2xl text-xs font-medium transition-all"
                     :class="status === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                    <div class="flex items-center gap-2">
                        <template x-if="status === 'success'">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="status === 'error'">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <span x-text="message"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2" x-data="{ showKey: false }">
                        <label for="gemini_api_key" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                            Google Gemini API Key
                        </label>
                        <div class="relative">
                            <input 
                                id="gemini_api_key" 
                                :type="showKey ? 'text' : 'password'" 
                                name="gemini_api_key" 
                                value="{{ old('gemini_api_key', $settings['gemini_api_key'] ?? '') }}" 
                                placeholder="AIzaSy..."
                                class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-stone-300 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                            >
                            <button 
                                type="button" 
                                @click="showKey = !showKey" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 cursor-pointer"
                                :title="showKey ? 'Hide key' : 'Show key'"
                            >
                                <svg x-show="!showKey" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showKey" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-[11px] text-stone-500">
                            Obtain a free API key from <a href="https://aistudio.google.com/app/apikey" target="_blank" rel="noopener noreferrer" class="text-maroon underline font-medium">Google AI Studio</a>. If left empty, falls back to <code class="font-mono text-stone-600 bg-stone-100 px-1 py-0.5 rounded">GEMINI_API_KEY</code> in your environment.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label for="gemini_model" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                            AI Model
                        </label>
                        <select 
                            id="gemini_model" 
                            name="gemini_model" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all bg-white"
                        >
                            @php
                                $selectedModel = old('gemini_model', $settings['gemini_model'] ?? 'gemini-1.5-flash');
                            @endphp
                            <option value="gemini-1.5-flash" {{ $selectedModel === 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (Fast, Multimodal - Recommended)</option>
                            <option value="gemini-2.0-flash" {{ $selectedModel === 'gemini-2.0-flash' ? 'selected' : '' }}>Gemini 2.0 Flash (Next-Gen)</option>
                            <option value="gemini-1.5-pro" {{ $selectedModel === 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro (Deep Reasoning)</option>
                        </select>
                        <p class="text-[11px] text-stone-500">
                            All listed models support multimodal vision for analyzing uploaded gallery and section photos.
                        </p>
                    </div>
                </div>
            </div>

            <!-- SECTION 7: Image Optimization & WebP Pipeline -->
            <div 
                x-data="{
                    testing: false,
                    status: null,
                    message: null,
                    env: {{ Js::from($optimizerStatus ?? []) }},
                    testOptimizer() {
                        this.testing = true;
                        this.message = null;
                        this.status = null;

                        fetch('{{ route('admin.settings.test-optimizer') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.testing = false;
                            if (data.test_result && data.test_result.success) {
                                this.status = 'success';
                                this.message = data.test_result.message;
                            } else {
                                this.status = 'error';
                                this.message = (data.test_result && data.test_result.message) ? data.test_result.message : (data.message || 'Optimizer test failed.');
                            }
                            if (data.environment) {
                                this.env = data.environment;
                            }
                        })
                        .catch(err => {
                            this.testing = false;
                            this.status = 'error';
                            this.message = 'Network error running optimizer test: ' + err.message;
                        });
                    }
                }"
                class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6"
            >
                <div class="flex items-center justify-between pb-4 border-b border-stone-100 flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">7</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-bold text-stone-900">Image Optimization & WebP Pipeline</h2>
                                <template x-if="env.is_operational">
                                    <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide bg-emerald-100 text-emerald-700 rounded-md">Operational</span>
                                </template>
                                <template x-if="!env.is_operational">
                                    <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide bg-rose-100 text-rose-700 rounded-md">Action Required</span>
                                </template>
                            </div>
                            <p class="text-xs text-stone-500">Dual GD/Imagick engine enforcing strict &le; 180 KB WebP output across all uploads</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="testOptimizer()" 
                        :disabled="testing"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-stone-900 text-white hover:bg-stone-800 transition-colors shadow-xs disabled:opacity-50 cursor-pointer"
                    >
                        <template x-if="testing">
                            <svg class="animate-spin w-3.5 h-3.5 text-cream" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <template x-if="!testing">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <span x-text="testing ? 'Running Live Test...' : 'Test Image Optimization'"></span>
                    </button>
                </div>

                <!-- Test Connection Feedback Banner -->
                <div x-show="message" x-cloak class="p-3.5 rounded-2xl text-xs font-medium transition-all"
                     :class="status === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                    <div class="flex items-center gap-2">
                        <template x-if="status === 'success'">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="status === 'error'">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <span x-text="message"></span>
                    </div>
                </div>

                <!-- Environment Diagnostics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Active Engine -->
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/60 space-y-1">
                        <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider block">Active Engine</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2.5 h-2.5 rounded-full" :class="env.is_operational ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                            <span class="text-sm font-bold text-stone-900 uppercase" x-text="env.active_engine ? env.active_engine : 'NONE'"></span>
                        </div>
                        <p class="text-[11px] text-stone-500" x-text="env.is_operational ? (env.active_engine === 'gd' ? 'Primary PHP GD Engine' : 'Secondary Imagick Engine') : 'Uncompressed fallback mode'"></p>
                    </div>

                    <!-- GD Status -->
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/60 space-y-1">
                        <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider block">PHP GD Extension</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-bold" :class="env.gd?.loaded ? 'text-stone-900' : 'text-rose-600'" x-text="env.gd?.loaded ? 'Loaded' : 'Not Loaded'"></span>
                            <template x-if="env.gd?.loaded">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold" :class="env.gd?.webp_supported ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'" x-text="env.gd?.webp_supported ? 'WebP OK' : 'No WebP'"></span>
                            </template>
                        </div>
                        <p class="text-[11px] text-stone-500" x-text="env.gd?.version || 'N/A'"></p>
                    </div>

                    <!-- Imagick Status -->
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/60 space-y-1">
                        <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider block">Imagick Extension</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-bold" :class="env.imagick?.loaded ? 'text-stone-900' : 'text-stone-500'" x-text="env.imagick?.loaded ? 'Loaded' : 'Not Loaded'"></span>
                            <template x-if="env.imagick?.loaded">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold" :class="env.imagick?.webp_supported ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-700'" x-text="env.imagick?.webp_supported ? 'WebP OK' : 'No WebP'"></span>
                            </template>
                        </div>
                        <p class="text-[11px] text-stone-500" x-text="env.imagick?.loaded ? 'Fallback Engine Ready' : 'Optional secondary'"></p>
                    </div>

                    <!-- Runtime Memory -->
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/60 space-y-1">
                        <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider block">PHP Memory Limit</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-bold text-stone-900" x-text="env.php?.memory_limit || 'N/A'"></span>
                        </div>
                        <p class="text-[11px] text-stone-500">Auto-elevated to 512M during photo compression</p>
                    </div>
                </div>

                <!-- cPanel Resolution Tip if inactive -->
                <div x-show="!env.is_operational" x-cloak class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs space-y-2">
                    <p class="font-bold flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        How to Enable WebP Optimization on cPanel:
                    </p>
                    <ol class="list-decimal list-inside space-y-1 text-stone-700">
                        <li>Log into your <strong>cPanel</strong> dashboard (<code class="bg-amber-100 px-1 py-0.5 rounded font-mono">server161.web-hosting.com</code>).</li>
                        <li>Click <strong>Select PHP Version</strong> under the <em>Software</em> section.</li>
                        <li>Switch to the <strong>Extensions</strong> tab and check the box for <strong>gd</strong> (and optionally <strong>imagick</strong>).</li>
                        <li>In the <strong>Options</strong> tab, verify that <code class="bg-amber-100 px-1 py-0.5 rounded font-mono">memory_limit</code> is set to at least <strong>256M</strong>.</li>
                    </ol>
                </div>
            </div>

            <!-- Sticky / Prominent Save Bar -->
            <div class="flex items-center justify-end gap-4 pt-4">
                <button 
                    type="submit" 
                    class="px-8 py-3.5 rounded-2xl bg-maroon text-white font-bold text-sm hover:bg-crimson transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Save All Changes</span>
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
