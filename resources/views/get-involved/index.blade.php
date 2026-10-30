<x-layout 
    title="Get Involved: Volunteer or Partner | Dzuels Educational Foundation"
    description="Join Dzuels Educational Foundation as a volunteer mentor or partner organization. Help us empower youth through education, digital literacy, and community development."
    :canonical="url('/get-involved')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Get Involved', 'url' => url('/get-involved')]
    ]">

    <div x-data="{ 
            activeTab: (new URLSearchParams(window.location.search).get('tab') === 'partner' || '{{ old('_tab') }}' === 'partner') ? 'partner' : 'volunteer',
            setTab(tab) {
                this.activeTab = tab;
                const formEl = document.getElementById('application-form-card');
                if (formEl) {
                    formEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
         }" 
         class="min-h-screen bg-[var(--bg)] transition-colors">

        {{-- 1. Hero Section --}}
        @php
            $heroSec = $sections['hero'] ?? null;
            $pathwaysSec = $sections['pathways'] ?? null;
            $formSec = $sections['form'] ?? null;
            $benefitsSec = $sections['benefits'] ?? null;
        @endphp
        @if ($heroSec?->is_active ?? true)
        <section class="relative py-16 lg:py-24 bg-[var(--surface)] border-b border-[var(--border)] overflow-hidden transition-colors">
            {{-- Decorative ambient background glows --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-maroon/5 dark:bg-maroon/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[var(--accent-cream)]/30 dark:bg-[var(--accent-cream)]/5 blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-mint border border-emerald-200 dark:border-emerald-800 mb-6">
                    <span>🤝</span> {{ $heroSec?->subheadline ?: 'Make a Difference With Us' }}
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[var(--heading)] tracking-tight max-w-4xl mx-auto leading-tight">
                    {{ $heroSec?->headline ?: 'Get Involved & Power Youth Potential' }}
                </h1>

                <p class="mt-6 text-lg sm:text-xl text-[var(--text-muted)] max-w-3xl mx-auto leading-relaxed">
                    {{ $heroSec?->body_text ?: 'Whether giving your time as an inspirational mentor or collaborating as an institutional partner, your involvement directly accelerates youth digital literacy and educational equity across Ekiti State.' }}
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
                        <span class="text-base">📦</span>
                        <span>Hardware, Books & Solar Equipment</span>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- 2. Balanced 3-Pathway Selector Cards --}}
        @if ($pathwaysSec?->is_active ?? true)
        <section class="py-12 lg:py-16 bg-[var(--bg)] border-b border-[var(--border)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-maroon dark:text-[var(--accent-cream)] block">
                        {{ $pathwaysSec?->subheadline ?: 'Engagement Pathways' }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                        {{ $pathwaysSec?->headline ?: 'How Would You Like to Support?' }}
                    </h2>
                    <p class="text-sm text-[var(--text-muted)]">
                        {{ $pathwaysSec?->body_text ?: 'Select a pathway below to jump directly to its dedicated application or giving channel.' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Pathway 1: Volunteer Mentorship --}}
                    <div @click="setTab('volunteer')" 
                         class="p-6 sm:p-8 rounded-3xl bg-[var(--surface)] border-2 transition-all duration-300 cursor-pointer group flex flex-col justify-between"
                         :class="activeTab === 'volunteer' ? 'border-maroon shadow-lg ring-2 ring-maroon/20 -translate-y-1' : 'border-[var(--border)] hover:border-maroon/40 shadow-xs hover:shadow-md'">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                🙋
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-crimson dark:text-mint block mb-1">Pathway 01</span>
                            <h3 class="text-xl font-bold text-[var(--heading)] mb-2">Volunteer Mentor</h3>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed mb-6">
                                Deliver hands-on coding clinics, coach student debate and spelling bees, or assist with event organization in Ekiti public secondary schools.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between text-xs font-bold text-maroon dark:text-[var(--accent-cream)]">
                            <span>Open Volunteer Application</span>
                            <span class="group-hover:translate-x-1 transition-transform">&darr;</span>
                        </div>
                    </div>

                    {{-- Pathway 2: Institutional & Corporate Partners --}}
                    <div @click="setTab('partner')" 
                         class="p-6 sm:p-8 rounded-3xl bg-[var(--surface)] border-2 transition-all duration-300 cursor-pointer group flex flex-col justify-between"
                         :class="activeTab === 'partner' ? 'border-maroon shadow-lg ring-2 ring-maroon/20 -translate-y-1' : 'border-[var(--border)] hover:border-maroon/40 shadow-xs hover:shadow-md'">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                🏢
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-crimson dark:text-mint block mb-1">Pathway 02</span>
                            <h3 class="text-xl font-bold text-[var(--heading)] mb-2">Strategic Partner</h3>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed mb-6">
                                Collaborate via corporate CSR sponsorships, multi-school digital lab endowments, co-branded initiatives, or academic partnerships.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between text-xs font-bold text-maroon dark:text-[var(--accent-cream)]">
                            <span>Submit Partnership Proposal</span>
                            <span class="group-hover:translate-x-1 transition-transform">&darr;</span>
                        </div>
                    </div>

                    {{-- Pathway 3: Equipment & Direct Giving --}}
                    <a href="{{ route('donate') }}" 
                       class="p-6 sm:p-8 rounded-3xl bg-[var(--surface)] border-2 border-[var(--border)] hover:border-emerald-500/50 transition-all duration-300 shadow-xs hover:shadow-md hover:-translate-y-1 group flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-mint flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                📦
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-mint block mb-1">Pathway 03</span>
                            <h3 class="text-xl font-bold text-[var(--heading)] mb-2">Equipment & Giving</h3>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed mb-6">
                                Donate working laptops, STEM kits, solar inverters, or make a direct transfer to fund computer terminals and literacy prizes.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between text-xs font-bold text-emerald-700 dark:text-mint">
                            <span>View Giving Channels</span>
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </div>
                    </a>
                </div>
            </div>
        </section>
        @endif

        {{-- 3. Centered Interactive Application Area --}}
        @if ($formSec?->is_active ?? true)
        <section id="application-form-card" class="py-16 lg:py-24 scroll-mt-24">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

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

                {{-- Centered Application Card --}}
                <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl shadow-xl overflow-hidden relative">
                    {{-- Top Accent Bar --}}
                    <div class="h-2 bg-gradient-to-r from-maroon via-crimson to-mint"></div>

                    <div class="p-6 sm:p-12">
                        {{-- Centered Pill Tab Switcher --}}
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
                            
                            <div class="mb-8 border-b border-[var(--border)] pb-6 text-center sm:text-left">
                                <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-mint block mb-1">
                                    Community Service Application
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                                    Volunteer Application
                                </h2>
                                <p class="text-sm text-[var(--text-muted)] mt-1.5 leading-relaxed">
                                    Join our network of passionate instructors, workshop organizers, and student mentors. Fill in your details below and our team will get in touch within 48 hours.
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
                                    <label for="vol_full_name" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                        Full Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="vol_full_name" 
                                           name="full_name" 
                                           value="{{ old('full_name') }}" 
                                           placeholder="e.g. Adebayo Ogunlesi"
                                           required
                                           aria-required="true"
                                           class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('full_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">
                                    @error('full_name')
                                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Contact Information: Email & Phone --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="vol_email" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Email Address <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="email" 
                                               id="vol_email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="you@example.com"
                                               required
                                               aria-required="true"
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('email') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">
                                        @error('email')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vol_phone" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Phone Number <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="tel" 
                                               id="vol_phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="e.g. 0801 234 5678"
                                               required
                                               aria-required="true"
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('phone') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">
                                        @error('phone')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Age Band & Work Status --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="vol_age_band" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Age Group <span class="text-rose-500">*</span>
                                        </label>
                                        <select id="vol_age_band" 
                                                name="age_band" 
                                                required
                                                aria-required="true"
                                                class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('age_band') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all cursor-pointer">
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
                                        <label for="vol_work_status" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Current Work Status <span class="text-rose-500">*</span>
                                        </label>
                                        <select id="vol_work_status" 
                                                name="work_status" 
                                                required
                                                aria-required="true"
                                                class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('work_status') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all cursor-pointer">
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
                                    <label for="vol_skills" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                        Specific Skills & Mentorship Interests <span class="text-xs font-normal text-[var(--text-muted)]">(Optional)</span>
                                    </label>
                                    <textarea id="vol_skills" 
                                              name="skills" 
                                              rows="4" 
                                              placeholder="e.g. Teaching mathematics, basic computer literacy, graphic design, debate coaching, event logistics, photography..."
                                              class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border border-[var(--border)] text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">{{ old('skills') }}</textarea>
                                </div>

                                {{-- Privacy Notice --}}
                                <p class="text-xs text-[var(--text-muted)] flex items-start gap-1.5 leading-relaxed">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-mint shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>Volunteer applications are processed strictly in accordance with our <a href="{{ route('privacy-policy') }}" class="underline hover:text-maroon dark:hover:text-[var(--accent-cream)] font-medium">Privacy Policy</a>.</span>
                                </p>

                                {{-- Centered Submit Button --}}
                                <div class="pt-4 text-center">
                                    <button type="submit" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-10 py-4 rounded-full font-bold text-sm bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
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
                            
                            <div class="mb-8 border-b border-[var(--border)] pb-6 text-center sm:text-left">
                                <span class="text-xs font-bold uppercase tracking-wider text-crimson dark:text-mint block mb-1">
                                    Strategic Collaboration & CSR
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                                    Partner With Us
                                </h2>
                                <p class="text-sm text-[var(--text-muted)] mt-1.5 leading-relaxed">
                                    Collaborate on CSR education projects, community tech lab sponsorships, or multi-school literacy programs. Complete the enquiry form below to start a conversation.
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
                                        <label for="part_org_name" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Organization / Company Name <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="part_org_name" 
                                               name="organization_name" 
                                               value="{{ old('organization_name') }}" 
                                               placeholder="e.g. Apex Global Solutions"
                                               required
                                               aria-required="true"
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('organization_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">
                                        @error('organization_name')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="part_contact_name" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Contact Person Full Name <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="part_contact_name" 
                                               name="contact_name" 
                                               value="{{ old('contact_name') }}" 
                                               placeholder="e.g. Dr. Folake Adeleke"
                                               required
                                               aria-required="true"
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('contact_name') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">
                                        @error('contact_name')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email & Phone --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="part_email" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Official Email Address <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="email" 
                                               id="part_email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="partnerships@company.com"
                                               required
                                               aria-required="true"
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('email') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">
                                        @error('email')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="part_phone" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                            Telephone / Mobile <span class="text-xs font-normal text-[var(--text-muted)]">(Optional)</span>
                                        </label>
                                        <input type="tel" 
                                               id="part_phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="e.g. +234 802 345 6789"
                                               class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('phone') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">
                                        @error('phone')
                                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Partnership Type --}}
                                <div>
                                    <label for="part_type" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                        Type of Partnership <span class="text-rose-500">*</span>
                                    </label>
                                    <select id="part_type" 
                                            name="partnership_type" 
                                            required
                                            aria-required="true"
                                            class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('partnership_type') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all cursor-pointer">
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
                                    <label for="part_message" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] mb-2">
                                        Proposal Overview / Message <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea id="part_message" 
                                              name="message" 
                                              rows="5" 
                                              required
                                              aria-required="true"
                                              placeholder="Share key details about your organization, proposed initiatives, locations of interest, or how you would like to collaborate..."
                                              class="w-full px-4 py-3 rounded-xl bg-[var(--bg)] border @error('message') border-rose-500 @else border-[var(--border)] @enderror text-[var(--text)] focus:border-maroon focus:ring-2 focus:ring-maroon/20 outline-none text-sm transition-all">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Privacy Notice --}}
                                <p class="text-xs text-[var(--text-muted)] flex items-start gap-1.5 leading-relaxed">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-mint shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>We respect your organization's confidentiality. Partnership proposals are handled strictly in accordance with our <a href="{{ route('privacy-policy') }}" class="underline hover:text-maroon dark:hover:text-[var(--accent-cream)] font-medium">Privacy Policy</a>.</span>
                                </p>

                                {{-- Centered Submit Button --}}
                                <div class="pt-4 text-center">
                                    <button type="submit" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-10 py-4 rounded-full font-bold text-sm bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                                        <span>Submit Partnership Proposal</span>
                                        <span>&rarr;</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </section>
        @endif

        {{-- 4. Balanced 3-Column Governance & Benefits Grid Below Form --}}
        @if ($benefitsSec?->is_active ?? true)
        <section class="py-16 bg-[var(--surface-alt)] border-t border-[var(--border)] transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Card 1: Why Volunteer With Us --}}
                    <div class="p-6 sm:p-8 rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                🌟
                            </div>
                            <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Why Volunteer With Us?</h3>
                            <ul class="space-y-3 text-xs sm:text-sm text-[var(--text-muted)]">
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span><strong>Direct Classroom Mentorship:</strong> Teach coding, reading, and digital problem-solving directly to public school students.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span><strong>Flexible Commitment:</strong> On-site weekend workshops in Ijero-Ekiti or remote curriculum development.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span><strong>Recognized Service:</strong> Certificate of community contribution and lifelong alumni network.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Card 2: Verified Track Record & Compliance --}}
                    <div class="p-6 sm:p-8 rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl mb-4">
                                📈
                            </div>
                            <h3 class="text-xl font-bold text-[var(--heading)] mb-3">Institutional Collaboration & Track Record</h3>
                            <div class="grid grid-cols-2 gap-2.5 mb-4">
                                <div class="p-3 rounded-xl bg-[var(--bg)] border border-[var(--border)] text-center">
                                    <span class="text-xl font-black text-maroon dark:text-[var(--accent-cream)] block">500+</span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-medium">Students Benefited</span>
                                </div>
                                <div class="p-3 rounded-xl bg-[var(--bg)] border border-[var(--border)] text-center">
                                    <span class="text-xl font-black text-maroon dark:text-[var(--accent-cream)] block">591+</span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-medium">Literacy Learners</span>
                                </div>
                                <div class="p-3 rounded-xl bg-[var(--bg)] border border-[var(--border)] text-center">
                                    <span class="text-xl font-black text-maroon dark:text-[var(--accent-cream)] block">30</span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-medium">Teachers Trained</span>
                                </div>
                                <div class="p-3 rounded-xl bg-[var(--bg)] border border-[var(--border)] text-center">
                                    <span class="text-xl font-black text-maroon dark:text-[var(--accent-cream)] block">10+</span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-medium">Years Serving Youth</span>
                                </div>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                Officially incorporated under Nigeria's Corporate Affairs Commission with audited reporting and transparent deployment.
                            </p>
                        </div>
                    </div>

                    {{-- Card 3: Direct Secretariat Hotline --}}
                    <div class="p-6 sm:p-8 rounded-3xl bg-linear-to-br from-maroon to-crimson text-white shadow-md flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-2xl">📞</span>
                                <h3 class="text-xl font-bold text-white">Direct Secretariat Inquiry</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-[var(--accent-cream)]/90 mb-6 leading-relaxed">
                                Need immediate partnership briefings, formal bilateral discussions, or in-kind donation collection in Lagos or Ekiti? Contact our leadership desk:
                            </p>
                            <div class="space-y-3 text-xs font-semibold text-white/95 pb-4 border-b border-white/20">
                                <div class="flex items-center gap-2.5">
                                    <span>📧</span>
                                    <a href="mailto:info@dzuelsfoundation.org" class="underline hover:text-[var(--accent-cream)]">info@dzuelsfoundation.org</a>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span>📱</span>
                                    <a href="tel:+2349042493883" class="hover:text-[var(--accent-cream)]">+234 904 249 3883</a>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 flex items-center justify-between text-[11px] text-[var(--accent-cream)]/80">
                            <span>Response timeframe:</span>
                            <span class="font-bold text-white">Within 24–48 Hours</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
</x-layout>
