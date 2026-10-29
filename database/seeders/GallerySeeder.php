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
                'is_active' => true,
                'is_public' => true,
                'is_famoyinka' => true,
            ],
            [
                'title' => 'Essay Competition',
                'slug' => 'essay-competition',
                'kicker' => 'Academic Literacy & Critical Thinking',
                'description' => 'Annual student essay competition fostering critical thinking, literature appreciation, and writing skills among youth in Ijero-Ekiti and Ekiti State.',
                'cover_image' => 'front/images/gallery/optimized/essay-competition-01.webp',
                'sort_order' => 2,
                'is_active' => true,
                'is_public' => true,
                'prefix' => 'essay-competition',
                'caption_label' => 'Essay Competition Moment',
                'count' => 16,
                'year' => '2026',
            ],
            [
                'title' => 'Girls in Tech & Leadership',
                'slug' => 'girls-day',
                'kicker' => 'Empowerment & Digital Inclusion',
                'description' => 'Dedicated workshops inspiring young female students to embrace computer science, coding, creative digital design, and career leadership.',
                'cover_image' => 'front/images/gallery/optimized/girls-day-01.webp',
                'sort_order' => 3,
                'is_active' => true,
                'is_public' => true,
                'prefix' => 'girls-day',
                'caption_label' => 'Girls in Tech Session',
                'count' => 16,
                'year' => '2026',
            ],
            [
                'title' => 'Ijero Tech Day (ITD)',
                'slug' => 'itd',
                'kicker' => 'Flagship Yearly Festival',
                'description' => 'Flagship yearly celebration uniting innovators, students, tech enthusiasts, educators, and community leaders for digital empowerment in Ijero-Ekiti.',
                'cover_image' => 'front/images/gallery/optimized/itd-01.webp',
                'sort_order' => 4,
                'is_active' => true,
                'is_public' => true,
                'prefix' => 'itd',
                'caption_label' => 'ITD Festival Highlight',
                'count' => 16,
                'year' => '2025',
            ],
            [
                'title' => 'Reading & Children’s Library',
                'slug' => 'reading-competition',
                'kicker' => 'Early Childhood Literacy',
                'description' => 'Encouraging early literacy, book reading culture, comprehension, and vocabulary among young students across Ekiti schools.',
                'cover_image' => 'front/images/gallery/optimized/reading-01.webp',
                'sort_order' => 5,
                'is_active' => true,
                'is_public' => true,
                'prefix' => 'reading',
                'caption_label' => 'Reading Competition Moment',
                'count' => 8,
                'year' => '2024',
            ],
            [
                'title' => 'Spelling Bee Championship',
                'slug' => 'spelling-bee',
                'kicker' => 'Academic Precision & Stage Confidence',
                'description' => 'High-energy inter-school spelling bee celebrating linguistic talent, spelling precision, stage confidence, and academic camaraderie.',
                'cover_image' => 'front/images/gallery/optimized/spelling-bee-01.webp',
                'sort_order' => 6,
                'is_active' => true,
                'is_public' => true,
                'prefix' => 'spelling-bee',
                'caption_label' => 'Spelling Bee Contestant',
                'count' => 16,
                'year' => '2026',
            ],
            [
                'title' => 'WDADA Community Outreach',
                'slug' => 'wdada-community-outreach',
                'kicker' => 'Grassroots Relief & Welfare',
                'description' => 'Grassroots community development, nutritional relief, and education welfare reaching indigent children and families across Ekiti communities.',
                'cover_image' => 'front/images/gallery/optimized/wdada-01.webp',
                'sort_order' => 7,
                'is_active' => true,
                'is_public' => true,
                'prefix' => 'wdada',
                'caption_label' => 'Community Relief Outreach',
                'count' => 16,
                'year' => '2026',
            ],
            [
                'title' => 'General Community & Cohorts',
                'slug' => 'general',
                'kicker' => 'Youth Mentorship & Excursions',
                'description' => 'Cohort digital training graduations, student excursions to Ikogosi Warm Springs, charity love sales, and volunteer outreach across Ekiti State.',
                'cover_image' => 'front/images/gallery/optimized/general-01.webp',
                'sort_order' => 8,
                'is_active' => true,
                'is_public' => true,
                'prefix' => 'general',
                'caption_label' => 'Community Impact Moment',
                'count' => 16,
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
                // Prune old excess photos above target count
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
