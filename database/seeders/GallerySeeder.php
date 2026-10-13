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
                'title' => 'Famoyinka Memorial Library',
                'slug' => 'famoyinka-library',
                'kicker' => "Children's Literacy & Creative Hub",
                'description' => "Action moments from our community children's library in Ijero-Ekiti: story circles, book loans, creative crafts, transformational leadership games, and educational excursions.",
                'cover_image' => 'front/images/gallery/optimized/famoyinka-01.webp',
                'sort_order' => 1,
                'is_famoyinka' => true,
            ],
            [
                'title' => 'ITD 2025',
                'slug' => 'itd-2025',
                'kicker' => 'Innovation and Tech Day',
                'description' => 'Highlights from ITD 2025: keynote talks, hands-on coding demonstrations, award moments, and student builders.',
                'cover_image' => 'front/images/gallery/optimized/itd-2025-05.webp',
                'sort_order' => 2,
                'prefix' => 'itd-2025',
                'caption_label' => 'ITD 2025 Highlight',
                'count' => 15,
                'year' => '2025',
            ],
            [
                'title' => 'ITD 2024',
                'slug' => 'itd-2024',
                'kicker' => 'Inaugural Tech Day',
                'description' => 'Highlights from the maiden ITD 2024 edition: bringing technology education and digital skills to Ijero-Ekiti.',
                'cover_image' => 'front/images/gallery/optimized/itd-2024-01.webp',
                'sort_order' => 3,
                'prefix' => 'itd-2024',
                'caption_label' => 'ITD 2024 Highlight',
                'count' => 12,
                'year' => '2024',
            ],
            [
                'title' => 'Reading Competition',
                'slug' => 'reading-competition',
                'kicker' => 'Literacy Outreach',
                'description' => 'Encouraging early literacy, book reading culture, comprehension, and vocabulary among young students.',
                'cover_image' => 'front/images/gallery/optimized/reading-01.webp',
                'sort_order' => 4,
                'prefix' => 'reading',
                'caption_label' => 'Reading Competition Moment',
                'count' => 8, // Pruned down from 25 to eliminate repetitive shots
                'year' => '2024',
            ],
            [
                'title' => 'Spelling Bee',
                'slug' => 'spelling-bee',
                'kicker' => 'Academic Excellence',
                'description' => 'Spelling Bee tournaments across secondary schools in Ekiti State promoting language mastery and confidence.',
                'cover_image' => 'front/images/gallery/optimized/spelling-01.webp',
                'sort_order' => 5,
                'prefix' => 'spelling',
                'caption_label' => 'Spelling Bee Contestant',
                'count' => 10,
                'year' => '2024',
            ],
            [
                'title' => 'General',
                'slug' => 'general',
                'kicker' => 'Everyday Impact',
                'description' => 'Mentorship sessions, classroom interventions, community outreach, and equipment distribution moments.',
                'cover_image' => 'front/images/gallery/optimized/general-01.webp',
                'sort_order' => 6,
                'prefix' => 'general',
                'caption_label' => 'Community Impact Moment',
                'count' => 12,
                'year' => '2024',
            ],
        ];

        $famoyinkaPhotos = [
            [
                'image' => 'famoyinka-01.webp',
                'caption' => "Children's Book Lending & Fiction Shelf",
                'alt' => "Student selecting books at Famoyinka Memorial Children's Library",
                'year' => '2024',
            ],
            [
                'image' => 'famoyinka-02.webp',
                'caption' => 'Transformational Leadership & Teamwork Games',
                'alt' => 'Outdoor youth leadership and character-building games',
                'year' => '2024',
            ],
            [
                'image' => 'famoyinka-03.webp',
                'caption' => 'Creative Arts, Painting & Crafts Workshop',
                'alt' => 'Children painting and creating paper crafts in the library',
                'year' => '2025',
            ],
            [
                'image' => 'famoyinka-04.webp',
                'caption' => 'Ikogosi Warm Springs Educational Nature Excursion',
                'alt' => 'Pupils exploring nature boardwalk at Ikogosi Warm Springs',
                'year' => '2025',
            ],
            [
                'image' => 'famoyinka-05.webp',
                'caption' => 'Attentive Early Childhood Storybook Reading Circle',
                'alt' => 'Young pupil reading attentively in classroom circle',
                'year' => '2024',
            ],
            [
                'image' => 'famoyinka-06.webp',
                'caption' => 'Digital Literacy & Weekend Study Workstations',
                'alt' => 'Students working on computers in the study lab',
                'year' => '2025',
            ],
            [
                'image' => 'famoyinka-07.webp',
                'caption' => 'Smiling Beneficiaries in Community Peer Learning',
                'alt' => 'Smiling schoolchildren participating in library club',
                'year' => '2024',
            ],
            [
                'image' => 'famoyinka-08.webp',
                'caption' => 'Holistic Education Support, Arts & Values Pillars',
                'alt' => 'Foundation education support and values curriculum',
                'year' => '2024',
            ],
        ];

        foreach ($albumsData as $data) {
            $isFamoyinka = $data['is_famoyinka'] ?? false;
            $prefix = $data['prefix'] ?? null;
            $captionLabel = $data['caption_label'] ?? null;
            $count = $data['count'] ?? 8;
            $defaultYear = $data['year'] ?? '2024';

            unset($data['is_famoyinka'], $data['prefix'], $data['caption_label'], $data['count'], $data['year']);

            $album = GalleryAlbum::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            if ($isFamoyinka) {
                foreach ($famoyinkaPhotos as $idx => $photoData) {
                    $sortOrder = $idx + 1;
                    $imagePath = "front/images/gallery/optimized/{$photoData['image']}";

                    GalleryPhoto::updateOrCreate(
                        [
                            'gallery_album_id' => $album->id,
                            'image_path' => $imagePath,
                        ],
                        [
                            'caption' => $photoData['caption'],
                            'alt_text' => $photoData['alt'],
                            'year' => $photoData['year'],
                            'sort_order' => $sortOrder,
                        ]
                    );
                }
            } else {
                // Prune repetitive excess photos above target count
                GalleryPhoto::where('gallery_album_id', $album->id)
                    ->where('sort_order', '>', $count)
                    ->delete();

                for ($i = 1; $i <= $count; $i++) {
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
                            'year' => $defaultYear,
                            'sort_order' => $i,
                        ]
                    );
                }
            }
        }
    }
}
