<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiContentGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        Setting::clearCache();
        Setting::set('gemini_api_key', 'test-fake-key', 'string', 'ai');
    }

    public function test_guest_cannot_access_ai_generate_endpoint(): void
    {
        $this->post(route('admin.ai.generate'), [
            'field_name' => 'body',
        ])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_generate_content_successfully(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Empowering youth through free community digital literacy workshops in Ijero-Ekiti.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.ai.generate'), [
                'field_name' => 'summary',
                'field_label' => 'Programme Summary',
                'page_context' => 'Programmes',
                'section_context' => 'Ijero Digital Literacy',
                'sibling_data' => [
                    'title' => 'Ijero Tech Day 2026',
                ],
                'prompt_tweak' => 'Keep it under 30 words',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'text' => 'Empowering youth through free community digital literacy workshops in Ijero-Ekiti.',
        ]);

        Http::assertSent(function ($request) {
            $prompt = $request->data()['contents'][0]['parts'][0]['text'];

            return str_contains($prompt, 'Programme Summary')
                && str_contains($prompt, 'Ijero Tech Day 2026')
                && str_contains($prompt, 'Keep it under 30 words');
        });
    }

    public function test_generate_content_validates_required_fields(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.ai.generate'), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['field_name']);
    }

    public function test_generate_content_handles_image_path_for_multimodal_vision(): void
    {
        $tempPath = public_path('uploads/test-photo.webp');
        File::ensureDirectoryExists(dirname($tempPath));
        File::put($tempPath, 'dummy-image-bytes');

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Students actively reading textbooks in the library.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.ai.generate'), [
                'field_name' => 'caption',
                'field_label' => 'Photo Caption',
                'page_context' => 'Gallery Collection',
                'section_context' => 'Famoyinka Library',
                'image_path' => 'uploads/test-photo.webp',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'text' => 'Students actively reading textbooks in the library.',
        ]);

        Http::assertSent(function ($request) {
            $parts = $request->data()['contents'][0]['parts'];
            $hasInlineData = false;
            foreach ($parts as $part) {
                if (isset($part['inline_data'])) {
                    $hasInlineData = true;
                    $this->assertEquals('image/webp', $part['inline_data']['mime_type']);
                    $this->assertEquals(base64_encode('dummy-image-bytes'), $part['inline_data']['data']);
                }
            }

            return $hasInlineData;
        });

        if (File::exists($tempPath)) {
            File::delete($tempPath);
        }
    }

    public function test_generate_content_returns_422_when_gemini_service_throws_error(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 500,
                    'message' => 'Internal server error from Gemini.',
                ],
            ], 500),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.ai.generate'), [
                'field_name' => 'body',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('Internal server error', $response->json('error'));
    }
}
