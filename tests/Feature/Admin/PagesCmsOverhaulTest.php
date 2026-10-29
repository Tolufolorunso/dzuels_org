<?php

namespace Tests\Feature\Admin;

use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesCmsOverhaulTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_to_admin_login_from_pages_cms(): void
    {
        $response = $this->get('/admin/pages');
        $response->assertRedirect('/admin/login');

        $response = $this->get('/admin/pages/about');
        $response->assertRedirect('/admin/login');

        $response = $this->post('/admin/pages/about/sections/hero/toggle-status');
        $response->assertRedirect('/admin/login');

        $response = $this->post('/admin/pages/about/sections/hero/move-down');
        $response->assertRedirect('/admin/login');

        $response = $this->put('/admin/pages/about/sections/hero', [
            'headline' => 'Test Headline',
        ]);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_pages_index_with_active_sections_count(): void
    {
        $this->seed(PageSectionSeeder::class);

        $response = $this->actingAs($this->admin)->get('/admin/pages');

        $response->assertOk();
        $response->assertSee('Site Pages');
        $response->assertSee('Content Management');
        $response->assertSee('About Us');
        $response->assertSee('Home Page');
        $response->assertSee('Active');
    }

    public function test_admin_can_view_edit_page_with_reorder_and_toggle_controls(): void
    {
        $this->seed(PageSectionSeeder::class);

        $response = $this->actingAs($this->admin)->get('/admin/pages/about');

        $response->assertOk();
        $response->assertSee('About Us');
        $response->assertSee('Active (Visible)');
        $response->assertSee('Hide on Site');
        $response->assertSee('Move section up');
        $response->assertSee('Move section down');
        $response->assertSee('Section Active');
    }

    public function test_admin_can_toggle_section_visibility_and_public_page_reflects_change(): void
    {
        $this->seed(PageSectionSeeder::class);

        // Verify public about page initially shows the hero headline
        $publicBefore = $this->get('/about');
        $publicBefore->assertOk();
        $publicBefore->assertSee('Transforming Lives Page by Page');

        // Toggle hero section to inactive
        $response = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->post('/admin/pages/about/sections/hero/toggle-status');

        $response->assertRedirect('/admin/pages/about');
        $response->assertSessionHas('success', "Section 'hero' is now Hidden from Live Page.");

        $hero = PageSection::where('page_key', 'about')->where('section_key', 'hero')->first();
        $this->assertFalse($hero->is_active);

        // Public about page should now hide the hero section
        $publicAfter = $this->get('/about');
        $publicAfter->assertOk();
        $publicAfter->assertDontSee('Transforming Lives Page by Page');

        // Toggle back to active
        $responseAgain = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->post('/admin/pages/about/sections/hero/toggle-status');

        $responseAgain->assertRedirect('/admin/pages/about');
        $responseAgain->assertSessionHas('success', "Section 'hero' is now Active (Visible on Live Page).");

        $hero->refresh();
        $this->assertTrue($hero->is_active);

        // Public page displays hero headline again
        $publicRestored = $this->get('/about');
        $publicRestored->assertOk();
        $publicRestored->assertSee('Transforming Lives Page by Page');
    }

    public function test_admin_can_reorder_sections_with_move_down_and_move_up(): void
    {
        $this->seed(PageSectionSeeder::class);

        $hero = PageSection::where('page_key', 'about')->where('section_key', 'hero')->first();
        $story = PageSection::where('page_key', 'about')->where('section_key', 'story')->first();

        $initialHeroOrder = $hero->sort_order;
        $initialStoryOrder = $story->sort_order;
        $this->assertLessThan($initialStoryOrder, $initialHeroOrder);

        // Move hero down (swap with story)
        $response = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->post('/admin/pages/about/sections/hero/move-down');

        $response->assertRedirect('/admin/pages/about');
        $response->assertSessionHas('success', "Section 'hero' moved down.");

        $hero->refresh();
        $story->refresh();
        $this->assertGreaterThan($story->sort_order, $hero->sort_order);

        // Move hero back up (swap with story)
        $responseUp = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->post('/admin/pages/about/sections/hero/move-up');

        $responseUp->assertRedirect('/admin/pages/about');
        $responseUp->assertSessionHas('success', "Section 'hero' moved up.");

        $hero->refresh();
        $story->refresh();
        $this->assertLessThan($story->sort_order, $hero->sort_order);
    }

    public function test_admin_can_update_section_content_and_public_view_displays_updated_text(): void
    {
        $this->seed(PageSectionSeeder::class);

        $response = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->put('/admin/pages/about/sections/hero', [
                'headline' => 'Updated Custom About Mission Headline',
                'subheadline' => 'Exclusive Vision',
                'body_text' => 'We are empowering children with world-class education tools.',
                'button_text' => 'Support Today',
                'button_url' => '/donate',
                'is_active' => '1',
            ]);

        $response->assertRedirect('/admin/pages/about');
        $response->assertSessionHas('success', "Section 'hero' updated successfully!");

        $hero = PageSection::where('page_key', 'about')->where('section_key', 'hero')->first();
        $this->assertEquals('Updated Custom About Mission Headline', $hero->headline);
        $this->assertEquals('Exclusive Vision', $hero->subheadline);
        $this->assertTrue($hero->is_active);

        // Verify public view reflects the new copy
        $public = $this->get('/about');
        $public->assertOk();
        $public->assertSee('Updated Custom About Mission Headline');
        $public->assertSee('Exclusive Vision');
    }

    public function test_toggling_donate_faq_section_hides_faq_on_donate_page(): void
    {
        $this->seed(PageSectionSeeder::class);

        // Initially public donate page displays FAQ headline
        $publicBefore = $this->get('/donate');
        $publicBefore->assertOk();
        $publicBefore->assertSee('Frequently Asked Questions');

        // Toggle FAQ section inactive
        $this->actingAs($this->admin)
            ->post('/admin/pages/donate/sections/faq/toggle-status');

        $faq = PageSection::where('page_key', 'donate')->where('section_key', 'faq')->first();
        $this->assertFalse($faq->is_active);

        // Public donate page hides FAQ section
        $publicAfter = $this->get('/donate');
        $publicAfter->assertOk();
        $publicAfter->assertDontSee('Frequently Asked Questions');
    }
}
