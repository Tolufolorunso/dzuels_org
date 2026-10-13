<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteLogoAndGivebutterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::clearCache();
    }

    public function test_setting_get_logo_url_defaults_to_brand_asset(): void
    {
        Setting::where('key', 'site_logo')->delete();
        Setting::clearCache();

        $this->assertSame(asset('front/images/logo.png'), Setting::getLogoUrl());
    }

    public function test_setting_get_logo_url_returns_custom_path_or_absolute_url(): void
    {
        Setting::set('site_logo', 'uploads/media/custom-logo.webp', 'string', 'general');

        $this->assertSame(asset('uploads/media/custom-logo.webp'), Setting::getLogoUrl());

        Setting::set('site_logo', 'https://cdn.example.com/logo.png', 'string', 'general');
        $this->assertSame('https://cdn.example.com/logo.png', Setting::getLogoUrl());
    }

    public function test_public_pages_render_dynamic_logo(): void
    {
        Setting::set('site_logo', 'uploads/media/test-foundation-logo.webp', 'string', 'general');

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee(asset('uploads/media/test-foundation-logo.webp'));

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertOk();
        $aboutResponse->assertSee(asset('uploads/media/test-foundation-logo.webp'));
    }

    public function test_admin_settings_view_displays_logo_and_givebutter_inputs(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.settings'));
        $response->assertOk();
        $response->assertSeeText('Brand Identity & Official Site Logo');
        $response->assertSee('name="site_logo"', false);
        $response->assertSeeText('Givebutter Hosted Giving URL (U.S. Donors Only)');
        $response->assertSee('name="givebutter_url"', false);
    }

    public function test_admin_can_update_site_logo_and_givebutter_url(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $payload = [
            'site_logo' => 'uploads/logos/annual-logo.webp',
            'givebutter_url' => 'https://givebutter.com/custom-campaign',
            'stat_ict_students' => 200,
            'stat_literacy_students' => 650,
            'stat_schools_supported' => 20,
            'stat_feed_mode' => 'manual',
            'contact_phone_1' => '+234 904 249 3883',
            'contact_email' => 'info@dzuelsfoundation.org',
            'contact_address' => '12b Doherty Road, Ijero-Ekiti',
            'flutterwave_url' => 'https://flutterwave.com/pay/test',
            'bank_name' => 'GTBank Plc',
            'bank_account_name' => 'Dzuels Educational Foundation',
            'bank_account_number' => '0450072850',
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings'));
        $response->assertSessionHas('success');

        Setting::clearCache();
        $this->assertSame('uploads/logos/annual-logo.webp', Setting::get('site_logo'));
        $this->assertSame('https://givebutter.com/custom-campaign', Setting::get('givebutter_url'));
        $this->assertSame(asset('uploads/logos/annual-logo.webp'), Setting::getLogoUrl());
    }

    public function test_donate_page_renders_givebutter_channel_with_exact_us_donors_button_label(): void
    {
        Setting::set('givebutter_url', 'https://givebutter.com/dzuels-foundation', 'string', 'banking');

        $response = $this->get('/donate');
        $response->assertOk();
        $response->assertSeeText('U.S. Diaspora Giving');
        $response->assertSeeText('For U.S. Donors Only.');
        $response->assertSee('href="https://givebutter.com/dzuels-foundation"', false);
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);
    }
}
