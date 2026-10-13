<?php

namespace Tests\Feature;

use App\Models\Partner;
use Database\Seeders\PartnerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerPublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PartnerSeeder::class);
    }

    public function test_home_page_displays_active_partners_with_webp_logos(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Our Valued Partners');
        $response->assertSeeText('Collaborating for greater impact');
        $response->assertSee('front/images/ascend.webp');
        $response->assertSee('front/images/SAA.webp');
        $response->assertSee('front/images/helpmeetaneed.webp');
        $response->assertSee('front/images/intinternational.webp');

        // External link check
        $response->assertSee('https://ascendedufoundation.org/');
    }

    public function test_about_page_displays_active_partners_grid(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSeeText('Partners in Progress');
        $response->assertSee('front/images/ascend.webp');
        $response->assertSee('front/images/SAA.webp');
        $response->assertSee('front/images/helpmeetaneed.webp');
        $response->assertSee('front/images/intinternational.webp');
    }

    public function test_inactive_partners_are_hidden_from_home_and_about_pages(): void
    {
        Partner::where('name', 'ASCEND Education Foundation')->update(['is_active' => false]);

        $homeResponse = $this->get('/');
        $homeResponse->assertDontSee('front/images/ascend.webp');
        $homeResponse->assertSee('front/images/SAA.webp');

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertDontSee('front/images/ascend.webp');
        $aboutResponse->assertSee('front/images/SAA.webp');
    }

    public function test_empty_partners_renders_cleanly_without_hardcoded_duplicates(): void
    {
        Partner::query()->delete();

        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertDontSee('front/images/ascend.jpg');
        $homeResponse->assertDontSee('front/images/SAA.jpg');

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertOk();
        $aboutResponse->assertSeeText('Interested in partnering with Dzuels Educational Foundation?');
        $aboutResponse->assertDontSee('front/images/ascend.jpg');
    }
}
