<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Partner;
use App\Models\PartnerEnquiry;
use App\Models\Programme;
use App\Models\TeamMember;
use App\Models\VolunteerApplication;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the administrative dashboard overview.
     */
    public function index(): View
    {
        $totalRegistrations = EventRegistration::count();
        $activeEvents = Event::where('is_active', true)->count();
        $activeProgrammes = Programme::where('is_active', true)->count();
        $teamMembersCount = TeamMember::where('is_active', true)->count();
        $partnersCount = Partner::where('is_active', true)->count();
        $unreadMessagesCount = ContactMessage::where('status', 'unread')->count();
        $totalMessagesCount = ContactMessage::count();
        $pendingVolunteersCount = VolunteerApplication::where('status', 'pending')->count();
        $pendingPartnersCount = PartnerEnquiry::where('status', 'pending')->count();

        $upcomingEvents = Event::where('is_active', true)
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        $recentRegistrations = EventRegistration::with('event')
            ->latest()
            ->take(5)
            ->get();

        $recentMessages = ContactMessage::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRegistrations',
            'activeEvents',
            'activeProgrammes',
            'teamMembersCount',
            'partnersCount',
            'unreadMessagesCount',
            'totalMessagesCount',
            'pendingVolunteersCount',
            'pendingPartnersCount',
            'upcomingEvents',
            'recentRegistrations',
            'recentMessages'
        ));
    }
}
