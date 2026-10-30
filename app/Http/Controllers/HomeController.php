<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PageSection;
use App\Models\Partner;
use App\Services\ImpactStatsService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request to display the public homepage.
     */
    public function __invoke(ImpactStatsService $statsService): View
    {
        $stats = $statsService->getStats();
        $sections = PageSection::getPageSections('home');

        try {
            $partners = Partner::active()->ordered()->get();
        } catch (\Throwable) {
            $partners = collect();
        }

        $latestPosts = collect();

        try {
            $flagshipEvent = Event::where('slug', 'ijero-tech-day-2026')->first();
        } catch (\Throwable) {
            $flagshipEvent = null;
        }

        return view('home', [
            'stats' => $stats,
            'sections' => $sections,
            'partners' => $partners,
            'latestPosts' => $latestPosts,
            'flagshipEvent' => $flagshipEvent,
        ]);
    }
}
