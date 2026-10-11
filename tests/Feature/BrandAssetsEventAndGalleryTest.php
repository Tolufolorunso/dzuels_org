<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\GalleryAlbum;
use Database\Seeders\EventSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandAssetsEventAndGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_assets_and_favicons_are_present_and_accessible(): void
    {
        $this->assertFileExists(public_path('favicon.ico'));
        $this->assertFileExists(public_path('favicon-16x16.png'));
        $this->assertFileExists(public_path('favicon-32x32.png'));
        $this->assertFileExists(public_path('apple-touch-icon.png'));
        $this->assertFileExists(public_path('site.webmanifest'));

        $manifestResponse = $this->get('/site.webmanifest');
        $manifestResponse->assertOk();
        $this->assertStringContainsString('Dzuels Educational Foundation', $manifestResponse->getContent());

        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('favicon.ico');
        $homeResponse->assertSee('favicon-32x32.png');
        $homeResponse->assertSee('apple-touch-icon.png');
        $homeResponse->assertSee('site.webmanifest');
    }

    public function test_itd_2026_event_seeding_and_database_attributes(): void
    {
        $this->seed(EventSeeder::class);

        $event = Event::where('slug', 'ijero-tech-day-2026')->with('sponsors')->firstOrFail();

        $this->assertEquals('2026-10-03', $event->event_date->format('Y-m-d'));
        $this->assertEquals('Empowering children with AI tools to learn smarter, think creatively, and build the future.', $event->objective);
        $this->assertEquals('Learning Today, Creating Tomorrow', $event->theme);
        $this->assertStringContainsString('itd-2026-backdrop', $event->banner_image);
        $this->assertFalse((bool) $event->is_registration_open);
        $this->assertCount(3, $event->sponsors);

        $sponsorNames = $event->sponsors->pluck('name')->all();
        $this->assertContains('Solape Adesuyi Associates', $sponsorNames);
        $this->assertContains('Help Meet A Need', $sponsorNames);
        $this->assertContains('Int International', $sponsorNames);
    }

    public function test_itd_2026_show_page_renders_new_attributes_and_removes_registration_form(): void
    {
        $this->seed(EventSeeder::class);

        $response = $this->get('/events/ijero-tech-day-2026');

        $response->assertOk();
        $response->assertSeeText('Empowering children with AI tools to learn smarter, think creatively, and build the future.');
        $response->assertSeeText('Solape Adesuyi Associates');
        $response->assertSeeText('Help Meet A Need');
        $response->assertSeeText('Int International');
        $response->assertSeeText('School Delegations');
        $response->assertSeeText('Cohort 2026 Certification');
        $response->assertSeeText('Staggered Arrival Times');
        $response->assertSeeText('October 3, 2026');

        // Verify registration form is removed for ITD 2026
        $response->assertDontSeeText('Reserve Your Spot');
        $response->assertDontSeeText('Complete Free Registration');

        // Verify zero mentions of SeedDev for 2026
        $this->assertStringNotContainsString('SeedDev', $response->getContent());
    }

    public function test_events_index_showcases_itd_2026_and_separates_past_events(): void
    {
        $this->seed(EventSeeder::class);

        $response = $this->get('/events');

        $response->assertOk();
        $response->assertSeeText('Empowering children with AI tools to learn smarter, think creatively, and build the future.');
        $response->assertSeeText('Historic Milestones & Completed Events');
        $response->assertSeeText('Ijero Tech Day 2024');
    }

    public function test_gallery_renders_photos_and_inline_alpine_app(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'ITD 2025',
            'slug' => 'itd-2025',
            'kicker' => 'Flagship Festival',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $photo = $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/itd-2025-01.webp',
            'caption' => 'Hands-on AI Lab Session',
            'sort_order' => 1,
        ]);

        $response = $this->get('/gallery');

        $response->assertOk();
        $response->assertSee('window.galleryApp = galleryApp', false);
        $response->assertSee('gallery-photo-item', false);
        $response->assertSeeText('Hands-on AI Lab Session');
        $response->assertSeeText('ITD 2025');

        // Confirm scratch file is deleted
        $this->assertFileDoesNotExist(base_path('gallery/hello.html'));
    }

    public function test_gallery_album_detail_renders_album_context(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Reading Competition',
            'slug' => 'reading-competition',
            'kicker' => 'Youth Literacy',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/reading-01.webp',
            'caption' => 'Reading Finalists',
            'sort_order' => 1,
        ]);

        $response = $this->get('/gallery/reading-competition');

        $response->assertOk();
        $response->assertSeeText('Reading Competition');
        $response->assertSeeText('Reading Finalists');
    }
}
