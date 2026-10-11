<x-layout
    title="Privacy Policy | Dzuels Educational Foundation"
    description="Official Privacy Policy of Dzuels Educational Foundation (CAC/IT/NO 123456) explaining how we collect, protect, and respect your personal information, volunteer submissions, and event registrations."
    :canonical="url('/privacy-policy')"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Privacy Policy', 'url' => url('/privacy-policy')]
    ]">

    {{-- 1. Hero & Breadcrumb Banner --}}
    <section class="relative bg-maroon text-white py-16 sm:py-24 overflow-hidden">
        {{-- Background Pattern & Linear Gradient --}}
        <div class="absolute inset-0 z-0 opacity-20">
            <div class="absolute inset-0 bg-[radial-gradient(#F9DEC9_1px,transparent_1px)] [background-size:24px_24px]"></div>
        </div>
        <div class="absolute inset-0 bg-linear-to-b from-maroon/90 via-maroon to-[#2B0203]"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-sm text-cream/80 mb-6 flex-wrap" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1.5 focus:outline-none focus:underline">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <span class="text-cream/50" aria-hidden="true">/</span>
                <span class="text-cream font-semibold">Privacy Policy</span>
            </nav>

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[var(--accent-cream)]/20 text-[var(--accent-cream)] border border-[var(--accent-cream)]/30 backdrop-blur-xs mb-4">
                    Transparency & Governance
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4">
                    Privacy Policy
                </h1>
                <p class="text-base sm:text-lg text-cream/90 leading-relaxed">
                    How Dzuels Educational Foundation collects, protects, and respects your personal information, volunteer applications, and event registrations.
                </p>
                <p class="mt-4 text-xs sm:text-sm text-cream/70">
                    Effective Date: October 2026 &bull; Registered Incorporated Trustee CAC/IT/NO 123456
                </p>
            </div>
        </div>
    </section>

    {{-- 2. Main Content & Sections --}}
    <section class="py-16 lg:py-24 bg-[var(--surface)] transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            {{-- Summary Box --}}
            <div class="p-6 sm:p-8 rounded-2xl bg-[var(--accent-cream-light)] dark:bg-[var(--surface-raised)] border border-[var(--border)] shadow-xs space-y-4">
                <h2 class="text-lg font-bold text-maroon dark:text-[var(--accent-cream)] flex items-center gap-2">
                    <span>🛡️</span> Privacy Summary at a Glance
                </h2>
                <ul class="space-y-2.5 text-sm text-[var(--text-strong)] list-disc list-inside">
                    <li><strong>No Sale of Data:</strong> We never sell, rent, or trade your personal information to third parties or marketing brokers.</li>
                    <li><strong>Secure Donations:</strong> Online payments are processed exclusively through Flutterwave's secure hosted checkout. We never store or see your credit or debit card numbers.</li>
                    <li><strong>Purpose Limitation:</strong> We collect personal details strictly to coordinate educational programs, respond to volunteers and partners, and manage event logistics.</li>
                    <li><strong>Your Control:</strong> You have the right to request access, correction, or deletion of your personal data at any time.</li>
                </ul>
            </div>

            {{-- Policy Articles --}}
            <div class="space-y-10 text-[var(--text)] leading-relaxed">

                {{-- Section 1 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">1. Organization Identity & Scope</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        Dzuels Educational Foundation ("the Foundation", "we", "us", or "our") is a non-governmental organization registered with Nigeria's Corporate Affairs Commission under Registration Number <strong>CAC/IT/NO 123456</strong> as an Incorporated Trustee.
                    </p>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        This Privacy Policy applies to personal information gathered through our official website (<a href="{{ url('/') }}" class="text-maroon dark:text-[var(--accent-cream)] underline">dzuelsfoundation.org</a>), public intake forms, volunteer applications, partner enquiries, and event registration forms (including Ijero Tech Day).
                    </p>
                </div>

                {{-- Section 2 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">2. Information We Collect</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        We only collect personal information that you voluntarily provide to us when interacting with our website. This includes:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm sm:text-base text-[var(--text-muted)] pl-2">
                        <li><strong>Contact Inquiries:</strong> Your full name, email address, phone number, subject, and message content when submitting the Contact form.</li>
                        <li><strong>Volunteer Applications:</strong> Full name, email address, phone number, age band, employment status, core skills, and your statement of motivation.</li>
                        <li><strong>Partner Enquiries:</strong> Organization or institution name, contact representative name, email, phone number, partnership type, and proposed collaborative scope.</li>
                        <li><strong>Event Registrations:</strong> Attendee name, email address, phone number, school or organization affiliation, batch selection, and dietary or special accommodations for community events like Ijero Tech Day.</li>
                        <li><strong>Newsletter Subscriptions:</strong> Your email address for foundation announcements (with an instant one-click unsubscribe mechanism).</li>
                    </ul>
                </div>

                {{-- Section 3 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">3. How We Use Your Information</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        We use the information we collect strictly for legitimate non-profit and charitable operational purposes:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm sm:text-base text-[var(--text-muted)] pl-2">
                        <li>Evaluating volunteer suitability and assigning tasks in education clinics and bootcamps.</li>
                        <li>Responding to partnership inquiries from educational institutions, seed funds, and donor bodies.</li>
                        <li>Managing event attendance, cohort scheduling, badge issuance, and safety protocols for Ijero Tech Day.</li>
                        <li>Replying to inquiries submitted through our contact form.</li>
                        <li>Sending official receipts or letters of acknowledgment for verified donations.</li>
                        <li>Complying with applicable statutory, accounting, and charitable reporting regulations in Nigeria.</li>
                    </ul>
                </div>

                {{-- Section 4 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">4. Payment Security & Financial Data</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        We take financial transparency and payment safety seriously:
                    </p>
                    <div class="p-4 rounded-xl bg-[var(--surface-raised)] border border-[var(--border)] text-sm space-y-2">
                        <p class="text-[var(--text-strong)]">
                            <strong>Hosted Online Payments:</strong> All online credit card, debit card, USSD, and bank transfer donations made through our website are processed by <strong>Flutterwave</strong> (a PCI-DSS Level 1 certified payment processor). Dzuels Educational Foundation never collects, handles, or stores your card numbers, CVVs, or bank authorization codes on our servers.
                        </p>
                        <p class="text-[var(--text-muted)]">
                            <strong>Direct Bank Transfers:</strong> Donations made directly to our Guaranty Trust Bank (GTBank) accounts are subject to GTBank's banking regulations. When you send confirmation receipts, we use them solely to generate an official acknowledgment.
                        </p>
                    </div>
                </div>

                {{-- Section 5 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">5. Information Sharing & Third Parties</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        We do not sell, rent, trade, or distribute your personal details to commercial advertising networks. We only share information with trusted third-party service providers who assist us in operating our platform:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm sm:text-base text-[var(--text-muted)] pl-2">
                        <li><strong>Infrastructure & Hosting:</strong> Cloud and web hosting providers maintaining our server infrastructure under strict confidentiality agreements.</li>
                        <li><strong>Email Delivery Services:</strong> Secure transactional email gateways delivering confirmation and acknowledgment emails.</li>
                        <li><strong>Legal & Regulatory Authorities:</strong> We may disclose information if required to do so by Nigerian law or in response to valid legal processes.</li>
                    </ul>
                </div>

                {{-- Section 6 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">6. Data Retention & Safeguards</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        We retain personal data only for as long as necessary to fulfill the purposes outlined in this policy or as required by audit and accounting standards. We implement industry-standard physical, technical, and administrative safeguards to protect your personal information against unauthorized access, destruction, loss, or alteration.
                    </p>
                </div>

                {{-- Section 7 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">7. Your Rights Under Data Protection Laws</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        Under the Nigeria Data Protection Act (NDPA) and applicable global data protection principles, you possess the right to:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm sm:text-base text-[var(--text-muted)] pl-2">
                        <li>Request confirmation of whether we hold personal information about you.</li>
                        <li>Request access to or a copy of your personal data.</li>
                        <li>Request correction of inaccurate or outdated details.</li>
                        <li>Request erasure of your information from our active records.</li>
                        <li>Withdraw consent at any time for ongoing communications.</li>
                    </ul>
                </div>

                {{-- Section 8 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">8. Cookies & Local Storage</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        Our website does not use tracking cookies or third-party advertising pixels. We utilize standard browser local storage (<code class="text-xs bg-[var(--surface-raised)] px-1.5 py-0.5 rounded text-maroon dark:text-[var(--accent-cream)] font-mono">dzuels-theme</code>) strictly to preserve your selected dark or light mode color preference across browsing sessions without flickering.
                    </p>
                </div>

                {{-- Section 9 --}}
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold text-[var(--text-strong)] tracking-tight">9. Contact Us for Privacy Matters</h2>
                    <p class="text-sm sm:text-base text-[var(--text-muted)]">
                        If you have questions, concerns, or requests regarding this Privacy Policy or how your personal information is handled, please contact our administrative team:
                    </p>
                    <div class="p-6 rounded-2xl bg-[var(--surface-raised)] border border-[var(--border)] text-sm space-y-3">
                        <p class="font-bold text-[var(--text-strong)]">Dzuels Educational Foundation</p>
                        <p class="text-[var(--text-muted)]"><strong>Office Address:</strong> AAoJ Memorial Learning Center, Ijero-Ekiti, Ekiti State, Nigeria</p>
                        <p class="text-[var(--text-muted)]"><strong>Email:</strong> <a href="mailto:info@dzuelsfoundation.org" class="text-maroon dark:text-[var(--accent-cream)] font-medium underline">info@dzuelsfoundation.org</a></p>
                        <p class="text-[var(--text-muted)]"><strong>Telephone:</strong> <a href="tel:+2348033889576" class="text-maroon dark:text-[var(--accent-cream)] font-medium underline">+234 803 388 9576</a> / <a href="tel:+2347076646780" class="text-maroon dark:text-[var(--accent-cream)] font-medium underline">+234 707 664 6780</a></p>
                    </div>
                </div>

            </div>

            {{-- Recovery links to Home and Donate --}}
            <div class="pt-8 border-t border-[var(--border)] flex flex-wrap items-center justify-between gap-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-maroon dark:text-[var(--accent-cream)] hover:underline">
                    <span>&larr;</span> Back to Home
                </a>
                <a href="{{ url('/donate') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-crimson hover:bg-crimson-hover text-white transition-colors shadow-xs">
                    <span>Support Our Mission</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>
    </section>

</x-layout>
