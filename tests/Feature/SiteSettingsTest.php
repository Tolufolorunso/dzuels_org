<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Services\ImpactStatsService;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
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

    public function test_admin_can_view_settings_page_with_verified_baseline(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings');

        $response->assertOk();
        $response->assertSee('500');
        $response->assertSee('591');
        $response->assertSee('30');
        $response->assertSee('0450072850');
        $response->assertSee('+234 904 249 3883');
        $response->assertSee('info@dzuelsfoundation.org');
    }

    public function test_admin_can_update_site_settings(): void
    {
        $payload = [
            'stat_ict_students' => 200,
            'stat_literacy_students' => 650,
            'stat_schools_supported' => 20,
            'stat_feed_mode' => 'manual',
            'stat_api_endpoint' => null,

            'contact_phone_1' => '+234 803 000 1111',
            'contact_phone_2' => '+234 803 000 2222',
            'contact_email' => 'contact@dzuelsfoundation.org',
            'contact_address' => 'Updated Address, Ijero-Ekiti',

            'flutterwave_url' => 'https://flutterwave.com/pay/updated-slug',
            'bank_name' => 'Guaranty Trust Bank Plc',
            'bank_account_name' => 'Dzuels Educational Foundation',
            'bank_account_number' => '0450072850',

            'registration_number' => 'RC 999999',
            'social_linkedin' => 'https://linkedin.com/company/dzuels',
            'social_facebook' => 'https://facebook.com/dzuels',
            'social_twitter' => 'https://x.com/dzuels',
            'social_instagram' => 'https://instagram.com/dzuels',
        ];

        $response = $this->actingAs($this->admin)->put('/admin/settings', $payload);

        $response->assertRedirect('/admin/settings');
        $response->assertSessionHas('success');

        $this->assertSame(200, Setting::get('stat_ict_students'));
        $this->assertSame(650, Setting::get('stat_literacy_students'));
        $this->assertSame(20, Setting::get('stat_schools_supported'));
        $this->assertSame('+234 803 000 1111', Setting::get('contact_phone_1'));
    }

    public function test_settings_validation_rejects_invalid_values(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'stat_ict_students' => -10, // negative integer
            'contact_email' => 'invalid-email-format',
            'flutterwave_url' => 'not-a-valid-url',
        ]);

        $response->assertSessionHasErrors(['stat_ict_students', 'contact_email', 'flutterwave_url']);
    }

    public function test_setting_helper_casts_native_types(): void
    {
        Setting::set('test_int', 42, 'integer');
        Setting::set('test_bool_true', true, 'boolean');
        Setting::set('test_bool_false', false, 'boolean');
        Setting::set('test_json', ['a' => 1, 'b' => 2], 'json');

        $this->assertSame(42, Setting::get('test_int'));
        $this->assertTrue(Setting::get('test_bool_true'));
        $this->assertFalse(Setting::get('test_bool_false'));
        $this->assertSame(['a' => 1, 'b' => 2], Setting::get('test_json'));
        $this->assertSame('default_val', Setting::get('non_existent', 'default_val'));
    }

    public function test_impact_stats_service_resolves_manual_mode(): void
    {
        $service = new ImpactStatsService;

        Setting::set('stat_feed_mode', 'manual');
        Setting::set('stat_ict_students', 165, 'integer');
        Setting::set('stat_literacy_students', 591, 'integer');
        Setting::set('stat_schools_supported', 15, 'integer');

        $stats = $service->getStats();
        $this->assertSame(165, $stats['ict_students']);
        $this->assertSame(591, $stats['literacy_students']);
        $this->assertSame(15, $stats['schools_supported']);
        $this->assertSame('manual', $stats['source']);
    }

    public function test_impact_stats_service_resolves_api_mode(): void
    {
        $service = new ImpactStatsService;

        Http::fake([
            'https://api.dzuelsfoundation.org/metrics' => Http::response([
                'ict_students' => 180,
                'literacy_students' => 620,
                'schools_supported' => 18,
            ], 200),
        ]);

        Setting::set('stat_feed_mode', 'api');
        Setting::set('stat_api_endpoint', 'https://api.dzuelsfoundation.org/metrics');

        $stats = $service->getStats();
        $this->assertSame(180, $stats['ict_students']);
        $this->assertSame(620, $stats['literacy_students']);
        $this->assertSame(18, $stats['schools_supported']);
        $this->assertSame('api', $stats['source']);
    }

    public function test_impact_stats_service_falls_back_to_database_on_api_error(): void
    {
        $service = new ImpactStatsService;

        Http::fake([
            'https://api.dzuelsfoundation.org/metrics' => Http::response(null, 500),
        ]);

        Setting::set('stat_feed_mode', 'api');
        Setting::set('stat_api_endpoint', 'https://api.dzuelsfoundation.org/metrics');
        Setting::set('stat_ict_students', 165, 'integer');
        Setting::set('stat_literacy_students', 591, 'integer');
        Setting::set('stat_schools_supported', 15, 'integer');

        $stats = $service->getStats();
        $this->assertSame(165, $stats['ict_students']);
        $this->assertSame(591, $stats['literacy_students']);
        $this->assertSame(15, $stats['schools_supported']);
        $this->assertSame('manual', $stats['source']);
    }
}
