<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageOptimizerDiagnosticTest extends TestCase
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
    }

    public function test_super_admin_can_view_image_optimizer_card_in_settings(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.settings'));

        $response->assertOk();
        $response->assertSee('Image Optimization');
        $response->assertSee('WebP Pipeline');
        $response->assertSee('Test Image Optimization');
        $response->assertSee('PHP GD Extension');
        $response->assertSee('Imagick Extension');
    }

    public function test_super_admin_can_run_live_test_optimizer_endpoint(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.settings.test-optimizer'));

        $response->assertOk();
        $response->assertJsonStructure([
            'environment' => [
                'is_operational',
                'active_engine',
                'gd' => ['loaded', 'version', 'webp_supported', 'available'],
                'imagick' => ['loaded', 'version', 'webp_supported', 'available'],
                'php' => ['version', 'memory_limit', 'upload_max_filesize', 'post_max_size'],
                'storage' => ['uploads_writable', 'public_path'],
            ],
            'test_result' => [
                'success',
                'engine',
                'message',
            ],
        ]);

        $data = $response->json();
        $this->assertTrue($data['environment']['is_operational']);
        $this->assertTrue($data['test_result']['success']);
        $this->assertSame('gd', $data['test_result']['engine']);
    }

    public function test_editor_cannot_access_test_optimizer_endpoint(): void
    {
        $response = $this->actingAs($this->editor)
            ->postJson(route('admin.settings.test-optimizer'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_from_test_optimizer_endpoint(): void
    {
        $response = $this->post(route('admin.settings.test-optimizer'));

        $response->assertRedirect(route('admin.login'));
    }
}
