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

            <!-- SECTION 1: Verified Community Impact Metrics -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between flex-wrap gap-2 pb-4 border-b border-stone-100">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">1</span>
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

            <!-- SECTION 2: Organization Contacts -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Contact & Organization Information</h2>
                        <p class="text-xs text-stone-500">Official channels displayed on the header, utility bar, and contact page</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_phone_1" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Primary Hotline
                        </label>
                        <input 
                            id="contact_phone_1" 
                            type="text" 
                            name="contact_phone_1" 
                            value="{{ old('contact_phone_1', $settings['contact_phone_1'] ?? '+234 803 388 9576') }}" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="contact_phone_2" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Secondary Phone Number (Optional)
                        </label>
                        <input 
                            id="contact_phone_2" 
                            type="text" 
                            name="contact_phone_2" 
                            value="{{ old('contact_phone_2', $settings['contact_phone_2'] ?? '+234 810 000 0000') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-transparent transition-all"
                        >
                    </div>

                    <div>
                        <label for="contact_email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Official Inquiries Email
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
                        <label for="contact_address" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Physical Headquarters Address
                        </label>
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

            <!-- SECTION 3: Giving & Banking Details -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Giving & Banking Channels</h2>
                        <p class="text-xs text-stone-500">Destination channels for donors giving online or via direct wire</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="flutterwave_url" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Flutterwave Hosted Giving URL
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

            <!-- SECTION 4: Social Media Links -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-maroon flex items-center justify-center font-bold text-sm">4</span>
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
