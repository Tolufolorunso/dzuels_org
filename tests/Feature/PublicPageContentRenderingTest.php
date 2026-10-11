<?php

namespace Tests\Feature;

use App\Models\PageSection;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPageContentRenderingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PageSectionSeeder::class);
    }

    public function test_homepage_renders_seeded_cms_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Addressing poverty through quality education');
        $response->assertSee('Dedicated to Transforming Lives');
    }

    public function test_updating_cms_section_immediately_updates_homepage(): void
    {
        $section = PageSection::where('page_key', 'home')->where('section_key', 'hero')->first();
        $this->assertNotNull($section);

        $section->update([
            'headline' => 'Custom Headline For Digital Future',
            'body_text' => 'Updated custom body copy for tests.',
            'button_text' => 'Custom Giving Button',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Custom Headline For Digital Future');
        $response->assertSee('Updated custom body copy for tests.');
        $response->assertSee('Custom Giving Button');
    }

    public function test_about_page_renders_dynamic_cms_content(): void
    {
        $section = PageSection::where('page_key', 'about')->where('section_key', 'hero')->first();
        $section->update([
            'headline' => 'A Special Decade of Transformation',
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('A Special Decade of Transformation');
    }

    public function test_all_public_cms_pages_render_without_errors(): void
    {
        $pages = [
            '/' => 'Dzuels Educational Foundation',
            '/about' => 'About Us',
            '/programmes' => 'Programmes',
            '/contact' => 'Get in Touch',
            '/donate' => 'Donate',
            '/get-involved' => 'Get Involved',
        ];

        foreach ($pages as $url => $expected) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee($expected);
        }
    }

    public function test_page_renders_gracefully_when_sections_are_missing(): void
    {
        PageSection::query()->delete();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Dzuels Educational Foundation');
    }
}
