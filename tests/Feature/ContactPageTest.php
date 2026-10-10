<?php

namespace Tests\Feature;

use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_returns_200_and_renders_information_and_form(): void
    {
        Setting::set('contact_phone_1', '+234 707 664 6780', 'string', 'contact');
        Setting::set('contact_phone_2', '+234 904 249 3883', 'string', 'contact');
        Setting::set('contact_email', 'info@dzuelsfoundation.org', 'string', 'contact');
        Setting::set('contact_address', '12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria', 'string', 'contact');

        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSeeText('We Are Always Available to');
        $response->assertSeeText('Connect With You');
        $response->assertSeeText('12b Doherty Road, Ijero-Ekiti');
        $response->assertSeeText('+234 707 664 6780');
        $response->assertSeeText('+234 904 249 3883');
        $response->assertSeeText('info@dzuelsfoundation.org');
        $response->assertSeeText('Send Us a Message');
        $response->assertSee('https://www.google.com/maps/embed', false);
    }

    public function test_contact_message_can_be_submitted_successfully(): void
    {
        Mail::fake();

        $payload = [
            'first_name' => 'Olumide',
            'last_name' => 'Bakare',
            'email' => 'olumide@example.com',
            'phone' => '08021234567',
            'subject' => 'Inquiry on Ekiti Secondary School Sponsorship',
            'message' => 'We would like to discuss sponsoring computers for two community secondary schools in Ijero.',
        ];

        $response = $this->post('/contact', $payload);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'first_name' => 'Olumide',
            'last_name' => 'Bakare',
            'email' => 'olumide@example.com',
            'phone' => '08021234567',
            'subject' => 'Inquiry on Ekiti Secondary School Sponsorship',
            'status' => 'unread',
        ]);

        Mail::assertSent(NewContactMessageMail::class, function ($mail) {
            return $mail->contactMessage->email === 'olumide@example.com'
                && $mail->contactMessage->first_name === 'Olumide';
        });

        $followed = $this->followRedirects($response);
        $followed->assertSeeText('Your message has been received');
    }

    public function test_contact_message_validation_requires_mandatory_fields(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email-address',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'subject', 'message']);
        $this->assertEquals(0, ContactMessage::count());
        Mail::assertNothingSent();
    }

    public function test_contact_form_honeypot_blocks_spambots(): void
    {
        Mail::fake();

        $payload = [
            'first_name' => 'Bot',
            'last_name' => 'Sender',
            'email' => 'bot@spammer.org',
            'subject' => 'Unsolicited SEO offers',
            'message' => 'Boost your traffic now!',
            '_hp_company' => 'Blackhat SEO Ltd',
        ];

        $response = $this->post('/contact', $payload);

        $response->assertSessionHasErrors(['_hp_company']);
        $this->assertEquals(0, ContactMessage::count());
        Mail::assertNothingSent();
    }

    public function test_rate_limiter_restricts_excessive_contact_submissions(): void
    {
        Mail::fake();

        $validPayload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'subject' => 'General Question',
            'message' => 'Can you send more details regarding your digital lab programmes?',
        ];

        // Send 6 allowed submissions
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/contact', $validPayload);
            $response->assertRedirect('/contact');
        }

        // 7th submission exceeds throttle:6,1
        $response = $this->post('/contact', $validPayload);
        $response->assertStatus(429);
    }
}
