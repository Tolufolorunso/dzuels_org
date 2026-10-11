<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flagshipDate = Carbon::create(2026, 10, 17, 8, 0, 0);

        Event::updateOrCreate(
            ['slug' => 'ijero-tech-day-2026'],
            [
                'title' => 'Ijero Tech-Day 2026 (ITD 2026)',
                'summary' => 'Empowering 300 children with practical AI tools across 10 rotating batches of 30, inside interactive labs and garden booths, plus 22 cohort graduate certificates.',
                'description' => "Ijero Tech-Day 2026 (ITD 2026) is the flagship community technology festival organized by Dzuels Educational Foundation under the theme \"Learning Today, Creating Tomorrow\". Held at the AAoJ Memorial Learning Center in Ijero-Ekiti, this high-impact gathering brings together 300 selected primary and secondary school students, dedicated educators, and community mentors for a transformative full-day digital literacy and AI immersion experience.\n\nStudents participate in synchronized 10-batch rotations of 30 participants each across two experiential learning zones:\n1. Inside Practical Lab: 20-minute intensive hands-on sessions introducing practical Artificial Intelligence tools, prompt thinking, and digital creativity on modern computing equipment.\n2. Garden Innovation Pavilion: Outdoor creative stations featuring an AI Image Creation Booth, interactive Tech Quizzes, student digital logic challenges, and prize giveaways.\n\nThe event also features the milestone Cohort 2026 Certificate Presentations, honoring 22 outstanding students who completed our rigorous digital literacy training, alongside community educational keynotes and educator recognition.",
                'event_date' => $flagshipDate,
                'end_date' => $flagshipDate->copy()->setTime(17, 0, 0),
                'venue' => 'AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)',
                'theme' => 'Learning Today, Creating Tomorrow',
                'banner_image' => 'front/images/techday.webp',
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
                'is_registration_open' => true,
                'is_active' => true,
                'sort_order' => 1,
                'seo_title' => 'Ijero Tech-Day 2026 (ITD 2026) | Dzuels Educational Foundation',
                'seo_description' => 'Join 300 students and educators for Ijero Tech-Day 2026 on October 17, 2026. Theme: Learning Today, Creating Tomorrow. Register now for free community admission.',
            ]
        );

        Event::updateOrCreate(
            ['slug' => 'ijero-tech-day-2024'],
            [
                'title' => 'Ijero Tech Day 2024 (Inaugural Edition)',
                'summary' => 'The inaugural gathering that connected over 150 local students to digital literacy tools, robotics kits, and mentorship.',
                'description' => "The 2024 edition of Ijero Tech Day set a new benchmark for grassroots technology engagement in Ekiti State. Facilitated in collaboration with Information Technology Seed Development Initiative (SeedDev), the gathering welcomed 150+ students from 15 secondary schools across Ijero-Ekiti for practical digital workshops and competitions.\n\nHighlights included interactive coding basics, typing mastery awards, and the official handover of modern computing equipment to participating schools.",
                'event_date' => Carbon::create(2024, 11, 15, 9, 0, 0),
                'end_date' => Carbon::create(2024, 11, 15, 15, 0, 0),
                'venue' => 'Ijero-Ekiti Civic Centre, Ekiti State',
                'banner_image' => 'front/images/techday.webp',
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
                'sort_order' => 2,
                'seo_title' => 'Ijero Tech Day 2024 | Dzuels Foundation',
                'seo_description' => 'Highlights and outcomes from the inaugural Ijero Tech Day 2024 in Ekiti State.',
            ]
        );
    }
}
