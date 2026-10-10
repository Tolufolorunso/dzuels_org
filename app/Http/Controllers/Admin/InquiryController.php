<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerEnquiry;
use App\Models\VolunteerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * Display a listing of volunteer applications and partner enquiries.
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'volunteers');
        $status = $request->query('status', 'all');
        $search = $request->query('q');

        // Volunteers query
        $volunteersQuery = VolunteerApplication::latest();
        if ($status !== 'all' && in_array($status, ['pending', 'reviewed', 'contacted'])) {
            $volunteersQuery->where('status', $status);
        }
        if ($search) {
            $volunteersQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%");
            });
        }
        $volunteers = $volunteersQuery->paginate(15, ['*'], 'volunteers_page')->withQueryString();

        // Partners query
        $partnersQuery = PartnerEnquiry::latest();
        if ($status !== 'all' && in_array($status, ['pending', 'reviewed', 'contacted'])) {
            $partnersQuery->where('status', $status);
        }
        if ($search) {
            $partnersQuery->where(function ($q) use ($search) {
                $q->where('organization_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }
        $partners = $partnersQuery->paginate(15, ['*'], 'partners_page')->withQueryString();

        // Pending counts for quick badge counters
        $pendingVolunteersCount = VolunteerApplication::pending()->count();
        $pendingPartnersCount = PartnerEnquiry::pending()->count();
        $totalVolunteersCount = VolunteerApplication::count();
        $totalPartnersCount = PartnerEnquiry::count();

        return view('admin.inquiries.index', compact(
            'volunteers',
            'partners',
            'tab',
            'status',
            'search',
            'pendingVolunteersCount',
            'pendingPartnersCount',
            'totalVolunteersCount',
            'totalPartnersCount'
        ));
    }

    /**
     * Update the status of a volunteer application.
     */
    public function updateVolunteerStatus(Request $request, VolunteerApplication $volunteer): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,reviewed,contacted'],
        ]);

        $volunteer->update(['status' => $validated['status']]);

        return back()->with('success', "Status updated to {$validated['status']} for {$volunteer->full_name}.");
    }

    /**
     * Delete a volunteer application.
     */
    public function destroyVolunteer(VolunteerApplication $volunteer): RedirectResponse
    {
        $name = $volunteer->full_name;
        $volunteer->delete();

        return back()->with('success', "Volunteer application for {$name} deleted successfully.");
    }

    /**
     * Update the status of a partner enquiry.
     */
    public function updatePartnerStatus(Request $request, PartnerEnquiry $partner): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,reviewed,contacted'],
        ]);

        $partner->update(['status' => $validated['status']]);

        return back()->with('success', "Status updated to {$validated['status']} for {$partner->organization_name}.");
    }

    /**
     * Delete a partner enquiry.
     */
    public function destroyPartner(PartnerEnquiry $partner): RedirectResponse
    {
        $org = $partner->organization_name;
        $partner->delete();

        return back()->with('success', "Partner enquiry for {$org} deleted successfully.");
    }
}
