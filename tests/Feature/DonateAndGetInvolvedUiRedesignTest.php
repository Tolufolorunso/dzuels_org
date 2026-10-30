<?php

namespace Tests\Feature;

use App\Mail\NewPartnerEnquiryMail;
use App\Mail\NewVolunteerApplicationMail;
use App\Models\Setting;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DonateAndGetInvolvedUiRedesignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::clearCache();
    }

    public function test_donate_page_has_no_flutterwave_and_renders_gtbank_and_givebutter_channels(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();

        // 1. Zero mentions of Flutterwave in public donation page
        $response->assertDontSee('flutterwave', false);
        $response->assertDontSee('Flutterwave', false);

        // 2. GTBank Local Transfer Channel (Elevated as primary Nigerian channel)
        $response->assertSeeText('Direct Bank Transfer');
        $response->assertSeeText('0450072850');
        $response->assertSeeText('Guaranty Trust Bank');
        $response->assertSeeText('Dzuels Educational Foundation');
        $response->assertSeeText('₦5,000');
        $response->assertSeeText('₦20,000');
        $response->assertSeeText('₦50,000');
        $response->assertSeeText('₦100,000+');

        // 3. Givebutter U.S. Diaspora Giving Channel
        $response->assertSeeText('U.S. Diaspora Giving');
        $response->assertSeeText('For U.S. Donors Only.');
        $response->assertSee('https://givebutter.com/dzuels-foundation', false);
        $response->assertSeeText('Credit / Debit Card');
        $response->assertSeeText('Venmo & PayPal');
        $response->assertSeeText('Apple & Google Pay');

        // 4. Why Donate to Our Cause (Direct Stewardship & Impact Guarantee)
        $response->assertSeeText('Why Donate to Our Cause');
        $response->assertSeeText('100% Direct Learner Benefit');
        $response->assertDontSeeText('Zero Administrative Deductions');
        $response->assertSeeText('Transparent Stewardship & Direct Impact');
        $response->assertSeeText('CAC Certified Non-Profit Standing');
        $response->assertSeeText('Verified Progress & Photo Updates');

        // Verify unverified foreign currency wires and mock SWIFT details are purged
        $response->assertDontSeeText('International Wire Transfers');
        $response->assertDontSeeText('0450072881');
        $response->assertDontSeeText('0450073060');
        $response->assertDontSeeText('0450072898');
        $response->assertDontSeeText('GTBINGLA');

        // 5. In-Kind & Equipment Donations Channel
        $response->assertSeeText('In-Kind & Equipment Donations');
        $response->assertSeeText('Working laptops, desktop monitors');
    }

    public function test_footer_displays_updated_giving_links_without_flutterwave(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Online Giving (Flutterwave)', false);
        $response->assertSeeText('U.S. & Online Giving (Givebutter)');
        $response->assertSeeText('GTBank Direct Transfer');
    }

    public function test_get_involved_page_renders_centered_layout_and_pathway_cards(): void
    {
        $response = $this->get('/get-involved');

        $response->assertOk();

        // 1. Pathway selector cards
        $response->assertSeeText('Volunteer Mentor');
        $response->assertSeeText('Strategic Partner');
        $response->assertSeeText('Equipment & Giving');

        // 2. Centered application card container
        $response->assertSee('id="application-form-card"', false);
        $response->assertSee('max-w-4xl mx-auto', false);

        // 3. Dual tab switcher
        $response->assertSeeText('Volunteer Application');
        $response->assertSeeText('Partner With Us');

        // 4. Balanced 3-column benefits and governance grid
        $response->assertSeeText('Why Volunteer With Us?');
        $response->assertSeeText('Institutional Collaboration & Track Record');
        $response->assertSeeText('Direct Secretariat Inquiry');
        $response->assertSeeText('info@dzuelsfoundation.org');
        $response->assertSeeText('+234 904 249 3883');
    }

    public function test_volunteer_application_submits_successfully_and_flashes_confirmation(): void
    {
        Mail::fake();

        $payload = [
            'full_name' => 'Olufunke Adeyemi',
            'email' => 'olufunke.adeyemi@example.org',
            'phone' => '+234 803 555 1234',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            'skills' => 'Frontend web development and Scratch coding instruction',
        ];

        $response = $this->post('/get-involved/volunteer', $payload);

        $response->assertRedirect('/get-involved?tab=volunteer');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('volunteer_applications', [
            'full_name' => 'Olufunke Adeyemi',
            'email' => 'olufunke.adeyemi@example.org',
            'phone' => '+234 803 555 1234',
            'status' => 'pending',
        ]);

        Mail::assertSent(NewVolunteerApplicationMail::class, function ($mail) {
            return $mail->application->email === 'olufunke.adeyemi@example.org'
                && $mail->application->full_name === 'Olufunke Adeyemi';
        });

        $followed = $this->followRedirects($response);
        $followed->assertSeeText('Your volunteer application has been received');
    }

    public function test_partner_enquiry_submits_successfully_and_flashes_confirmation(): void
    {
        Mail::fake();

        $payload = [
            'organization_name' => 'Ekiti Tech Innovation Hub',
            'contact_name' => 'Dr. Segun Oladipo',
            'email' => 'partnerships@ekititechhub.org',
            'phone' => '+234 802 777 8899',
            'partnership_type' => 'corporate_sponsor',
            'message' => 'We would love to sponsor 50 Raspberry Pi kits for the Ijero Digital Literacy lab.',
        ];

        $response = $this->post('/get-involved/partner', $payload);

        $response->assertRedirect('/get-involved?tab=partner');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('partner_enquiries', [
            'organization_name' => 'Ekiti Tech Innovation Hub',
            'contact_name' => 'Dr. Segun Oladipo',
            'email' => 'partnerships@ekititechhub.org',
            'partnership_type' => 'corporate_sponsor',
            'status' => 'pending',
        ]);

        Mail::assertSent(NewPartnerEnquiryMail::class, function ($mail) {
            return $mail->enquiry->organization_name === 'Ekiti Tech Innovation Hub'
                && $mail->enquiry->contact_name === 'Dr. Segun Oladipo';
        });

        $followed = $this->followRedirects($response);
        $followed->assertSeeText('Your partnership proposal for Ekiti Tech Innovation Hub has been submitted');
    }

    public function test_honeypot_field_silently_rejects_spam_submissions(): void
    {
        Mail::fake();

        $payload = [
            'full_name' => 'Bot Spammer',
            'email' => 'bot@spammer.org',
            'phone' => '08000000000',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            '_hp_company' => 'Automated SEO Spammer Bot',
        ];

        $response = $this->post('/get-involved/volunteer', $payload);

        $response->assertSessionHasErrors(['_hp_company']);
        $this->assertEquals(0, VolunteerApplication::where('email', 'bot@spammer.org')->count());
        Mail::assertNothingSent();
    }
}
