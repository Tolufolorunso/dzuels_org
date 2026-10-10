<?php

namespace Tests\Feature;

use App\Mail\NewPartnerEnquiryMail;
use App\Mail\NewVolunteerApplicationMail;
use App\Models\PartnerEnquiry;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class GetInvolvedTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_involved_page_returns_200_and_renders_forms(): void
    {
        $response = $this->get('/get-involved');

        $response->assertOk();
        $response->assertSeeText('Get Involved & Power');
        $response->assertSeeText('Volunteer Application');
        $response->assertSeeText('Partner With Us');
        $response->assertSeeText('Why Volunteer With Us?');
        $response->assertSeeText('Institutional Collaboration');
    }

    public function test_legacy_partner_url_permanently_redirects_to_get_involved(): void
    {
        $response = $this->get('/partner');

        $response->assertStatus(301);
        $response->assertRedirect('/get-involved');
    }

    public function test_volunteer_application_can_be_submitted_successfully(): void
    {
        Mail::fake();

        $payload = [
            'full_name' => 'Babatunde Fashola',
            'email' => 'babatunde@example.com',
            'phone' => '08031234567',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            'skills' => 'Python tutoring and STEM robotics coaching',
        ];

        $response = $this->post('/get-involved/volunteer', $payload);

        $response->assertRedirect('/get-involved?tab=volunteer');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('volunteer_applications', [
            'full_name' => 'Babatunde Fashola',
            'email' => 'babatunde@example.com',
            'phone' => '08031234567',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            'status' => 'pending',
        ]);

        Mail::assertSent(NewVolunteerApplicationMail::class, function ($mail) {
            return $mail->application->email === 'babatunde@example.com'
                && $mail->application->full_name === 'Babatunde Fashola';
        });

        $followed = $this->followRedirects($response);
        $followed->assertSeeText('Your volunteer application has been received');
    }

    public function test_volunteer_application_validation_requires_mandatory_fields(): void
    {
        Mail::fake();

        $response = $this->post('/get-involved/volunteer', [
            'full_name' => '',
            'email' => 'invalid-email',
            'phone' => '',
            'age_band' => 'invalid_age',
            'work_status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['full_name', 'email', 'phone', 'age_band', 'work_status']);
        $this->assertEquals(0, VolunteerApplication::count());
        Mail::assertNothingSent();
    }

    public function test_volunteer_application_honeypot_blocks_bot_submissions(): void
    {
        Mail::fake();

        $payload = [
            'full_name' => 'Spambot User',
            'email' => 'spambot@example.com',
            'phone' => '08000000000',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            '_hp_company' => 'Spam Marketing Inc.',
        ];

        $response = $this->post('/get-involved/volunteer', $payload);

        $response->assertSessionHasErrors(['_hp_company']);
        $this->assertEquals(0, VolunteerApplication::count());
        Mail::assertNothingSent();
    }

    public function test_partner_enquiry_can_be_submitted_successfully(): void
    {
        Mail::fake();

        $payload = [
            'organization_name' => 'AfriTech Educational Systems',
            'contact_name' => 'Dr. Ngozi Okonjo',
            'email' => 'partnerships@afritech.org',
            'phone' => '+234 809 112 2334',
            'partnership_type' => 'corporate_sponsor',
            'message' => 'We are interested in co-funding 3 computer laboratories in public secondary schools.',
        ];

        $response = $this->post('/get-involved/partner', $payload);

        $response->assertRedirect('/get-involved?tab=partner');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('partner_enquiries', [
            'organization_name' => 'AfriTech Educational Systems',
            'contact_name' => 'Dr. Ngozi Okonjo',
            'email' => 'partnerships@afritech.org',
            'partnership_type' => 'corporate_sponsor',
            'status' => 'pending',
        ]);

        Mail::assertSent(NewPartnerEnquiryMail::class, function ($mail) {
            return $mail->enquiry->organization_name === 'AfriTech Educational Systems'
                && $mail->enquiry->contact_name === 'Dr. Ngozi Okonjo';
        });

        $followed = $this->followRedirects($response);
        $followed->assertSeeText('Your partnership proposal for AfriTech Educational Systems has been submitted');
    }

    public function test_partner_enquiry_validation_requires_mandatory_fields(): void
    {
        Mail::fake();

        $response = $this->post('/get-involved/partner', [
            'organization_name' => '',
            'contact_name' => '',
            'email' => 'not-an-email',
            'partnership_type' => 'unrecognized_type',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['organization_name', 'contact_name', 'email', 'partnership_type', 'message']);
        $this->assertEquals(0, PartnerEnquiry::count());
        Mail::assertNothingSent();
    }

    public function test_partner_enquiry_honeypot_blocks_bot_submissions(): void
    {
        Mail::fake();

        $payload = [
            'organization_name' => 'SEO Bot Network',
            'contact_name' => 'Bot Crawler',
            'email' => 'bot@spambox.net',
            'partnership_type' => 'corporate_sponsor',
            'message' => 'Buy cheap backlinks here',
            '_hp_company' => 'Black Hat Marketing Ltd',
        ];

        $response = $this->post('/get-involved/partner', $payload);

        $response->assertSessionHasErrors(['_hp_company']);
        $this->assertEquals(0, PartnerEnquiry::count());
        Mail::assertNothingSent();
    }

    public function test_rate_limiter_restricts_excessive_submissions(): void
    {
        Mail::fake();

        $validPayload = [
            'full_name' => 'Test Applicant',
            'email' => 'test@example.com',
            'phone' => '08012345678',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
        ];

        // Send 6 allowed requests
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/get-involved/volunteer', $validPayload);
            $response->assertRedirect('/get-involved?tab=volunteer');
        }

        // 7th request exceeds throttle:6,1
        $response = $this->post('/get-involved/volunteer', $validPayload);
        $response->assertStatus(429);
    }
}
