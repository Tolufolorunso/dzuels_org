<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    /**
     * Handle the incoming request to display the public About page.
     */
    public function __invoke(): View
    {
        try {
            $trustees = TeamMember::active()->byGroup('trustee')->ordered()->get();
            $management = TeamMember::active()->byGroup('management')->ordered()->get();
            $advisors = TeamMember::active()->byGroup('advisor')->ordered()->get();
            $partners = Partner::active()->ordered()->get();
        } catch (\Throwable) {
            $trustees = collect();
            $management = collect();
            $advisors = collect();
            $partners = collect();
        }

        return view('about', [
            'trustees' => $trustees,
            'management' => $management,
            'advisors' => $advisors,
            'partners' => $partners,
        ]);
    }
}
