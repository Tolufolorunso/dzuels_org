<?php

namespace Tests\Feature;

use App\Models\Programme;
use Database\Seeders\GallerySeeder;
use Database\Seeders\ProgrammeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgrammePublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_programmes_index_renders_successfully_with_active_programmes(): void
    {
        Programme::create([
            'title' => 'Digital Literacy Project',
            'slug' => 'digital-literacy-project',
            'summary' => 'Comprehensive digital training for students.',
            'body' => 'Full narrative description of the initiative.',
            'partner_name' => 'SeedDev',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Programme::create([
            'title' => 'Spelling Bee Challenge',
            'slug' => 'spelling-bee-challenge',
            'summary' => 'Inter-school language competition.',
            'body' => 'Full narrative for spelling bee challenge.',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $response = $this->get('/programmes');

        $response->assertOk();
        $response->assertSeeText('Our Impact');
        $response->assertSeeText('Programmes');
        $response->assertSeeText('Digital Literacy Project');
        $response->assertSeeText('Featured Initiative');
        $response->assertSeeText('Spelling Bee Challenge');
        $response->assertSeeText('SeedDev');
        $response->assertSee('href="'.url('/programmes/digital-literacy-project').'"', false);
    }

    public function test_programmes_detail_page_renders_successfully(): void
    {
        $programme = Programme::create([
            'title' => 'Digital Infrastructure and Literacy in Ijero-Ekiti',
            'slug' => 'ijero-digital-literacy',
            'summary' => 'Free literacy and computer skills for Ijero schools.',
            'body' => "This is paragraph one of the project narrative.\n\nThis is paragraph two explaining technical equipment.",
            'partner_name' => 'Information Technology Seed Development Initiative (SeedDev)',
            'is_featured' => true,
            'is_active' => true,
            'seo_title' => 'Custom SEO Title | Dzuels',
            'seo_description' => 'Custom SEO description of Ijero literacy project.',
        ]);

        $response = $this->get('/programmes/ijero-digital-literacy');

        $response->assertOk();
        $response->assertSeeText('Digital Infrastructure and Literacy in Ijero-Ekiti');
        $response->assertSeeText('This is paragraph one of the project narrative.');
        $response->assertSeeText('This is paragraph two explaining technical equipment.');
        $response->assertSeeText('SeedDev');
        $response->assertSeeText('Project Quick Facts');
        $response->assertSeeText('Ekiti State, Nigeria');
        $response->assertSeeText('Donate to this Project');
        $response->assertSeeText('Volunteer With Us');
        $response->assertSee('Custom SEO Title | Dzuels');
    }

    public function test_inactive_programme_returns_404_on_detail_page(): void
    {
        Programme::create([
            'title' => 'Hidden Programme',
            'slug' => 'hidden-programme',
            'summary' => 'Draft summary',
            'body' => 'Draft body',
            'is_active' => false,
        ]);

        $response = $this->get('/programmes/hidden-programme');

        $response->assertNotFound();
    }

    public function test_non_existent_slug_returns_404(): void
    {
        $response = $this->get('/programmes/non-existent-programme-slug');

        $response->assertNotFound();
    }

    public function test_programmes_index_handles_empty_database_gracefully(): void
    {
        $response = $this->get('/programmes');

        $response->assertOk();
        $response->assertSeeText('Programme details are currently being updated.');
    }

    public function test_spelling_bee_contest_programme_displays_authentic_cover_photo(): void
    {
        $this->seed(ProgrammeSeeder::class);

        $response = $this->get('/programmes');
        $response->assertOk();
        $response->assertSee('front/images/gallery/optimized/spelling-01.webp');
        $response->assertSee('front/images/gallery/optimized/itd-2025-05.webp');

        $detailResponse = $this->get('/programmes/spelling-bee-contests');
        $detailResponse->assertOk();
        $detailResponse->assertSee('front/images/gallery/optimized/spelling-01.webp');
    }

    public function test_famoyinka_memorial_library_programme_renders_with_rich_narrative_and_gallery_cross_link(): void
    {
        $this->seed(ProgrammeSeeder::class);
        $this->seed(GallerySeeder::class);

        $response = $this->get('/programmes/famoyinka-memorial-library');

        $response->assertOk();
        $response->assertSeeText("Famoyinka Memorial Children's Library");
        $response->assertSeeText('Explore Photo Gallery');
        $response->assertSee(url('/gallery?album=famoyinka-library'));
        $response->assertSee('front/images/gallery/optimized/famoyinka-01.webp');
    }

    public function test_home_and_about_pages_feature_famoyinka_memorial_library(): void
    {
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSeeText("Famoyinka Memorial Children's Library");
        $homeResponse->assertSeeText('Free Book Loans');
        $homeResponse->assertSeeText('Creative Arts & Crafts');
        $homeResponse->assertSeeText('Leadership & Teamwork');
        $homeResponse->assertSeeText('Educational Excursions');
        $homeResponse->assertSee(url('/programmes/famoyinka-memorial-library'));

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertOk();
        $aboutResponse->assertSeeText("Famoyinka Memorial Children's Library");
        $aboutResponse->assertSee(url('/programmes/famoyinka-memorial-library'));
    }
}
