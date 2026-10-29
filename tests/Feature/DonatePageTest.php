<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonatePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_donate_page_returns_200_and_renders_giving_channels(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertSeeText('Your Generosity Powers');
        $response->assertSeeText('Direct Bank Transfer');
        $response->assertSeeText('U.S. Diaspora Giving');
        $response->assertSeeText('International Wire Transfers');
        $response->assertSeeText('In-Kind & Equipment Donations');
        $response->assertSeeText('Where Your Contribution Goes');
        $response->assertSeeText('Frequently Asked Questions');
        $response->assertDontSee('flutterwave', false);
        $response->assertDontSee('Flutterwave', false);
    }

    public function test_donate_page_renders_database_settings_when_configured(): void
    {
        Setting::set('flutterwave_url', 'https://flutterwave.com/pay/custom-test-slug', 'string', 'banking');
        Setting::set('bank_name', 'First Bank of Nigeria', 'string', 'banking');
        Setting::set('bank_account_name', 'Dzuels Community Fund', 'string', 'banking');
        Setting::set('bank_account_number', '1234567890', 'string', 'banking');
        Setting::set('registration_number', 'CAC/TEST/999999', 'string', 'social');

        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertDontSee('https://flutterwave.com/pay/custom-test-slug');
        $response->assertDontSee('Flutterwave');
        $response->assertSeeText('First Bank of Nigeria');
        $response->assertSeeText('Dzuels Community Fund');
        $response->assertSeeText('1234567890');
        $response->assertSeeText('CAC/TEST/999999');
    }

    public function test_donate_page_renders_fallback_values_when_settings_are_empty(): void
    {
        Setting::set('flutterwave_url', '', 'string', 'banking');
        Setting::set('bank_account_number', '', 'string', 'banking');

        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertDontSee('flutterwave.com');
        $response->assertSeeText('0450072850');
        $response->assertSeeText('Guaranty Trust Bank');
    }

    public function test_donate_external_payment_link_has_safe_attributes(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);
    }

    public function test_donate_page_renders_faq_and_impact_pillars(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertSeeText('Is Dzuels Educational Foundation officially registered?');
        $response->assertSeeText('Are online donations secure?');
        $response->assertSeeText('Digital Literacy & Computer Labs');
        $response->assertSeeText('Spelling Bees & Literacy Contests');
    }
}
