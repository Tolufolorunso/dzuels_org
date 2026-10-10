<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Post;
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

        try {
            $partners = Partner::active()->ordered()->get();
        } catch (\Throwable) {
            $partners = collect();
        }

        try {
            $latestPosts = Post::published()->with('programme')->recent()->take(3)->get();
        } catch (\Throwable) {
            $latestPosts = collect();
        }

        return view('home', [
            'stats' => $stats,
            'partners' => $partners,
            'latestPosts' => $latestPosts,
        ]);
    }
}
