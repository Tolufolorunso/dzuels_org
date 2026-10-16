<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiButtonComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_button_component_renders_with_target_field(): void
    {
        $admin = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $view = $this->actingAs($admin)->blade(
            '<x-admin.ai-button for="headline" field-name="headline" field-label="Section Headline" page-context="Home Page" section-context="Hero" />'
        );

        $view->assertSee('AI Write');
        $view->assertSee('targetId: \'headline\'', false);
        $view->assertSee('fieldName: \'headline\'', false);
        $view->assertSee('fieldLabel: \'Section Headline\'', false);
        $view->assertSee('pageContext: \'Home Page\'', false);
        $view->assertSee('sectionContext: \'Hero\'', false);
        $view->assertSee('Insert into Field');
    }

    public function test_ai_button_component_renders_with_multimodal_image_path_or_selector(): void
    {
        $admin = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $view = $this->actingAs($admin)->blade(
            '<x-admin.ai-button for="caption" field-name="caption" field-label="Photo Caption" image-path="front/images/gallery/optimized/famoyinka-01.webp" />'
        );

        $view->assertSee('staticImagePath: \'front/images/gallery/optimized/famoyinka-01.webp\'', false);
    }

    public function test_admin_forms_render_ai_sparkle_buttons(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        // Programme create form
        $response = $this->actingAs($admin)->get(route('admin.programmes.create'));
        $response->assertOk();
        $response->assertSee('AI Write');
        $response->assertSee('Programme Title');

        // Event create form
        $response = $this->actingAs($admin)->get(route('admin.events.create'));
        $response->assertOk();
        $response->assertSee('AI Write');
        $response->assertSee('Event Title');

        // News create form
        $response = $this->actingAs($admin)->get(route('admin.news.create'));
        $response->assertOk();
        $response->assertSee('AI Write');
        $response->assertSee('News Article Title');

        // Team create form
        $response = $this->actingAs($admin)->get(route('admin.team.create'));
        $response->assertOk();
        $response->assertSee('AI Write');
        $response->assertSee('Team Role');

        // Settings form
        $response = $this->actingAs($admin)->get(route('admin.settings'));
        $response->assertOk();
        $response->assertSee('AI Write');
        $response->assertSee('AI Writing Assistant (Google Gemini)');
    }
}
