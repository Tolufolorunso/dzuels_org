<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
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
        $sections = PageSection::getPageSections('about');

        try {
            $teamByGroup = TeamMember::active()->ordered()->get()->groupBy('group');
            $trustees = $teamByGroup->get('trustee', collect());
            $management = $teamByGroup->get('management', collect());
            $advisors = $teamByGroup->get('advisor', collect());
            $partners = Partner::active()->ordered()->get();
        } catch (\Throwable) {
            $trustees = collect();
            $management = collect();
            $advisors = collect();
            $partners = collect();
        }

        return view('about', [
            'sections' => $sections,
            'trustees' => $trustees,
            'management' => $management,
            'advisors' => $advisors,
            'partners' => $partners,
        ]);
    }
}
