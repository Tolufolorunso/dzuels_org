<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnerEnquiryRequest;
use App\Http\Requests\StoreVolunteerApplicationRequest;
use App\Mail\NewPartnerEnquiryMail;
use App\Mail\NewVolunteerApplicationMail;
use App\Models\PageSection;
use App\Models\PartnerEnquiry;
use App\Models\Setting;
use App\Models\VolunteerApplication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GetInvolvedController extends Controller
{
    /**
     * Show the public Get Involved page.
     */
    public function index(): View
    {
        $sections = PageSection::getPageSections('get_involved');

        return view('get-involved.index', compact('sections'));
    }

    /**
     * Handle volunteer application submission.
     */
    public function storeVolunteer(StoreVolunteerApplicationRequest $request): RedirectResponse
    {
        $data = $request->safe()->only([
            'full_name',
            'email',
            'phone',
            'age_band',
            'work_status',
            'skills',
        ]);

        $application = VolunteerApplication::create($data);

        // Notify foundation team
        try {
            $recipient = Setting::get('contact_email', 'info@dzuelsfoundation.org') ?: 'info@dzuelsfoundation.org';
            Mail::to($recipient)->send(new NewVolunteerApplicationMail($application));
        } catch (\Throwable $e) {
            Log::warning('Failed sending volunteer notification email: '.$e->getMessage());
        }

        return redirect()->route('get-involved', ['tab' => 'volunteer'])
            ->with('success', "Thank you, {$application->full_name}! Your volunteer application has been received. Our team will review your profile and reach out shortly.");
    }

    /**
     * Handle institutional or corporate partner enquiry.
     */
    public function storePartner(StorePartnerEnquiryRequest $request): RedirectResponse
    {
        $data = $request->safe()->only([
            'organization_name',
            'contact_name',
            'email',
            'phone',
            'partnership_type',
            'message',
        ]);

        $enquiry = PartnerEnquiry::create($data);

        // Notify foundation team
        try {
            $recipient = Setting::get('contact_email', 'info@dzuelsfoundation.org') ?: 'info@dzuelsfoundation.org';
            Mail::to($recipient)->send(new NewPartnerEnquiryMail($enquiry));
        } catch (\Throwable $e) {
            Log::warning('Failed sending partner enquiry notification email: '.$e->getMessage());
        }

        return redirect()->route('get-involved', ['tab' => 'partner'])
            ->with('success', "Thank you for reaching out, {$enquiry->contact_name}! Your partnership proposal for {$enquiry->organization_name} has been submitted. Our team will connect with you soon.");
    }
}
