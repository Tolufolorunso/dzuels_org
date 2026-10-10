<?php

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $albumsData = [
            [
                'title' => 'ITD 2025',
                'slug' => 'itd-2025',
                'kicker' => 'Innovation and Tech Day',
                'description' => 'Highlights from ITD 2025: keynote talks, hands-on coding demonstrations, award moments, and student builders.',
                'cover_image' => 'front/images/gallery/optimized/itd-2025-05.webp',
                'sort_order' => 1,
                'prefix' => 'itd-2025',
                'caption_label' => 'ITD 2025 Highlight',
            ],
            [
                'title' => 'ITD 2024',
                'slug' => 'itd-2024',
                'kicker' => 'Inaugural Tech Day',
                'description' => 'Highlights from the maiden ITD 2024 edition: bringing technology education and digital skills to Ijero-Ekiti.',
                'cover_image' => 'front/images/gallery/optimized/itd-2024-01.webp',
                'sort_order' => 2,
                'prefix' => 'itd-2024',
                'caption_label' => 'ITD 2024 Highlight',
            ],
            [
                'title' => 'Reading Competition',
                'slug' => 'reading-competition',
                'kicker' => 'Literacy Outreach',
                'description' => 'Encouraging early literacy, book reading culture, comprehension, and vocabulary among young students.',
                'cover_image' => 'front/images/gallery/optimized/reading-01.webp',
                'sort_order' => 3,
                'prefix' => 'reading',
                'caption_label' => 'Reading Competition Moment',
            ],
            [
                'title' => 'Spelling Bee',
                'slug' => 'spelling-bee',
                'kicker' => 'Academic Excellence',
                'description' => 'Spelling Bee tournaments across secondary schools in Ekiti State promoting language mastery and confidence.',
                'cover_image' => 'front/images/gallery/optimized/spelling-01.webp',
                'sort_order' => 4,
                'prefix' => 'spelling',
                'caption_label' => 'Spelling Bee Contestant',
            ],
            [
                'title' => 'General',
                'slug' => 'general',
                'kicker' => 'Everyday Impact',
                'description' => 'Mentorship sessions, classroom interventions, community outreach, and equipment distribution moments.',
                'cover_image' => 'front/images/gallery/optimized/general-01.webp',
                'sort_order' => 5,
                'prefix' => 'general',
                'caption_label' => 'Community Impact Moment',
            ],
        ];

        foreach ($albumsData as $data) {
            $prefix = $data['prefix'];
            $captionLabel = $data['caption_label'];
            unset($data['prefix'], $data['caption_label']);

            $album = GalleryAlbum::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Seed the 25 pre-optimized photos for each collection
            for ($i = 1; $i <= 25; $i++) {
                $num = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
                $filename = "{$prefix}-{$num}.webp";
                $imagePath = "front/images/gallery/optimized/{$filename}";

                GalleryPhoto::updateOrCreate(
                    [
                        'gallery_album_id' => $album->id,
                        'image_path' => $imagePath,
                    ],
                    [
                        'caption' => "{$captionLabel} #{$i}",
                        'alt_text' => "{$data['title']} - {$captionLabel} #{$i} in Ijero-Ekiti",
                        'sort_order' => $i,
                    ]
                );
            }
        }
    }
}
