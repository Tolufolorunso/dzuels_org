<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_gallery_index_returns_200_and_renders_albums(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Innovation Day 2025',
            'slug' => 'itd-2025',
            'kicker' => 'Flagship Festival',
            'description' => 'Highlights from ITD 2025.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/itd-2025-01.webp',
            'caption' => 'Opening Ceremony',
            'sort_order' => 1,
        ]);

        $response = $this->get('/gallery');

        $response->assertOk();
        $response->assertSeeText('Curated Visual Archive');
        $response->assertSeeText('Innovation Day 2025');
        $response->assertSeeText('All Collections');
        $response->assertSee('itd-2025-01.webp');
    }

    public function test_public_gallery_filters_by_collection_query_parameter(): void
    {
        GalleryAlbum::create([
            'title' => 'Reading Competition',
            'slug' => 'reading-competition',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/gallery?collection=reading-competition');

        $response->assertOk();
        $response->assertSeeText('Reading Competition');
    }

    public function test_public_gallery_single_album_route_returns_200_and_shows_album(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Spelling Bee Champions',
            'slug' => 'spelling-bee-champions',
            'kicker' => 'Academic Honor',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $album->photos()->create([
            'image_path' => 'front/images/gallery/optimized/spelling-01.webp',
            'caption' => 'Award Ceremony',
            'sort_order' => 1,
        ]);

        $response = $this->get('/gallery/spelling-bee-champions');

        $response->assertOk();
        $response->assertSeeText('Spelling Bee Champions');
        $response->assertSee('spelling-01.webp');
    }

    public function test_inactive_album_route_returns_404(): void
    {
        GalleryAlbum::create([
            'title' => 'Unpublished Archive',
            'slug' => 'unpublished-archive',
            'is_active' => false,
        ]);

        $response = $this->get('/gallery/unpublished-archive');

        $response->assertNotFound();
    }

    public function test_gallery_renders_empty_state_when_collection_has_no_photos(): void
    {
        $album = GalleryAlbum::create([
            'title' => 'Empty Album',
            'slug' => 'empty-album',
            'is_active' => true,
        ]);

        $response = $this->get('/gallery/empty-album');

        $response->assertOk();
        $response->assertSeeText('No photos found in this collection');
    }
}
