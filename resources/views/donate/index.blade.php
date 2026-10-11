<x-layout 
    title="Donate & Support Quality Education | Dzuels Educational Foundation"
    description="Support digital literacy, student computer labs, and educational opportunities in Ekiti State, Nigeria. Donate online via Flutterwave or direct bank transfer."
    :canonical="url('/donate')"
    :og-image="asset('front/images/cause.png')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Donate', 'url' => url('/donate')]
    ]">

    <div x-data="donateHelper()" class="min-h-screen bg-[var(--bg)] transition-colors">
        {{-- 1. Hero Section --}}
        <section class="relative py-16 lg:py-24 bg-[var(--surface)] border-b border-[var(--border)] overflow-hidden transition-colors">
            {{-- Decorative ambient background glows --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-maroon/5 dark:bg-maroon/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[var(--accent-cream)]/30 dark:bg-[var(--accent-cream)]/5 blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-mint border border-emerald-200 dark:border-emerald-800 mb-6">
                    <span>💚</span> Every Gift Creates Lasting Impact
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[var(--heading)] tracking-tight max-w-4xl mx-auto leading-tight">
                    Your Generosity Powers <span class="font-serif italic font-normal text-crimson dark:text-[var(--accent-cream)]">Real Transformation</span>
                </h1>

                <p class="mt-6 text-lg sm:text-xl text-[var(--text-muted)] max-w-2xl mx-auto leading-relaxed">
                    Help us bridge Nigeria's digital divide. 100% of public contributions directly equip community computer labs, sponsor youth literacy tournaments, and provide educational materials in Ijero-Ekiti and rural public schools.
                </p>

                {{-- Trust Badges Strip --}}
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-xs text-[var(--text-strong)] font-semibold">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[var(--bg)] border border-[var(--border)] shadow-2xs">
                        <span class="text-base">🏛️</span>
                        <span>Registered Non-Profit ({{ $settings['registration_number'] }})</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[var(--bg)] border border-[var(--border)] shadow-2xs">
                        <span class="text-base">🛡️</span>
                        <span>Verified Financial Accountability</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[var(--bg)] border border-[var(--border)] shadow-2xs">
                        <span class="text-base">🔒</span>
                        <span>256-Bit Encrypted Payments</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- 2. Giving Channels Grid --}}
        <section class="py-16 lg:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="text-xs font-bold uppercase tracking-wider text-maroon dark:text-[var(--accent-cream)] block mb-2">
                        How You Can Give
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                        Choose Your Preferred Giving Channel
                    </h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)] mt-2">
                        Whether giving online with a debit card, transferring from your banking app, sending wire transfers from abroad, or donating equipment, every contribution matters.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Channel 1: Instant Online Payment via Flutterwave --}}
                    <div class="rounded-3xl bg-[var(--surface)] border-2 border-maroon/30 dark:border-maroon/50 shadow-md p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-maroon text-white shadow-xs">
                                ★ Fastest & Most Popular
                            </span>
                        </div>

                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] flex items-center justify-center text-2xl mb-4">
                                💳
                            </div>
                            <h3 class="text-2xl font-bold text-[var(--heading)] mb-2">Instant Online Donation</h3>
                            <p class="text-sm text-[var(--text-muted)] mb-6">
                                Give securely in seconds using any Nigerian or International debit card, Apple Pay, Google Pay, USSD, or direct bank transfer via Flutterwave.
                            </p>

                            {{-- Suggested Impact Tiers --}}
                            <div class="space-y-3 mb-8">
                                <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] block">
                                    Suggested Giving Amounts:
                                </span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @foreach ($givingTiers as $tier)
                                        <div class="p-3 rounded-xl bg-[var(--bg)] border border-[var(--border)] flex flex-col justify-between">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-extrabold text-sm text-[var(--heading)]">{{ $tier['amount'] }}</span>
                                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-maroon/10 text-maroon dark:text-[var(--accent-cream)] uppercase">
                                                    {{ $tier['badge'] }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-[var(--text-muted)] leading-tight">{{ $tier['title'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <a href="{{ $settings['flutterwave_url'] }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                                <span>Donate Securely via Flutterwave</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            <div class="mt-3 flex items-center justify-center gap-4 text-[11px] text-[var(--text-muted)]">
                                <span>🔒 256-Bit SSL Encrypted</span>
                                <span>•</span>
                                <span>Instant Email Receipt</span>
                            </div>
                        </div>
                    </div>

                    {{-- Channel 2: Direct Local Bank Transfer (GTBank) --}}
                    <div id="bank-transfer" class="rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-xs p-6 sm:p-8 flex flex-col justify-between scroll-mt-24">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-mint flex items-center justify-center text-2xl mb-4">
                                🏦
                            </div>
                            <h3 class="text-2xl font-bold text-[var(--heading)] mb-2">Direct Bank Transfer</h3>
                            <p class="text-sm text-[var(--text-muted)] mb-6">
                                Make a local electronic funds transfer (EFT), mobile bank transfer, or cash deposit directly into the official foundation account.
                            </p>

                            {{-- Account Details Card --}}
                            <div class="p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border)] space-y-4 mb-6">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">Bank Name</span>
                                    <span class="text-sm font-bold text-[var(--heading)]">{{ $settings['bank_name'] }}</span>
                                </div>

                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">Account Name</span>
                                    <span class="text-sm font-bold text-[var(--heading)]">{{ $settings['bank_account_name'] }}</span>
                                </div>

                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">Account Number (NGN)</span>
                                    <div class="flex items-center justify-between gap-3 mt-1">
                                        <span class="text-2xl sm:text-3xl font-mono font-black text-maroon dark:text-[var(--accent-cream)] tracking-wider">
                                            {{ $settings['bank_account_number'] }}
                                        </span>
                                        <button type="button" 
                                                @click="copyText('{{ $settings['bank_account_number'] }}', 'naira_account')" 
                                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5"
                                                :class="copiedField === 'naira_account' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-stone-200 dark:bg-stone-800 text-[var(--text)] hover:bg-stone-300 dark:hover:bg-stone-700'">
                                            <template x-if="copiedField === 'naira_account'">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Copied!
                                                </span>
                                            </template>
                                            <template x-if="copiedField !== 'naira_account'">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                    </svg>
                                                    Copy
                                                </span>
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 text-amber-900 dark:text-amber-200 text-xs space-y-1">
                            <span class="font-bold block">💡 Transfer Confirmation:</span>
                            <p>
                                Kindly email your payment reference to <a href="mailto:{{ $settings['contact_email'] }}" class="underline font-semibold">{{ $settings['contact_email'] }}</a> or WhatsApp <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone']) }}" class="underline font-semibold">{{ $settings['contact_phone'] }}</a> for your official receipt.
                            </p>
                        </div>
                    </div>

                    {{-- Channel 3: International & Diaspora Wire Transfers --}}
                    <div class="rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-xs p-6 sm:p-8 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 flex items-center justify-center text-2xl mb-4">
                                🌍
                            </div>
                            <h3 class="text-2xl font-bold text-[var(--heading)] mb-2">Diaspora & International Wire</h3>
                            <p class="text-sm text-[var(--text-muted)] mb-6">
                                Direct international wire transfer details for friends and diaspora donors in the United States, United Kingdom, Canada, and Europe.
                            </p>

                            <div class="p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border)] space-y-4 mb-6">
                                <div class="flex items-center justify-between pb-3 border-b border-[var(--border)]">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">Bank & Swift Code</span>
                                        <span class="text-sm font-bold text-[var(--heading)]">GTBank Plc. • Swift: {{ $diasporaAccounts['swift_code'] }}</span>
                                    </div>
                                    <button type="button" 
                                            @click="copyText('{{ $diasporaAccounts['swift_code'] }}', 'swift')" 
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold cursor-pointer"
                                            :class="copiedField === 'swift' ? 'bg-emerald-600 text-white' : 'bg-stone-200 dark:bg-stone-800 text-[var(--text)]'">
                                        <span x-text="copiedField === 'swift' ? 'Copied' : 'Copy Swift'"></span>
                                    </button>
                                </div>

                                {{-- USD --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">USD Domiciliary Account</span>
                                        <span class="text-base font-mono font-bold text-[var(--heading)]">{{ $diasporaAccounts['usd'] }}</span>
                                    </div>
                                    <button type="button" 
                                            @click="copyText('{{ $diasporaAccounts['usd'] }}', 'usd')" 
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold cursor-pointer"
                                            :class="copiedField === 'usd' ? 'bg-emerald-600 text-white' : 'bg-stone-200 dark:bg-stone-800 text-[var(--text)]'">
                                        <span x-text="copiedField === 'usd' ? 'Copied' : 'Copy'"></span>
                                    </button>
                                </div>

                                {{-- GBP --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">GBP Domiciliary Account</span>
                                        <span class="text-base font-mono font-bold text-[var(--heading)]">{{ $diasporaAccounts['gbp'] }}</span>
                                    </div>
                                    <button type="button" 
                                            @click="copyText('{{ $diasporaAccounts['gbp'] }}', 'gbp')" 
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold cursor-pointer"
                                            :class="copiedField === 'gbp' ? 'bg-emerald-600 text-white' : 'bg-stone-200 dark:bg-stone-800 text-[var(--text)]'">
                                        <span x-text="copiedField === 'gbp' ? 'Copied' : 'Copy'"></span>
                                    </button>
                                </div>

                                {{-- EUR --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] block">EUR Domiciliary Account</span>
                                        <span class="text-base font-mono font-bold text-[var(--heading)]">{{ $diasporaAccounts['eur'] }}</span>
                                    </div>
                                    <button type="button" 
                                            @click="copyText('{{ $diasporaAccounts['eur'] }}', 'eur')" 
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold cursor-pointer"
                                            :class="copiedField === 'eur' ? 'bg-emerald-600 text-white' : 'bg-stone-200 dark:bg-stone-800 text-[var(--text)]'">
                                        <span x-text="copiedField === 'eur' ? 'Copied' : 'Copy'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-xs text-[var(--text-muted)]">
                            <span>Beneficiary Name: <strong>{{ $settings['bank_account_name'] }}</strong></span>
                        </div>
                    </div>

                    {{-- Channel 4: In-Kind & Educational Equipment Support --}}
                    <div class="rounded-3xl bg-[var(--surface)] border border-[var(--border)] shadow-xs p-6 sm:p-8 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 flex items-center justify-center text-2xl mb-4">
                                📦
                            </div>
                            <h3 class="text-2xl font-bold text-[var(--heading)] mb-2">In-Kind & Equipment Donations</h3>
                            <p class="text-sm text-[var(--text-muted)] mb-6">
                                We gladly welcome donated learning hardware, literature, and school equipment to outfit classrooms and student workshops.
                            </p>

                            <div class="p-6 rounded-2xl bg-[var(--bg)] border border-[var(--border)] space-y-3 mb-6">
                                <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-strong)] block">
                                    High-Priority Needs:
                                </span>
                                <ul class="text-xs text-[var(--text-muted)] space-y-2 list-disc list-inside">
                                    <li>Working laptops, desktop monitors, and mice for coding hubs</li>
                                    <li>Science textbooks, mathematics sets, and English literature</li>
                                    <li>Solar panels, inverters, and backup batteries for power continuity</li>
                                    <li>School uniforms, backpacks, and student stationery</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <a href="mailto:{{ $settings['contact_email'] }}?subject=In-Kind%20Donation%20Inquiry" 
                               class="w-full sm:w-auto flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-stone-100 dark:bg-stone-800 text-[var(--text)] hover:text-maroon border border-[var(--border)] transition-colors">
                                ✉️ Email Logistics Team
                            </a>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone']) }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-maroon text-white hover:bg-crimson transition-colors">
                                📞 Call Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. Where Your Gift Goes (Transparency Breakdown) --}}
        <section class="py-16 bg-[var(--surface-alt)] border-y border-[var(--border)] transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-maroon/10 dark:bg-maroon/30 text-maroon dark:text-[var(--accent-cream)] border border-maroon/20 mb-3">
                        Financial Accountability
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                        Where Your Contribution Goes
                    </h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)] mt-2">
                        We maintain zero-waste budgeting. Here is how donations are allocated across our community programmes and educational initiatives.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($impactPillars as $pillar)
                        <div class="p-6 rounded-2xl bg-[var(--surface)] border border-[var(--border)] shadow-2xs hover:shadow-md transition-shadow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-3xl">{{ $pillar['icon'] }}</span>
                                    <span class="text-2xl font-black text-maroon dark:text-[var(--accent-cream)] font-mono">
                                        {{ $pillar['percentage'] }}%
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-[var(--heading)] mb-2">{{ $pillar['title'] }}</h3>
                                <p class="text-xs text-[var(--text-muted)] leading-relaxed">{{ $pillar['description'] }}</p>
                            </div>
                            <div class="w-full bg-stone-200 dark:bg-stone-800 h-2 rounded-full overflow-hidden mt-6">
                                <div class="bg-maroon h-full rounded-full" style="width: {{ $pillar['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- 4. Frequently Asked Questions (FAQ) --}}
        <section class="py-16 lg:py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="text-xs font-bold uppercase tracking-wider text-maroon dark:text-[var(--accent-cream)] block mb-2">
                        Questions & Clarifications
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                        Frequently Asked Questions
                    </h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)] mt-2">
                        Everything you need to know about giving to Dzuels Educational Foundation.
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach ($faqs as $index => $faq)
                        <div class="rounded-2xl bg-[var(--surface)] border border-[var(--border)] overflow-hidden shadow-2xs transition-colors">
                            <button type="button" 
                                    @click="toggleFaq({{ $index }})" 
                                    class="w-full px-6 py-5 text-left font-bold text-sm sm:text-base text-[var(--heading)] flex items-center justify-between gap-4 cursor-pointer focus:outline-none"
                                    :aria-expanded="activeFaq === {{ $index }}">
                                <span>{{ $faq['question'] }}</span>
                                <svg class="w-5 h-5 text-stone-400 shrink-0 transition-transform duration-200" 
                                     :class="activeFaq === {{ $index }} ? 'rotate-180 text-maroon' : ''"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="activeFaq === {{ $index }}" 
                                 x-cloak
                                 x-collapse
                                 class="px-6 pb-6 text-sm text-[var(--text-muted)] leading-relaxed border-t border-[var(--border)] pt-4">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- 5. Bottom Volunteer & Community CTA --}}
        <section class="py-16 bg-[var(--surface)] border-t border-[var(--border)] transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-mint/20 text-emerald-800 dark:text-mint border border-mint/30">
                    🤝 Give Your Time & Skills
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--heading)] tracking-tight">
                    Want to Support as a Volunteer or Mentor?
                </h2>
                <p class="text-base text-[var(--text-muted)] max-w-2xl mx-auto">
                    We welcome passionate teachers, software developers, robotics coaches, and logistics helpers to join hands in teaching and inspiring students.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <a href="{{ url('/get-involved') }}" 
                       class="px-6 py-3 rounded-full text-sm font-bold bg-maroon text-white hover:bg-crimson transition-all shadow-md hover:shadow-lg cursor-pointer">
                        Volunteer With Us &rarr;
                    </a>
                    <a href="{{ url('/about') }}" 
                       class="px-6 py-3 rounded-full text-sm font-semibold bg-[var(--bg)] text-[var(--text)] hover:text-maroon border border-[var(--border)] transition-colors">
                        Learn More About Our Team
                    </a>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        function donateHelper() {
            return {
                copiedField: null,
                activeFaq: 0,

                copyText(text, fieldName) {
                    if (!navigator.clipboard) {
                        return;
                    }
                    navigator.clipboard.writeText(text).then(() => {
                        this.copiedField = fieldName;
                        setTimeout(() => {
                            if (this.copiedField === fieldName) {
                                this.copiedField = null;
                            }
                        }, 2500);
                    });
                },

                toggleFaq(index) {
                    this.activeFaq = this.activeFaq === index ? null : index;
                }
            };
        }
    </script>
    @endpush
</x-layout>
