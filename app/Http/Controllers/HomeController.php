<?php

namespace App\Http\Controllers;

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

        return view('home', [
            'stats' => $stats,
        ]);
    }
}
