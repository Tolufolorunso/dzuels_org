<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * Handle public subscription request from the footer form.
     */
    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        // Honeypot bot protection
        if (! empty($request->input('_dzuels_hp'))) {
            return $this->respondSuccess($request, 'Thank you for subscribing to Dzuels updates!');
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $email = strtolower(trim($validated['email']));

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->is_active) {
                return $this->respondSuccess($request, 'You are already subscribed to Dzuels updates!');
            }

            // Reactivate previously unsubscribed member
            $subscriber->update([
                'unsubscribed_at' => null,
                'subscribed_at' => now(),
                'unsubscribe_token' => Str::random(40),
            ]);

            return $this->respondSuccess($request, 'Welcome back! Your subscription to Dzuels updates has been reactivated.');
        }

        NewsletterSubscriber::create([
            'email' => $email,
            'subscribed_at' => now(),
            'unsubscribe_token' => Str::random(40),
        ]);

        return $this->respondSuccess($request, 'Thank you for subscribing to Dzuels updates!');
    }

    /**
     * Show the tokenized unsubscribe confirmation page.
     */
    public function showUnsubscribe(string $token): View
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        return view('newsletter.unsubscribe', [
            'subscriber' => $subscriber,
            'unsubscribed' => ! $subscriber->is_active,
        ]);
    }

    /**
     * Process the unsubscription request.
     */
    public function unsubscribe(string $token): View
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        if ($subscriber->is_active) {
            $subscriber->update([
                'unsubscribed_at' => now(),
            ]);
        }

        return view('newsletter.unsubscribe', [
            'subscriber' => $subscriber,
            'unsubscribed' => true,
        ]);
    }

    /**
     * Return JSON for fetch/AJAX or redirect back for standard form submission.
     */
    private function respondSuccess(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('newsletter_success', $message);
    }
}
