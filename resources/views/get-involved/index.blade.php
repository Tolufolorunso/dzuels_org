<x-layout 
    title="Get Involved: Volunteer or Partner | Dzuels Educational Foundation"
    description="Join Dzuels Educational Foundation as a volunteer mentor or partner organization. Help us empower youth through education, digital literacy, and community development."
    :canonical="url('/get-involved')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Get Involved', 'url' => url('/get-involved')]
    ]">

    <div x-data="{ 
            activeTab: (new URLSearchParams(window.location.search).get('tab') === 'partner' || '{{ old('_tab') }}' === 'partner') ? 'partner' : 'volunteer' 
         }" 
         class="min-h-screen bg-[var(--bg)] transition-colors">

        {{-- 1. Hero Section --}}
        <section class="relative py-16 lg:py-24 bg-[var(--surface)] border-b border-[var(--border)] overflow-hidden transition-colors">
            {{-- Decorative ambient background glows --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-maroon/5 dark:bg-maroon/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[var(--accent-cream)]/30 dark:bg-[var(--accent-cream)]/5 blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-mint border border-emerald-200 dark:border-emerald-800 mb-6">
                    <span>🤝</span> Make a Difference With Us
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[var(--heading)] tracking-tight max-w-4xl mx-auto leading-tight">
                    Get Involved & Power <span class="font-serif italic font-normal text-crimson dark:text-[var(--accent-cream)]">Youth Potential</span>
                </h1>

                <p class="mt-6 text-lg sm:text-xl text-[var(--text-muted)] max-w-2xl mx-auto leading-relaxed">
                    Whether giving your time as an inspirational mentor or collaborating as an institutional partner, your involvement directly accelerates youth digital literacy and educational equity.
                </p>

                {{-- Badges Strip --}}
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-xs text-[var(--text-strong)] font-semibold">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[var(--bg)] border border-[var(--border)] shadow-2xs">
                        <span class="text-base">💡</span>
                        <span>STEM & Literacy Mentorship</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[var(--bg)] border border-[var(--border)] shadow-2xs">
                        <span class="text-base">🏫</span>
                        <span>Institutional & School Alliances</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[var(--bg)] border border-[var(--border)] shadow-2xs">
                        <span class="text-base">🌍</span>
                        <span>Measurable Grassroots Impact</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- 2. Main Content Area --}}
        <section class="py-12 lg:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Success Flash Banner --}}
                @if(session('success'))
                    <div class="mb-10 p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 flex items-start gap-4 shadow-sm animate-fade-in">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 flex items-center justify-center text-emerald-700 dark:text-emerald-300 text-xl shrink-0">
                            ✓
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold">Submission Received!</h3>
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
                            <h3 class="text-base font-bold">Please check the required fields</h3>
                            <ul class="text-sm mt-1 list-disc list-inside space-y-1 text-rose-800 dark:text-rose-300">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    
                    {{-- Left Column: Interactive Forms with Tab Switcher --}}
                    <div class="lg:col-span-8 bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-10 shadow-sm transition-colors">
                        
                        {{-- Tab Selector Switch --}}
                        <div class="flex p-1.5 rounded-2xl bg-[var(--bg)] border border-[var(--border)] max-w-md mx-auto mb-10 shadow-2xs">
                            <button type="button" 
                                    @click="activeTab = 'volunteer'"
                                    :class="activeTab === 'volunteer' 
                                        ? 'bg-maroon text-white shadow-xs font-bold' 
                                        : 'text-[var(--text-muted)] hover:text-[var(--heading)] font-medium'"
                                    class="flex-1 py-3 px-4 rounded-xl text-sm text-center transition-all cursor-pointer flex items-center justify-center gap-2">
                                <span>🙋</span>
                                <span>Volunteer</span>
                            </button>
                            <button type="button" 
                                    @click="activeTab = 'partner'"
                                    :class="activeTab === 'partner' 
                                        ? 'bg-maroon text-white shadow-xs font-bold' 
                                        : 'text-[var(--text-muted)] hover:text-[var(--heading)] font-medium'"
                                    class="flex-1 py-3 px-4 rounded-xl text-sm text-center transition-all cursor-pointer flex items-center justify-center gap-2">
                                <span>🏢</span>
                                <span>Partner With Us</span>
                            </button>
                        </div>

                        {{-- TAB 1: VOLUNTEER FORM --}}
                        <div x-show="activeTab === 'volunteer'" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="mb-8 border-b border-[var(--border)] pb-6">
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                                    Volunteer Application
                                </h2>
                                <p class="text-sm text-[var(--text-muted)] mt-1.5 leading-relaxed">
                                    Join our network of passionate instructors, workshop organizers, and student mentors. Fill in your details below and our team will get in touch.
                                </p>
                            </div>

                            <form method="POST" action="{{ route('get-involved.volunteer') }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="_tab" value="volunteer">

                                {{-- Anti-Spam Honeypot Field --}}
                                <div style="display:none !important;" aria-hidden="true">
                                    <label for="_hp_company_vol">Company Name (Leave blank)</label>
                                    <input type="text" id="_hp_company_vol" name="_hp_company" value="" tabindex="-1" autocomplete="off">
                                </div>

                                {{-- Full Name --}}
                                <div>
                                    <label for="vol_full_name" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        Full Name <span class="text-crimson">*</span>
                                    </label>
                                    <input type="text" 
                                           id="vol_full_name" 
                                           name="full_name" 
                                           value="{{ old('full_name') }}" 
                                           placeholder="e.g. Adebayo Ogunlesi"
                                           required
                                           class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('full_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                    @error('full_name')
                                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Contact Information: Email & Phone --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="vol_email" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Email Address <span class="text-crimson">*</span>
                                        </label>
                                        <input type="email" 
                                               id="vol_email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="you@example.com"
                                               required
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('email') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                        @error('email')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vol_phone" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Phone Number <span class="text-crimson">*</span>
                                        </label>
                                        <input type="tel" 
                                               id="vol_phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="e.g. 0801 234 5678"
                                               required
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('phone') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                        @error('phone')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Age Band & Work Status --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="vol_age_band" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Age Group <span class="text-crimson">*</span>
                                        </label>
                                        <select id="vol_age_band" 
                                                name="age_band" 
                                                required
                                                class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('age_band') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors cursor-pointer">
                                            <option value="" disabled {{ old('age_band') ? '' : 'selected' }}>Select your age group</option>
                                            <option value="below_18" {{ old('age_band') === 'below_18' ? 'selected' : '' }}>Under 18 years</option>
                                            <option value="18_to_55" {{ old('age_band') === '18_to_55' ? 'selected' : '' }}>18 - 55 years</option>
                                            <option value="above_55" {{ old('age_band') === 'above_55' ? 'selected' : '' }}>Above 55 years</option>
                                        </select>
                                        @error('age_band')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vol_work_status" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Current Work Status <span class="text-crimson">*</span>
                                        </label>
                                        <select id="vol_work_status" 
                                                name="work_status" 
                                                required
                                                class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('work_status') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors cursor-pointer">
                                            <option value="" disabled {{ old('work_status') ? '' : 'selected' }}>Select work status</option>
                                            <option value="employed" {{ old('work_status') === 'employed' ? 'selected' : '' }}>Employed</option>
                                            <option value="self_employed" {{ old('work_status') === 'self_employed' ? 'selected' : '' }}>Self-employed</option>
                                            <option value="unemployed" {{ old('work_status') === 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                                            <option value="student" {{ old('work_status') === 'student' ? 'selected' : '' }}>Student</option>
                                        </select>
                                        @error('work_status')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Specific Skills & Interests --}}
                                <div>
                                    <label for="vol_skills" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        Specific Skills & Mentorship Interests <span class="text-xs font-normal text-[var(--text-muted)]">(Optional)</span>
                                    </label>
                                    <textarea id="vol_skills" 
                                              name="skills" 
                                              rows="4" 
                                              placeholder="e.g. Teaching mathematics, basic computer literacy, graphic design, debate coaching, event logistics, photography..."
                                              class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('skills') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">{{ old('skills') }}</textarea>
                                    @error('skills')
                                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Privacy Notice --}}
                                <p class="text-xs text-[var(--text-muted)] flex items-start gap-1.5 leading-relaxed">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-mint shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>We respect your privacy. Volunteer applications are processed strictly in accordance with our <a href="{{ route('privacy-policy') }}" class="underline hover:text-maroon dark:hover:text-[var(--accent-cream)] font-medium">Privacy Policy</a>.</span>
                                </p>

                                {{-- Submit Button --}}
                                <div>
                                    <button type="submit" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-bold text-sm bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-maroon focus:ring-offset-2">
                                        <span>Submit Volunteer Application</span>
                                        <span>&rarr;</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- TAB 2: PARTNER FORM --}}
                        <div x-show="activeTab === 'partner'" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="mb-8 border-b border-[var(--border)] pb-6">
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                                    Partner With Us
                                </h2>
                                <p class="text-sm text-[var(--text-muted)] mt-1.5 leading-relaxed">
                                    Collaborate with Dzuels Educational Foundation through corporate social responsibility (CSR), equipment donation, educational grants, or institutional programmes.
                                </p>
                            </div>

                            <form method="POST" action="{{ route('get-involved.partner') }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="_tab" value="partner">

                                {{-- Anti-Spam Honeypot Field --}}
                                <div style="display:none !important;" aria-hidden="true">
                                    <label for="_hp_company_part">Company Name (Leave blank)</label>
                                    <input type="text" id="_hp_company_part" name="_hp_company" value="" tabindex="-1" autocomplete="off">
                                </div>

                                {{-- Organization & Contact Name --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="part_org_name" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Organization / Company Name <span class="text-crimson">*</span>
                                        </label>
                                        <input type="text" 
                                               id="part_org_name" 
                                               name="organization_name" 
                                               value="{{ old('organization_name') }}" 
                                               placeholder="e.g. TechCorp Nigeria Ltd"
                                               required
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('organization_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                        @error('organization_name')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="part_contact_name" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Representative / Contact Name <span class="text-crimson">*</span>
                                        </label>
                                        <input type="text" 
                                               id="part_contact_name" 
                                               name="contact_name" 
                                               value="{{ old('contact_name') }}" 
                                               placeholder="e.g. Dr. Folake Adeleke"
                                               required
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('contact_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                        @error('contact_name')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email & Phone --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="part_email" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Official Email Address <span class="text-crimson">*</span>
                                        </label>
                                        <input type="email" 
                                               id="part_email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="partnerships@company.com"
                                               required
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('email') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                        @error('email')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="part_phone" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                            Telephone / Mobile <span class="text-xs font-normal text-[var(--text-muted)]">(Optional)</span>
                                        </label>
                                        <input type="tel" 
                                               id="part_phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="e.g. +234 802 345 6789"
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('phone') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">
                                        @error('phone')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Partnership Type --}}
                                <div>
                                    <label for="part_type" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        Type of Partnership <span class="text-crimson">*</span>
                                    </label>
                                    <select id="part_type" 
                                            name="partnership_type" 
                                            required
                                            class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('partnership_type') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors cursor-pointer">
                                        <option value="" disabled {{ old('partnership_type') ? '' : 'selected' }}>Select partnership interest</option>
                                        <option value="corporate_sponsor" {{ old('partnership_type') === 'corporate_sponsor' ? 'selected' : '' }}>Corporate Sponsorship / CSR Initiative</option>
                                        <option value="school_partner" {{ old('partnership_type') === 'school_partner' ? 'selected' : '' }}>School / Academic Institution Collaboration</option>
                                        <option value="equipment_donor" {{ old('partnership_type') === 'equipment_donor' ? 'selected' : '' }}>Equipment / Computer / Library Donation</option>
                                        <option value="mentorship_provider" {{ old('partnership_type') === 'mentorship_provider' ? 'selected' : '' }}>Mentorship & Professional Skills Provider</option>
                                        <option value="other" {{ old('partnership_type') === 'other' ? 'selected' : '' }}>Other Strategic Partnership</option>
                                    </select>
                                    @error('partnership_type')
                                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Partnership Proposal Message --}}
                                <div>
                                    <label for="part_message" class="block text-sm font-semibold text-[var(--heading)] mb-2">
                                        Proposal Overview / Message <span class="text-crimson">*</span>
                                    </label>
                                    <textarea id="part_message" 
                                              name="message" 
                                              rows="5" 
                                              required
                                              placeholder="Share key details about your organization, proposed initiatives, locations of interest, or how you would like to collaborate..."
                                              class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('message') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] placeholder-[var(--text-muted)]/50 focus:outline-none focus:ring-2 focus:ring-maroon text-sm transition-colors">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Privacy Notice --}}
                                <p class="text-xs text-[var(--text-muted)] flex items-start gap-1.5 leading-relaxed">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-mint shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>We respect your privacy. Partnership proposals are handled strictly in accordance with our <a href="{{ route('privacy-policy') }}" class="underline hover:text-maroon dark:hover:text-[var(--accent-cream)] font-medium">Privacy Policy</a>.</span>
                                </p>

                                {{-- Submit Button --}}
                                <div>
                                    <button type="submit" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-bold text-sm bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-maroon focus:ring-offset-2">
                                        <span>Submit Partnership Proposal</span>
                                        <span>&rarr;</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Right Column: Information & Impact Sidebar --}}
                    <div class="lg:col-span-4 space-y-8">
                        
                        {{-- Volunteer Impact Card --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8 shadow-sm transition-colors">
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                🌟
                            </div>
                            <h3 class="text-xl font-bold text-[var(--heading)] mb-3">
                                Why Volunteer With Us?
                            </h3>
                            <ul class="space-y-3 text-sm text-[var(--text-muted)]">
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span><strong>Inspire Students Directly:</strong> Deliver hands-on digital training in public secondary schools in Ekiti State.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span><strong>Flexible Roles:</strong> On-site weekend mentoring or remote digital project coaching.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span><strong>Recognized Service:</strong> Certificate of community contribution and active volunteer network.</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Corporate / Institutional Partners Card --}}
                        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-6 sm:p-8 shadow-sm transition-colors">
                            <div class="w-12 h-12 rounded-2xl bg-[var(--accent-cream-light)] text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                🏛️
                            </div>
                            <h3 class="text-xl font-bold text-[var(--heading)] mb-3">
                                Institutional Collaboration
                            </h3>
                            <p class="text-sm text-[var(--text-muted)] leading-relaxed mb-4">
                                We partner with leading corporations, foundations, alumni associations, and tech organizations to scale educational infrastructure.
                            </p>
                            <div class="p-4 rounded-2xl bg-[var(--bg)] border border-[var(--border)] space-y-2 text-xs text-[var(--text-strong)] font-semibold">
                                <div class="flex items-center gap-2">
                                    <span>📊</span>
                                    <span>Detailed impact reports and metrics</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>🎯</span>
                                    <span>Co-branded lab naming & initiatives</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>🛡️</span>
                                    <span>Full regulatory compliance & transparency</span>
                                </div>
                            </div>
                        </div>

                        {{-- Financial Giving Promotion --}}
                        <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-maroon to-crimson text-white shadow-md">
                            <h3 class="text-xl font-bold text-white mb-2">Prefer to donate funds?</h3>
                            <p class="text-sm text-[var(--accent-cream)]/90 mb-5 leading-relaxed">
                                You can support our lab equipment drives and student sponsorships instantly with debit card or direct bank transfer.
                            </p>
                            <a href="{{ route('donate') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider bg-white text-maroon hover:bg-[var(--accent-cream)] transition-all shadow-xs">
                                <span>Support via Donate</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
</x-layout>
