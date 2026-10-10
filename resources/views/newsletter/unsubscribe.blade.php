<x-layout>
    <x-slot:title>Newsletter Subscription Preferences | Dzuels Educational Foundation</x-slot:title>
    <x-slot:description>Manage your email subscription preferences for Dzuels Educational Foundation.</x-slot:description>

    <section class="py-20 sm:py-28 bg-[var(--bg)] transition-colors">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-10 shadow-xl text-center space-y-6 transition-colors">
                @if ($unsubscribed)
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300 flex items-center justify-center mx-auto text-2xl">
                        ✓
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                        You Have Been Unsubscribed
                    </h1>

                    <p class="text-sm sm:text-base text-[var(--text)] leading-relaxed">
                        We have removed <strong class="text-[var(--heading)]">{{ $subscriber->email }}</strong> from our mailing list. You will no longer receive quarterly updates or newsletters from Dzuels Educational Foundation.
                    </p>

                    <div class="pt-4 border-t border-[var(--border-subtle)] space-y-3">
                        <p class="text-xs text-[var(--text-muted)]">
                            Changed your mind? You can re-subscribe anytime using the footer on our website.
                        </p>
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full text-sm font-semibold bg-maroon text-white hover:bg-crimson transition-all shadow-sm">
                            Return to Homepage
                        </a>
                    </div>
                @else
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-300 flex items-center justify-center mx-auto text-2xl">
                        ✉️
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[var(--heading)] tracking-tight">
                        Unsubscribe from Updates
                    </h1>

                    <p class="text-sm sm:text-base text-[var(--text)] leading-relaxed">
                        Are you sure you want to stop receiving community stories and foundation news at <strong class="text-[var(--heading)]">{{ $subscriber->email }}</strong>?
                    </p>

                    <form method="POST" action="{{ route('newsletter.unsubscribe.confirm', $subscriber->unsubscribe_token) }}" class="pt-4 space-y-3">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 rounded-full text-sm font-bold bg-rose-600 text-white hover:bg-rose-700 transition-all shadow-md cursor-pointer">
                            Confirm Unsubscribe
                        </button>
                    </form>

                    <div class="pt-2">
                        <a href="{{ url('/') }}" class="text-xs font-semibold text-stone-500 hover:text-maroon transition-colors">
                            Keep my subscription & go home
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
