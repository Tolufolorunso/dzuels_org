<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of newsletter subscribers.
     */
    public function index(Request $request): View
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('email', 'like', "%{$search}%");
        }

        $filter = $request->input('filter', 'all');
        if ($filter === 'active') {
            $query->active();
        } elseif ($filter === 'unsubscribed') {
            $query->unsubscribed();
        }

        $subscribers = $query->latest('id')->paginate(20)->withQueryString();

        $totalCount = NewsletterSubscriber::count();
        $activeCount = NewsletterSubscriber::active()->count();
        $unsubscribedCount = NewsletterSubscriber::unsubscribed()->count();

        return view('admin.subscribers.index', compact(
            'subscribers',
            'filter',
            'totalCount',
            'activeCount',
            'unsubscribedCount'
        ));
    }

    /**
     * Export all subscribers as a downloadable CSV file.
     */
    public function exportCsv(): StreamedResponse
    {
        $filename = 'dzuels_newsletter_subscribers_'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['ID', 'Email', 'Status', 'Subscribed At', 'Unsubscribed At']);

            NewsletterSubscriber::orderBy('id', 'desc')->chunk(100, function ($subscribers) use ($handle) {
                foreach ($subscribers as $subscriber) {
                    fputcsv($handle, [
                        $subscriber->id,
                        $subscriber->email,
                        $subscriber->is_active ? 'Active' : 'Unsubscribed',
                        $subscriber->subscribed_at ? $subscriber->subscribed_at->format('Y-m-d H:i:s') : '',
                        $subscriber->unsubscribed_at ? $subscriber->unsubscribed_at->format('Y-m-d H:i:s') : '',
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified subscriber from storage.
     */
    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber removed successfully.');
    }
}
