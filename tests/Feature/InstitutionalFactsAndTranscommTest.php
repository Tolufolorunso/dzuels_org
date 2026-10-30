<?php

namespace Tests\Feature;

use App\Models\Programme;
use Database\Seeders\PageSectionSeeder;
use Database\Seeders\PartnerSeeder;
use Database\Seeders\ProgrammeSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionalFactsAndTranscommTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            SettingSeeder::class,
            PartnerSeeder::class,
            PageSectionSeeder::class,
            ProgrammeSeeder::class,
        ]);
    }

    public function test_homepage_renders_all_four_exact_copy_replacements(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        // Exact Copy Replacement 1: Causes Headline
        $response->assertSeeText('Programs that Bridge the Opportunity Divide');

        // Exact Copy Replacement 2: Causes Body Text
        $response->assertSeeText('From digital hubs in Ijero-Ekiti to annual literacy contest, and much more in between, our causes are designed to uplift communities from the ground up.');

        // Exact Copy Replacement 3: Computer Lab & Library at Dzuels Foundation (not public schools)
        $response->assertSeeText('Establishing a well-equipped computer laboratory and a resourceful library at Dzuels Foundation, providing children with access to practical computer training, coding skills, reading materials, and a supportive learning environment. To date, over 500 students have benefited from these learning opportunities.');

        // Exact Copy Replacement 4: Home CTA Body Text
        $response->assertSeeText('Whether through individual, family or corporate sponsorship, volunteer mentorship, financial donations or legacy giving, your partnership empowers the next generation.');
    }

    public function test_transformational_leadership_transcomm_programme_is_seeded_and_accessible(): void
    {
        $programme = Programme::where('slug', 'transformational-leadership-transcomm')->first();

        $this->assertNotNull($programme);
        $this->assertSame('Transformational leadership (TRANSCOMM)', $programme->title);
        $this->assertTrue($programme->is_active);
        $this->assertTrue($programme->is_featured);

        // Catalog route
        $catalogResponse = $this->get('/programmes');
        $catalogResponse->assertOk();
        $catalogResponse->assertSeeText('Transformational leadership (TRANSCOMM)');

        // Individual detail route
        $detailResponse = $this->get('/programmes/transformational-leadership-transcomm');
        $detailResponse->assertOk();
        $detailResponse->assertSeeText('Transformational leadership (TRANSCOMM)');
        $detailResponse->assertSeeText('DRNICER');
    }

    public function test_verified_metrics_reflect_500_students_and_30_teachers_trained(): void
    {
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSeeText('Ekiti students in computer lab & coding training');
        $homeResponse->assertSeeText('500');
        $homeResponse->assertSeeText('Teachers Trained in Digital Skills');
        $homeResponse->assertSeeText('30');

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertOk();
        $aboutResponse->assertSeeText('30+');
        $aboutResponse->assertSeeText('Teachers Trained');

        $donateResponse = $this->get('/donate');
        $donateResponse->assertOk();
        $donateResponse->assertSeeText('over 500 students benefited through our learning lab & library, and 30 teachers trained.');

        $getInvolvedResponse = $this->get('/get-involved');
        $getInvolvedResponse->assertOk();
        $getInvolvedResponse->assertSeeText('500+');
        $getInvolvedResponse->assertSeeText('Students Benefited');
        $getInvolvedResponse->assertSeeText('30');
        $getInvolvedResponse->assertSeeText('Teachers Trained');
    }
}
