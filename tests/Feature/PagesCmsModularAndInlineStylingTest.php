<?php

namespace Tests\Feature;

use App\Models\PageSection;
use App\Models\User;
use App\Support\CmsTextFormatter;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesCmsModularAndInlineStylingTest extends TestCase
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

    public function test_cms_text_formatter_escapes_xss_while_rendering_styling_tags(): void
    {
        // 1. Script injection must be strictly escaped
        $malicious = "<script>alert('hack')</script> [accent]quality education[/accent]";
        $formatted = CmsTextFormatter::format($malicious);
        $html = (string) $formatted;

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('<span class="text-maroon dark:text-mint font-semibold">quality education</span>', $html);

        // 2. All tags must render their configured styled wrappers
        $sample = '[crimson]red[/crimson] [mint]green[/mint] [cream]cream[/cream] [gold]gold[/gold] [bold]bolded[/bold] [italic]slanted[/italic] [underline]under[/underline] [badge]highlight[/badge]';
        $res = (string) CmsTextFormatter::format($sample);

        $this->assertStringContainsString('text-crimson font-bold', $res);
        $this->assertStringContainsString('text-mint font-bold', $res);
        $this->assertStringContainsString('text-[var(--accent-cream)] font-bold', $res);
        $this->assertStringContainsString('text-amber-500 font-bold', $res);
        $this->assertStringContainsString('<strong class="font-bold text-[var(--heading)]">bolded</strong>', $res);
        $this->assertStringContainsString('<em class="italic">slanted</em>', $res);
        $this->assertStringContainsString('underline decoration-crimson/50', $res);
        $this->assertStringContainsString('rounded-full text-xs font-bold', $res);
    }

    public function test_admin_can_update_about_story_milestones_and_floating_badge(): void
    {
        $this->seed(PageSectionSeeder::class);

        $payload = [
            'headline' => 'Our 10-Year [mint]Journey[/mint] in Ekiti',
            'subheadline' => 'Our Heritage',
            'body_text' => 'Custom story narrative text.',
            'sort_order' => 20,
            'is_active' => 1,
            'meta_data' => [
                'milestones' => [
                    ['value' => '12+', 'label' => 'Years of Dedication'],
                    ['value' => '850+', 'label' => 'Scholars Empowered'],
                    ['value' => '45+', 'label' => 'Certified Instructors'],
                ],
                'badge' => [
                    'kicker' => 'Special Milestone',
                    'title' => 'Over a Decade of Transformation',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->put('/admin/pages/about/sections/story', $payload);

        $response->assertRedirect('/admin/pages/about');
        $response->assertSessionHas('success');

        $section = PageSection::where('page_key', 'about')->where('section_key', 'story')->firstOrFail();
        $this->assertEquals('12+', $section->meta_data['milestones'][0]['value']);
        $this->assertEquals('Special Milestone', $section->meta_data['badge']['kicker']);

        // Check public about page
        $public = $this->get('/about');
        $public->assertOk();
        $public->assertSee('12+');
        $public->assertSee('Years of Dedication');
        $public->assertSee('Special Milestone');
        $public->assertSee('Over a Decade of Transformation');
        $public->assertSee('text-mint font-bold', false);
    }

    public function test_admin_can_update_about_approach_pillars(): void
    {
        $this->seed(PageSectionSeeder::class);

        $payload = [
            'headline' => 'Our Holistic [accent]Intervention Approach[/accent]',
            'subheadline' => 'Total Well-Being of the Child',
            'body_text' => 'We combine academic sponsorship and healthcare.',
            'sort_order' => 30,
            'is_active' => 1,
            'meta_data' => [
                'pillars' => [
                    [
                        'title' => 'Targeted Tuition Grants',
                        'description' => 'Fully paying fees for orphans and indigent pupils.',
                        'icon' => 'academic-cap',
                    ],
                    [
                        'title' => 'Digital Coding Hubs',
                        'description' => 'Hands-on programming and AI literacy.',
                        'icon' => 'computer-desktop',
                    ],
                    [
                        'title' => 'Nutritional Welfare Packages',
                        'description' => 'Food rations for vulnerable families.',
                        'icon' => 'heart',
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->put('/admin/pages/about/sections/approach', $payload);

        $response->assertRedirect('/admin/pages/about');
        $response->assertSessionHas('success');

        $section = PageSection::where('page_key', 'about')->where('section_key', 'approach')->firstOrFail();
        $this->assertEquals('Targeted Tuition Grants', $section->meta_data['pillars'][0]['title']);

        // Check public about page
        $public = $this->get('/about');
        $public->assertOk();
        $public->assertSee('Targeted Tuition Grants');
        $public->assertSee('Digital Coding Hubs');
        $public->assertSee('Nutritional Welfare Packages');
        $public->assertSee('text-maroon dark:text-mint font-semibold', false);
    }

    public function test_admin_can_update_about_famoyinka_library_bullets_and_badge(): void
    {
        $this->seed(PageSectionSeeder::class);

        $payload = [
            'headline' => "Famoyinka Memorial [gold]Children's Library[/gold]",
            'subheadline' => 'Permanent Sanctuary',
            'body_text' => 'A vibrant literary sanctuary.',
            'sort_order' => 40,
            'is_active' => 1,
            'meta_data' => [
                'bullets' => [
                    'Over 2,500 curated volumes and scientific texts',
                    'Interactive coding workstations with high-speed internet',
                    'Weekend creative writing and calligraphy circles',
                    'Annual excursion to Ikogosi Warm Springs resort',
                ],
                'badge' => [
                    'location' => 'Ijero-Ekiti Center',
                    'title' => 'Beacon of Rural Literacy',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->from('/admin/pages/about')
            ->put('/admin/pages/about/sections/famoyinka_library', $payload);

        $response->assertRedirect('/admin/pages/about');
        $response->assertSessionHas('success');

        // Check public about page
        $public = $this->get('/about');
        $public->assertOk();
        $public->assertSee('Over 2,500 curated volumes and scientific texts');
        $public->assertSee('Interactive coding workstations with high-speed internet');
        $public->assertSee('Ijero-Ekiti Center');
        $public->assertSee('Beacon of Rural Literacy');
        $public->assertSee('text-amber-500 font-bold', false);
    }

    public function test_public_homepage_renders_inline_cms_styling_tags(): void
    {
        $this->seed(PageSectionSeeder::class);

        // Update home hero headline with inline style tag as in user prompt
        $hero = PageSection::where('page_key', 'home')->where('section_key', 'hero')->firstOrFail();
        $hero->headline = 'Addressing poverty through [crimson]quality education[/crimson] and skills acquisition';
        $hero->save();

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Addressing poverty through', false);
        $response->assertSee('<span class="text-crimson font-bold">quality education</span> and skills acquisition', false);
    }
}
