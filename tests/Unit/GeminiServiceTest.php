<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Services\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::clearCache();
    }

    public function test_it_resolves_api_key_and_model_from_settings(): void
    {
        Setting::set('gemini_api_key', 'test-custom-key', 'string', 'ai');
        Setting::set('gemini_model', 'gemini-2.0-flash', 'string', 'ai');

        $service = new GeminiService;

        $this->assertEquals('test-custom-key', Setting::getGeminiApiKey());
        $this->assertEquals('gemini-2.0-flash', Setting::getGeminiModel());
    }

    public function test_it_throws_exception_when_api_key_is_missing(): void
    {
        Setting::set('gemini_api_key', '', 'string', 'ai');
        $service = new GeminiService('', 'gemini-1.5-flash');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Google Gemini API Key is not configured');

        $service->generateText('Write a brief description');
    }

    public function test_generate_text_formats_payload_and_returns_text_using_http_fake(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Inspiring community literacy programme in Ijero-Ekiti.'],
                            ],
                            'role' => 'model',
                        ],
                        'finishReason' => 'STOP',
                    ],
                ],
            ], 200),
        ]);

        $service = new GeminiService('test-key', 'gemini-1.5-flash');
        $result = $service->generateText('Write a programme summary');

        $this->assertEquals('Inspiring community literacy programme in Ijero-Ekiti.', $result);

        Http::assertSent(function ($request) {
            $data = $request->data();

            return str_contains($request->url(), 'key=test-key')
                && str_contains($request->url(), 'gemini-1.5-flash')
                && isset($data['system_instruction']['parts'][0]['text'])
                && str_contains($data['system_instruction']['parts'][0]['text'], 'British / Commonwealth English')
                && $data['contents'][0]['parts'][0]['text'] === 'Write a programme summary';
        });
    }

    public function test_generate_text_includes_multimodal_image_when_provided(): void
    {
        // Create a temporary test image
        $tempPath = public_path('uploads/test-sample.webp');
        File::ensureDirectoryExists(dirname($tempPath));
        File::put($tempPath, 'fake-image-binary-data');

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Students participating in spelling bee competition.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = new GeminiService('test-key', 'gemini-1.5-flash');
        $result = $service->generateText('Generate caption for this photo', null, 'uploads/test-sample.webp');

        $this->assertEquals('Students participating in spelling bee competition.', $result);

        Http::assertSent(function ($request) {
            $parts = $request->data()['contents'][0]['parts'];

            $hasInlineData = false;
            foreach ($parts as $part) {
                if (isset($part['inline_data'])) {
                    $hasInlineData = true;
                    $this->assertEquals('image/webp', $part['inline_data']['mime_type']);
                    $this->assertEquals(base64_encode('fake-image-binary-data'), $part['inline_data']['data']);
                }
            }

            return $hasInlineData;
        });

        if (File::exists($tempPath)) {
            File::delete($tempPath);
        }
    }

    public function test_generate_text_throws_exception_on_api_error(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 400,
                    'message' => 'API key not valid.',
                ],
            ], 400),
        ]);

        $service = new GeminiService('invalid-key', 'gemini-1.5-flash');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('API key not valid.');

        $service->generateText('Test prompt');
    }

    public function test_test_connection_returns_success_on_valid_ping(): void
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

        $service = new GeminiService('valid-key', 'gemini-1.5-flash');
        $result = $service->testConnection();

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('Successfully connected', $result['message']);
    }

    public function test_test_connection_returns_failure_on_invalid_key_or_error(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 403,
                    'message' => 'Quota exceeded',
                ],
            ], 403),
        ]);

        $service = new GeminiService('quota-key', 'gemini-1.5-flash');
        $result = $service->testConnection();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Quota exceeded', $result['message']);
    }

    public function test_test_connection_fails_immediately_if_key_is_empty(): void
    {
        $service = new GeminiService('', 'gemini-1.5-flash');
        $result = $service->testConnection();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Gemini API Key is missing', $result['message']);
    }
}
