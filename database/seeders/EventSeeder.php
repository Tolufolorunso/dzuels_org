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
        $flagshipDate = Carbon::create(2026, 11, 20, 9, 0, 0);

        Event::updateOrCreate(
            ['slug' => 'ijero-tech-day-10th-anniversary'],
            [
                'title' => 'Ijero Tech Day & 10th Anniversary Celebration',
                'summary' => 'Celebrating 10 years of purpose, innovation, and impact in Ijero-Ekiti. Join students, educators, and leaders for hands-on tech demonstrations, awards, and digital milestones.',
                'description' => "Join us as we celebrate 10 years of impact, innovation, and transformation at the Dzuels Foundation Tech-Day. This milestone event showcases our decade-long journey of digital transformation across rural secondary schools in Ekiti State.\n\nAttendees will experience live student coding showcases, robotics demonstrations, educational keynotes, and an inspiring panel on the future of tech careers for rural youth. Whether you are a student, teacher, community leader, or tech professional, Ijero Tech Day offers an unforgettable day of learning, inspiration, and purpose-driven networking.",
                'event_date' => $flagshipDate,
                'end_date' => $flagshipDate->copy()->setTime(16, 0, 0),
                'venue' => 'Dzuels Community Digital Hub, Ijero-Ekiti, Ekiti State, Nigeria',
                'banner_image' => 'front/images/techday.webp',
                'schedule' => [
                    [
                        'time' => '09:00 AM - 10:00 AM',
                        'title' => 'Registration, Accreditation & Welcome Breakfast',
                        'speaker' => 'Dzuels Foundation Host Team',
                    ],
                    [
                        'time' => '10:00 AM - 11:30 AM',
                        'title' => 'Keynote: A Decade of Transforming Rural Education Through Tech',
                        'speaker' => 'Board of Trustees & Special Guests',
                    ],
                    [
                        'time' => '11:45 AM - 01:15 PM',
                        'title' => 'Hands-On Student Tech Demos & Coding Showcase',
                        'speaker' => 'Dzuels ICT Students & SeedDev Mentors',
                    ],
                    [
                        'time' => '01:15 PM - 02:00 PM',
                        'title' => 'Lunch Break & Interactive Tech Exhibition',
                        'speaker' => 'Partner Exhibitors & Student Builders',
                    ],
                    [
                        'time' => '02:00 PM - 03:30 PM',
                        'title' => '10th Anniversary Toast, Awards & Community Networking',
                        'speaker' => 'Leadership Team & Community Leaders',
                    ],
                ],
                'is_registration_open' => true,
                'is_active' => true,
                'sort_order' => 1,
                'seo_title' => 'Ijero Tech Day & 10th Anniversary Celebration | Dzuels Foundation',
                'seo_description' => 'Celebrate 10 years of educational and digital impact at Ijero Tech Day. Register now to attend hands-on tech demos, student presentations, and community keynotes in Ijero-Ekiti.',
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
