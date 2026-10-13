<?php

namespace Tests\Feature;

use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImpactCausesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_all_6_authentic_causes_and_tagline_banner(): void
    {
        $this->seed(PageSectionSeeder::class);

        $response = $this->get('/');
        $response->assertOk();

        // Check tagline banner
        $response->assertSeeText('AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD');
        $response->assertSeeText('6 Foundational Pillars');

        // Check all 6 pillars and ministries
        $response->assertSeeText('Education Support');
        $response->assertSeeText("Paul's academy");

        $response->assertSeeText('Skills Acquisition');
        $response->assertSeeText("Peter's net");

        $response->assertSeeText('Advocacy');
        $response->assertSeeText('Deborah congress');

        $response->assertSeeText('Feeding families');
        $response->assertSeeText("Naomi's kitchen");

        $response->assertSeeText('Transformative leadership Training');
        $response->assertSeeText("Priscilla's upper room");

        $response->assertSeeText('Health awareness and support');
        $response->assertSeeText("Luke's Tent");
    }

    public function test_homepage_falls_back_to_default_6_pillars_when_meta_data_empty(): void
    {
        PageSection::create([
            'page_key' => 'home',
            'section_key' => 'causes',
            'headline' => 'Default Causes Headline',
            'subheadline' => 'Our 6 Causes',
            'body_text' => 'Supporting children.',
            'meta_data' => null,
            'sort_order' => 5,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSeeText('AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD');
        $response->assertSeeText('Education Support');
        $response->assertSeeText("Paul's academy");
        $response->assertSeeText("Luke's Tent");
    }

    public function test_admin_pages_home_editor_displays_causes_cms(): void
    {
        $this->seed(PageSectionSeeder::class);

        $admin = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.pages.edit', 'home'));
        $response->assertOk();
        $response->assertSee('Foundational Tagline Banner');
        $response->assertSee('name="meta_data[tagline]"', false);
        $response->assertSee('The 6 Authentic Impact Pillars');
        $response->assertSee('name="meta_data[pillars][0][title]"', false);
        $response->assertSee('name="meta_data[pillars][0][ministry]"', false);
    }

    public function test_admin_can_update_causes_tagline_and_pillars(): void
    {
        $this->seed(PageSectionSeeder::class);

        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $payload = [
            'headline' => 'Empowering Through Holistic Care',
            'subheadline' => 'Our Core Pillars',
            'body_text' => 'Comprehensive interventions for the child.',
            'meta_data' => [
                'tagline' => 'CUSTOM INTEGRATED APPROACH TO CHILD EMPOWERMENT',
                'pillars' => [
                    [
                        'title' => 'Advanced Tech Education',
                        'ministry' => 'Paul Technology Academy',
                        'description' => 'Coding and robotics scholarships for bright students.',
                        'icon' => '💻',
                    ],
                    [
                        'title' => 'Vocational Carpentry & Crafts',
                        'ministry' => 'Peter Crafts Net',
                        'description' => 'Hands-on trade skills for older youth.',
                        'icon' => '🔨',
                    ],
                    [
                        'title' => 'Child Legal Rights Advocacy',
                        'ministry' => 'Deborah Law Center',
                        'description' => 'Protecting youth in rural courts.',
                        'icon' => '⚖️',
                    ],
                    [
                        'title' => 'Daily School Meals',
                        'ministry' => 'Naomi Warm Kitchen',
                        'description' => 'Nutritious lunch programs in local schools.',
                        'icon' => '🍲',
                    ],
                    [
                        'title' => 'Youth Mentorship Fellowship',
                        'ministry' => 'Priscilla Fellowship',
                        'description' => 'Ethical character formation retreats.',
                        'icon' => '🌱',
                    ],
                    [
                        'title' => 'Rural Dental & Eye Clinic',
                        'ministry' => 'Luke Healing Clinic',
                        'description' => 'Free checkups and glasses for school children.',
                        'icon' => '👓',
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.pages.sections.update', ['page' => 'home', 'section' => 'causes']), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $section = PageSection::where('page_key', 'home')->where('section_key', 'causes')->first();
        $this->assertNotNull($section);
        $this->assertSame('CUSTOM INTEGRATED APPROACH TO CHILD EMPOWERMENT', $section->meta_data['tagline']);
        $this->assertSame('Advanced Tech Education', $section->meta_data['pillars'][0]['title']);

        // Verify update reflects on public homepage
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSeeText('CUSTOM INTEGRATED APPROACH TO CHILD EMPOWERMENT');
        $homeResponse->assertSeeText('Advanced Tech Education');
        $homeResponse->assertSeeText('Paul Technology Academy');
        $homeResponse->assertSeeText('Rural Dental & Eye Clinic');
    }
}
