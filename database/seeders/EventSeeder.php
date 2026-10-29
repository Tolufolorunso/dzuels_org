<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSponsor;
use App\Models\MediaItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flagshipDate = Carbon::create(2026, 10, 3, 8, 0, 0);

        // Fetch first authentic photo paths if available
        $photo2024 = MediaItem::where('year', 2024)->where('source_type', 'gallery')->first()?->file_path
            ?? 'uploads/media/techday/2024/1793278498_UanlkjhT6Q8M.webp';
        $photo2025 = MediaItem::where('year', 2025)->where('source_type', 'gallery')->first()?->file_path
            ?? 'uploads/media/techday/2025/1793278500_XUSj1fYPJy42.webp';

        // 1. ITD 2026 (Flagship Upcoming)
        $itd2026 = Event::updateOrCreate(
            ['slug' => 'ijero-tech-day-2026'],
            [
                'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
                'summary' => 'Empowering 300 children with practical AI tools across 10 rotating batches of 30, inside interactive labs and garden booths, plus 22 cohort graduate certificates.',
                'description' => "Ijero Tech-Day 2026 (ITD 2026) is the flagship community technology festival organized by Dzuels Educational Foundation under the theme \"Learning Today, Creating Tomorrow\". Held at the AAoJ Memorial Learning Center in Ijero-Ekiti, this high-impact gathering brings together 300 selected primary and secondary school students, dedicated educators, and community mentors for a transformative full-day digital literacy and AI immersion experience.\n\nStudents participate in synchronized 10-batch rotations of 30 participants each across two experiential learning zones:\n1. Inside Practical Lab: 20-minute intensive hands-on sessions introducing practical Artificial Intelligence tools, prompt thinking, and digital creativity on modern computing equipment.\n2. Garden Innovation Pavilion: Outdoor creative stations featuring an AI Image Creation Booth, interactive Tech Quizzes, student digital logic challenges, and prize giveaways.\n\nThe event also features the milestone Cohort 2026 Certificate Presentations, honoring 22 outstanding students who completed our rigorous digital literacy training, alongside community educational keynotes and educator recognition.",
                'event_date' => $flagshipDate,
                'end_date' => $flagshipDate->copy()->setTime(17, 0, 0),
                'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)',
                'theme' => 'Learning Today, Creating Tomorrow',
                'objective' => 'Empowering children with AI tools to learn smarter, think creatively, and build the future.',
                'banner_image' => 'front/images/events/itd-2026-backdrop.webp',
                'schedule' => [
                    [
                        'time' => '08:00 AM - 09:00 AM',
                        'title' => 'Accreditation, Batch Allocation & Welcome Assembly',
                        'speaker' => 'Dzuels Foundation Coordination Team',
                    ],
                    [
                        'time' => '09:00 AM - 09:30 AM',
                        'title' => 'Opening Ceremony: "Learning Today, Creating Tomorrow"',
                        'speaker' => 'Board of Trustees & Community Leaders',
                    ],
                    [
                        'time' => '09:30 AM - 01:00 PM',
                        'title' => 'Morning AI Lab & Garden Rotation Sessions (Batches 1 – 5)',
                        'speaker' => 'Tech Instructors & Mentors',
                    ],
                    [
                        'time' => '01:00 PM - 02:00 PM',
                        'title' => 'Lunch Break, Networking & Image Creation Showcase',
                        'speaker' => 'Students & Guest Exhibitors',
                    ],
                    [
                        'time' => '02:00 PM - 04:30 PM',
                        'title' => 'Afternoon AI Lab & Garden Rotation Sessions (Batches 6 – 10)',
                        'speaker' => 'Tech Instructors & Mentors',
                    ],
                    [
                        'time' => '04:30 PM - 05:00 PM',
                        'title' => 'Cohort Graduation: Presentation of 22 Certificates & Grand Finale',
                        'speaker' => 'Foundation Leadership & Dignitaries',
                    ],
                ],
                'is_registration_open' => false,
                'is_active' => true,
                'sort_order' => 1,
                'seo_title' => 'Ijero Tech-Day 2026 (ITD 2026) | Dzuels Educational Foundation',
                'seo_description' => 'Join 300 students and educators for Ijero Tech-Day 2026 on Saturday, October 3, 2026. Theme: Learning Today, Creating Tomorrow. Objective: Empowering children with AI tools to learn smarter, think creatively, and build the future.',
            ]
        );

        $sponsors = [
            [
                'name' => 'Solape Adesuyi Associates',
                'logo_path' => 'front/images/SAA.jpg',
                'website_url' => null,
                'tier' => 'lead',
                'sort_order' => 1,
            ],
            [
                'name' => 'Help Meet A Need',
                'logo_path' => 'front/images/helpmeetaneed.png',
                'website_url' => null,
                'tier' => 'partner',
                'sort_order' => 2,
            ],
            [
                'name' => 'Int International',
                'logo_path' => 'front/images/intinternational.png',
                'website_url' => null,
                'tier' => 'supporter',
                'sort_order' => 3,
            ],
        ];

        foreach ($sponsors as $sponsor) {
            EventSponsor::updateOrCreate(
                [
                    'event_id' => $itd2026->id,
                    'name' => $sponsor['name'],
                ],
                array_merge($sponsor, [
                    'event_id' => $itd2026->id,
                    'is_active' => true,
                ])
            );
        }

        // 2. ITD 2025 (2nd Annual Edition)
        Event::updateOrCreate(
            ['slug' => 'ijero-tech-day-2025'],
            [
                'title' => 'Ijero Tech Day 2025 (2nd Annual Edition)',
                'summary' => 'The second annual flagship gathering celebrating student digital innovation, robotics demos, and creative problem solving across Ijero-Ekiti.',
                'description' => "Ijero Tech Day 2025 brought together over 220 students, teachers, and technology mentors from across Ekiti State for an intensive full-day celebration of innovation and practical STEM skills. Following the success of the 2024 inaugural gathering, the 2025 edition featured expanded coding hackathons, beginner robotics exhibitions, visual design clinics, and keynote addresses by tech professionals.\n\nStudents demonstrated innovative community problem-solving projects, and participating schools received refurbished desktop computers, books, and scholarship recognition for outstanding student innovators.",
                'event_date' => Carbon::create(2025, 10, 25, 9, 0, 0),
                'end_date' => Carbon::create(2025, 10, 25, 16, 30, 0),
                'venue' => 'AAoJ Memorial Learning Center & Ijero Civic Hall, Ijero-Ekiti',
                'theme' => 'Empowering Rural Youth Through Innovation & Technology',
                'objective' => 'Accelerating digital literacy, robotics awareness, and creative technology problem-solving among rural students.',
                'banner_image' => $photo2025,
                'schedule' => [
                    [
                        'time' => '09:00 AM - 10:00 AM',
                        'title' => 'Opening Assembly & Keynote: Innovation in Rural Education',
                        'speaker' => 'Foundation Leadership & Guests',
                    ],
                    [
                        'time' => '10:00 AM - 01:00 PM',
                        'title' => 'Interactive Coding, Robotics & Digital Media Workshops',
                        'speaker' => 'Tech Mentors & Facilitators',
                    ],
                    [
                        'time' => '01:00 PM - 02:00 PM',
                        'title' => 'Lunch Break & Student Innovation Exhibition',
                        'speaker' => 'Student Innovators',
                    ],
                    [
                        'time' => '02:00 PM - 04:00 PM',
                        'title' => 'Youth Hackathon Demos & Pitch Presentations',
                        'speaker' => 'Student Teams & Judges',
                    ],
                    [
                        'time' => '04:00 PM - 04:30 PM',
                        'title' => 'Awards, School Equipment Presentation & Closing',
                        'speaker' => 'Dignitaries',
                    ],
                ],
                'is_registration_open' => false,
                'is_active' => true,
                'sort_order' => 2,
                'seo_title' => 'Ijero Tech Day 2025 | Dzuels Educational Foundation',
                'seo_description' => 'Highlights and student exhibitions from the 2nd annual Ijero Tech Day held on October 25, 2025 in Ijero-Ekiti.',
            ]
        );

        // 3. ITD 2024 (Inaugural Edition)
        Event::updateOrCreate(
            ['slug' => 'ijero-tech-day-2024'],
            [
                'title' => 'Ijero Tech Day 2024 (Inaugural Edition)',
                'summary' => 'The inaugural gathering that connected over 150 local students to digital literacy tools, robotics kits, and mentorship.',
                'description' => "The 2024 edition of Ijero Tech Day set a new benchmark for grassroots technology engagement in Ekiti State. Facilitated in collaboration with Information Technology Seed Development Initiative (SeedDev), the gathering welcomed 150+ students from 15 secondary schools across Ijero-Ekiti for practical digital workshops and competitions.\n\nHighlights included interactive coding basics, typing mastery awards, and the official handover of modern computing equipment to participating schools.",
                'event_date' => Carbon::create(2024, 11, 15, 9, 0, 0),
                'end_date' => Carbon::create(2024, 11, 15, 15, 0, 0),
                'venue' => 'Ijero-Ekiti Civic Centre, Ekiti State',
                'theme' => 'Igniting Digital Potential in Ijero-Ekiti',
                'objective' => 'Introducing foundational digital literacy, typing mastery, and computer hardware to rural secondary school students.',
                'banner_image' => $photo2024,
                'schedule' => [
                    [
                        'time' => '09:00 AM - 10:00 AM',
                        'title' => 'Opening Ceremony & Keynote Address',
                        'speaker' => 'Dzuels Team & Education Officials',
                    ],
                    [
                        'time' => '10:00 AM - 12:30 PM',
                        'title' => 'Digital Literacy Workshops & Coding Clinic',
                        'speaker' => 'SeedDev Instructors',
                    ],
                    [
                        'time' => '01:00 PM - 02:30 PM',
                        'title' => 'Student Competitions & Award Presentations',
                        'speaker' => 'Guest Evaluators',
                    ],
                ],
                'is_registration_open' => false,
                'is_active' => true,
                'sort_order' => 3,
                'seo_title' => 'Ijero Tech Day 2024 | Dzuels Educational Foundation',
                'seo_description' => 'Highlights and outcomes from the inaugural Ijero Tech Day 2024 in Ekiti State.',
            ]
        );
    }
}
