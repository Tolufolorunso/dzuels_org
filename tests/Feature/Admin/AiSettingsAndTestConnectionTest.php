<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiSettingsAndTestConnectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        Setting::clearCache();
    }

    public function test_super_admin_can_view_ai_settings_card(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.settings'));

        $response->assertOk();
        $response->assertSee('AI Writing Assistant (Google Gemini)');
        $response->assertSee('Google Gemini API Key');
        $response->assertSee('gemini_model');
        $response->assertSee('Test Connection');
    }

    public function test_editor_cannot_access_settings_page(): void
    {
        $response = $this->actingAs($this->editor)->get(route('admin.settings'));

        $response->assertForbidden();
    }

    public function test_super_admin_can_update_gemini_api_key_and_model(): void
    {
        $payload = [
            'stat_ict_students' => 165,
            'stat_literacy_students' => 591,
            'stat_schools_supported' => 15,
            'stat_feed_mode' => 'manual',
            'contact_phone_1' => '+234 904 249 3883',
            'contact_email' => 'info@dzuels.org',
            'contact_address' => 'Ijero-Ekiti, Nigeria',
            'flutterwave_url' => 'https://flutterwave.com/pay/test',
            'bank_name' => 'Guaranty Trust Bank',
            'bank_account_name' => 'Dzuels Educational Foundation',
            'bank_account_number' => '0450072850',
            'gemini_api_key' => 'AIzaSyNewTestKey123',
            'gemini_model' => 'gemini-2.0-flash',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->put(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings'));
        $response->assertSessionHas('success');

        Setting::clearCache();
        $this->assertEquals('AIzaSyNewTestKey123', Setting::getGeminiApiKey());
        $this->assertEquals('gemini-2.0-flash', Setting::getGeminiModel());
    }

    public function test_test_gemini_endpoint_returns_success_with_valid_key(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [['text' => 'OK']],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.settings.test-gemini'), [
                'api_key' => 'valid-test-key',
                'model' => 'gemini-1.5-flash',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertStringContainsString('Successfully connected', $response->json('message'));
    }

    public function test_test_gemini_endpoint_returns_error_with_empty_or_invalid_key(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.settings.test-gemini'), [
                'api_key' => '',
                'model' => 'gemini-1.5-flash',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('Gemini API Key is missing', $response->json('message'));
    }

    public function test_test_gemini_requires_authentication_and_super_admin(): void
    {
        $this->post(route('admin.settings.test-gemini'), ['api_key' => 'key'])
            ->assertRedirect(route('admin.login'));

        $this->actingAs($this->editor)
            ->postJson(route('admin.settings.test-gemini'), ['api_key' => 'key'])
            ->assertForbidden();
    }
}
