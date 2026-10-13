<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_renders_successfully_with_story_and_holistic_pillars(): void
    {
        $response = $this->get('/about');

        $response->assertOk();

        // Meta & Breadcrumb
        $response->assertSeeText('About Us');
        $response->assertSeeText('Home');
        $response->assertSeeText('Transforming Lives');
        $response->assertSeeText('Page by Page');
        $response->assertSee('front/images/gallery/optimized/general-01.webp');

        // Story & 10-Year Narrative
        $response->assertSeeText('A Decade of Dedicated Service (Since 2015)');
        $response->assertSeeText('22nd June 2015');
        $response->assertSeeText('10+');
        $response->assertSeeText('Years of Service');

        // 6 Holistic Approach Pillars
        $response->assertSeeText('Our Integrated Intervention Approach');
        $response->assertSeeText('Tuition & School Fees Support');
        $response->assertSeeText('Educational Materials & Uniforms');
        $response->assertSeeText('Equipping Non-Profit Schools & Labs');
        $response->assertSeeText('Mentorship & Leadership Training');
        $response->assertSeeText('Family Nutrition & Widow Welfare');
        $response->assertSeeText('Health Services & Social Awareness');

        // Section IDs for footer navigation
        $response->assertSee('id="trustees"', false);
        $response->assertSee('id="management"', false);

        // Call to Action
        $response->assertSeeText('Join Hands with Us to Transform More Lives');
        $response->assertSeeText('Become a Volunteer');
        $response->assertSeeText('Support Our Mission (Donate)');
    }

    public function test_about_page_displays_board_of_trustees_with_roles_and_founder_badge(): void
    {
        TeamMember::create([
            'name' => 'Bolaji Agbelusi',
            'role' => 'Founder & Trustee',
            'group' => 'trustee',
            'photo' => 'front/images/bolaji.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Anna John-Yakubu',
            'role' => 'Trustee',
            'group' => 'trustee',
            'photo' => 'front/images/anna.jpg',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSeeText('Bolaji Agbelusi');
        $response->assertSeeText('Founder & Trustee');
        $response->assertSeeText('Founder'); // founder pill badge
        $response->assertSeeText('Anna John-Yakubu');
        $response->assertSeeText('Trustee');
    }

    public function test_about_page_displays_management_team_with_country_roles(): void
    {
        TeamMember::create([
            'name' => 'Mrs. Fadekemi Humphries',
            'role' => 'Country Manager (USA)',
            'group' => 'management',
            'photo' => 'front/images/mgt_fadekemi.jpeg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Ms. Michelle Sarumi',
            'role' => 'Country Manager (Nigeria)',
            'group' => 'management',
            'photo' => 'front/images/mgt_michelle.png',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSeeText('Mrs. Fadekemi Humphries');
        $response->assertSeeText('Country Manager (USA)');
        $response->assertSeeText('USA Chapter');
        $response->assertSeeText('Ms. Michelle Sarumi');
        $response->assertSeeText('Country Manager (Nigeria)');
        $response->assertSeeText('Nigeria Chapter');
    }

    public function test_about_page_displays_advisory_board_with_domain_pills_and_linkedin_links(): void
    {
        TeamMember::create([
            'name' => 'Solape Adesuyi',
            'role' => 'Advisor',
            'group' => 'advisor',
            'field' => 'Corporate Legal Services',
            'bio' => 'Principal Partner, Solape Adesuyi Associates',
            'linkedin_url' => 'https://www.linkedin.com/in/solape-adesuyi-test',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSeeText('Solape Adesuyi');
        $response->assertSeeText('Corporate Legal Services');
        $response->assertSeeText('Principal Partner, Solape Adesuyi Associates');
        $response->assertSee('https://www.linkedin.com/in/solape-adesuyi-test');
        $response->assertSee('aria-label="View Solape Adesuyi\'s LinkedIn profile"', false);
    }

    public function test_about_page_displays_partners_with_logos_and_links(): void
    {
        Partner::create([
            'name' => 'ASCEND Education Foundation',
            'logo' => 'front/images/ascend.jpg',
            'website_url' => 'https://ascendedufoundation.org/',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('alt="ASCEND Education Foundation"', false);
        $response->assertSee('href="https://ascendedufoundation.org/"', false);
    }

    public function test_about_page_hides_inactive_team_members_and_partners(): void
    {
        TeamMember::create([
            'name' => 'Hidden Ex-Trustee',
            'role' => 'Trustee',
            'group' => 'trustee',
            'is_active' => false,
        ]);

        Partner::create([
            'name' => 'Hidden Ex-Partner',
            'logo' => 'front/images/hidden.png',
            'is_active' => false,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertDontSeeText('Hidden Ex-Trustee');
        $response->assertDontSee('alt="Hidden Ex-Partner"', false);
    }

    public function test_about_page_renders_gracefully_when_tables_are_empty(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSeeText('Trustee profiles are currently being updated.');
        $response->assertSeeText('Management profiles are currently being updated.');
        $response->assertSeeText('Advisory board roster is currently being updated.');
        $response->assertSeeText('Interested in partnering with Dzuels Educational Foundation?');
    }
}
