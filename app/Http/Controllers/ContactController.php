<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Models\PageSection;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the public Contact page.
     */
    public function index(): View
    {
        $sections = PageSection::getPageSections('contact');
        $address = Setting::get('contact_address', '12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria') ?: '12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria';
        $email = Setting::get('contact_email', 'info@dzuelsfoundation.org') ?: 'info@dzuelsfoundation.org';
        $phone1 = Setting::get('contact_phone_1', '+234 707 664 6780') ?: '+234 707 664 6780';
        $phone2 = Setting::get('contact_phone_2', '+234 904 249 3883') ?: '+234 904 249 3883';

        return view('contact.index', compact('sections', 'address', 'email', 'phone1', 'phone2'));
    }

    /**
     * Handle incoming contact form submission.
     */
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $data = $request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'subject',
            'message',
        ]);

        $contactMessage = ContactMessage::create($data);

        // Notify foundation team via email
        try {
            $recipient = Setting::get('contact_email', 'info@dzuelsfoundation.org') ?: 'info@dzuelsfoundation.org';
            Mail::to($recipient)->send(new NewContactMessageMail($contactMessage));
        } catch (\Throwable $e) {
            Log::warning('Failed sending contact notification email: '.$e->getMessage());
        }

        return redirect()->route('contact')
            ->with('success', "Thank you, {$contactMessage->first_name}! Your message has been received. Our team will get back to you shortly.");
    }
}
