<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsPhoneTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SettingSeeder::class);

        $this->admin = User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_settings_form_with_all_five_phone_fields_and_schedule(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings');

        $response->assertOk();
        $response->assertSee('contact_phone_1');
        $response->assertSee('contact_phone_2');
        $response->assertSee('contact_phone_3');
        $response->assertSee('contact_phone_4');
        $response->assertSee('contact_phone_5');
        $response->assertSee('contact_working_hours');
        $response->assertSee('contact_timezone');
        $response->assertSee('contact_working_days_note');
        $response->assertSee('GMT +1');
    }

    public function test_admin_can_save_up_to_five_phones_and_custom_schedule(): void
    {
        $payload = [
            'stat_ict_students' => 165,
            'stat_literacy_students' => 591,
            'stat_schools_supported' => 15,
            'stat_feed_mode' => 'manual',
            'stat_api_endpoint' => null,

            'contact_phone_1' => '+234 901 111 1111',
            'contact_phone_2' => '+234 902 222 2222',
            'contact_phone_3' => '+234 903 333 3333',
            'contact_phone_4' => '+234 904 444 4444',
            'contact_phone_5' => '+234 905 555 5555',
            'contact_working_hours' => 'Mon – Sat: 7:30 AM – 6:00 PM',
            'contact_timezone' => 'GMT +1',
            'contact_working_days_note' => 'Closed on Sundays and national holidays',
            'contact_email' => 'hq@dzuelsfoundation.org',
            'contact_address' => 'HQ Address, Ijero-Ekiti',

            'flutterwave_url' => 'https://flutterwave.com/pay/test',
            'bank_name' => 'GTBank',
            'bank_account_name' => 'Dzuels Foundation',
            'bank_account_number' => '0450072850',
            'registration_number' => 'RC 123456',
        ];

        $response = $this->actingAs($this->admin)->put('/admin/settings', $payload);

        $response->assertRedirect('/admin/settings');
        $response->assertSessionHas('success');

        $this->assertSame('+234 901 111 1111', Setting::get('contact_phone_1'));
        $this->assertSame('+234 902 222 2222', Setting::get('contact_phone_2'));
        $this->assertSame('+234 903 333 3333', Setting::get('contact_phone_3'));
        $this->assertSame('+234 904 444 4444', Setting::get('contact_phone_4'));
        $this->assertSame('+234 905 555 5555', Setting::get('contact_phone_5'));
        $this->assertSame('Mon – Sat: 7:30 AM – 6:00 PM', Setting::get('contact_working_hours'));
        $this->assertSame('GMT +1', Setting::get('contact_timezone'));
        $this->assertSame('Closed on Sundays and national holidays', Setting::get('contact_working_days_note'));

        $this->assertCount(5, Setting::getPhoneNumbers());
        $this->assertSame('+234 901 111 1111', Setting::getPrimaryPhone());
    }

    public function test_setting_get_phone_numbers_filters_out_empty_lines(): void
    {
        Setting::set('contact_phone_1', '+234 901 111 1111');
        Setting::set('contact_phone_2', null);
        Setting::set('contact_phone_3', '+234 903 333 3333');
        Setting::set('contact_phone_4', '');
        Setting::set('contact_phone_5', '   ');

        $numbers = Setting::getPhoneNumbers();

        $this->assertSame(['+234 901 111 1111', '+234 903 333 3333'], $numbers);
        $this->assertSame('+234 901 111 1111', Setting::getPrimaryPhone());
    }

    public function test_public_pages_and_components_reflect_updated_phone_settings(): void
    {
        Setting::set('contact_phone_1', '+234 999 888 7777');
        Setting::set('contact_phone_2', '+234 555 444 3333');
        Setting::set('contact_email', 'help@dzuelsfoundation.org');

        // Homepage (Utility Bar & Footer)
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('+234 999 888 7777');
        $homeResponse->assertSee('tel:+2349998887777');

        // Donate Page
        $donateResponse = $this->get('/donate');
        $donateResponse->assertOk();
        $donateResponse->assertSee('+234 999 888 7777');

        // Privacy Policy Page
        $privacyResponse = $this->get('/privacy-policy');
        $privacyResponse->assertOk();
        $privacyResponse->assertSee('+234 999 888 7777');
        $privacyResponse->assertSee('+234 555 444 3333');
    }
}
