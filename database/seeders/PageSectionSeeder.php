<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            // 1. Home Page
            [
                'page_key' => 'home',
                'section_key' => 'hero',
                'headline' => 'Addressing poverty through quality education and skills acquisition',
                'subheadline' => 'Dzuels Educational Foundation',
                'body_text' => 'Empowering indigent Nigerian children, youth, and educators with free digital literacy, school support, leadership mentoring, and community welfare.',
                'image_path' => 'front/images/home_1.webp',
                'button_text' => 'Donate Now',
                'button_url' => '/donate',
                'sort_order' => 1,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'who_we_are',
                'headline' => 'Dedicated to Transforming Lives and Communities Through Education',
                'subheadline' => 'Who We Are',
                'body_text' => "Dzuels Educational Foundation is a non-profit organisation dedicated to addressing poverty by providing quality education and skills acquisition for indigent children and youth across Nigeria. Founded in 2015, we bridge educational inequality by equipping underserved communities with modern digital skills, literacy support, and vital infrastructure.\n\nOur initiatives ensure that children from low-income backgrounds have the tools, guidance, and environments required to thrive in a rapidly changing world.",
                'image_path' => 'front/images/about_1.webp',
                'button_text' => 'Learn More About Us',
                'button_url' => '/about',
                'sort_order' => 2,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'vision_mission',
                'headline' => 'Our Core Purpose & Guiding Principles',
                'subheadline' => 'Vision & Mission',
                'body_text' => "Vision: An equitable society where every child, irrespective of socio-economic background, has access to quality education, modern digital literacy, and the opportunity to achieve their highest human potential.\n\nMission: To dismantle educational barriers for indigent youth in Nigeria by deploying digital classrooms, scholarship support, literacy outreach, and mentorship that spark lifelong empowerment.",
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 3,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'values',
                'headline' => 'Our DRNICER Core Values',
                'subheadline' => 'Guiding Principles',
                'body_text' => 'Dynamism, Resilience, Novelty, Integrity, Compassion, Excellence, Responsibility — these principles define every classroom we build and partnership we form.',
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 4,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'causes',
                'headline' => 'Programs That Bridge the Opportunity Divide',
                'subheadline' => 'Our Impact Causes',
                'body_text' => 'From digital hubs in Ijero-Ekiti to annual literacy contests, our causes are designed to uplift communities from the ground up.',
                'image_path' => 'front/images/cause.webp',
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 5,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'featured_project',
                'headline' => 'Ijero-Ekiti Digital Infrastructure and Literacy Initiative',
                'subheadline' => 'Flagship Project',
                'body_text' => 'Establishing modern computer laboratories and solar-powered learning facilities in public schools across Ijero-Ekiti, equipping over 165 students with practical coding and computer skills.',
                'image_path' => 'front/images/dzuelsCollage.webp',
                'button_text' => 'Explore Our Programmes',
                'button_url' => '/programmes',
                'sort_order' => 6,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'cta',
                'headline' => "Join Us in Shaping the Future of Nigeria's Youth",
                'subheadline' => 'Take Action Today',
                'body_text' => 'Whether through corporate sponsorship, volunteer mentoring, or financial donations, your partnership empowers the next generation.',
                'image_path' => null,
                'button_text' => 'Get Involved Today',
                'button_url' => '/get-involved',
                'sort_order' => 7,
            ],

            // 2. About Page
            [
                'page_key' => 'about',
                'section_key' => 'hero',
                'headline' => 'A Decade of Impact, Education & Hope',
                'subheadline' => 'About Dzuels Educational Foundation',
                'body_text' => 'Founded in 2015, Dzuels Educational Foundation has grown from a local literacy initiative in Ekiti State into an impactful non-profit organisation driving digital empowerment and community welfare across Nigeria.',
                'image_path' => 'front/images/gallery/optimized/general-01.webp',
                'button_text' => 'Support Our Mission',
                'button_url' => '/donate',
                'sort_order' => 1,
            ],
            [
                'page_key' => 'about',
                'section_key' => 'story',
                'headline' => 'Our 10-Year Journey of Community Empowerment',
                'subheadline' => 'Our Story',
                'body_text' => "What started as an urgent response to learning resource shortages in rural schools has evolved into a comprehensive educational movement.\n\nOver the past ten years, we have trained hundreds of young Nigerians in modern computational thinking, distributed essential learning supplies, and renovated learning spaces in public secondary and primary schools.",
                'image_path' => 'front/images/about_1.webp',
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 2,
            ],
            [
                'page_key' => 'about',
                'section_key' => 'approach',
                'headline' => 'Grassroots Engagement & Sustainable Innovation',
                'subheadline' => 'Our Approach',
                'body_text' => 'We partner directly with schools, community leaders, and local governments to identify critical gaps and deploy sustainable, high-impact educational solutions tailored to underserved youth.',
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 3,
            ],
            [
                'page_key' => 'about',
                'section_key' => 'leadership',
                'headline' => 'Board of Trustees, Management & Advisory Board',
                'subheadline' => 'Foundation Governance',
                'body_text' => 'Guided by dedicated educators, technologists, and community leaders committed to absolute transparency, integrity, and social stewardship.',
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 4,
            ],

            // 3. Programmes Page
            [
                'page_key' => 'programmes',
                'section_key' => 'hero',
                'headline' => 'Sustainable Interventions Empowering Students and Schools',
                'subheadline' => 'Our Programmes',
                'body_text' => 'Explore our targeted initiatives focused on digital literacy, STEM training, academic competitions, and teacher development across Ekiti State.',
                'image_path' => 'front/images/gallery/optimized/itd-2025-05.webp',
                'button_text' => 'Partner on a Programme',
                'button_url' => '/get-involved',
                'sort_order' => 1,
            ],
            [
                'page_key' => 'programmes',
                'section_key' => 'overview',
                'headline' => 'Targeted Initiatives for Lasting Community Impact',
                'subheadline' => 'Empowering Next-Gen Leaders',
                'body_text' => 'Every programme is designed with measurable outcomes: improving student academic performance, fostering digital confidence, and preparing youth for future careers.',
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 2,
            ],

            // 4. Contact Page
            [
                'page_key' => 'contact',
                'section_key' => 'hero',
                'headline' => 'We Would Love to Hear From You',
                'subheadline' => 'Get in Touch',
                'body_text' => 'Have questions about our initiatives, want to partner, or need more information? Reach out to our dedicated team.',
                'image_path' => 'front/images/gallery/optimized/general-05.webp',
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 1,
            ],
            [
                'page_key' => 'contact',
                'section_key' => 'office',
                'headline' => 'Our Head Office & Learning Centers',
                'subheadline' => 'Visit Our Center',
                'body_text' => 'AAoJ Memorial Learning Center, Ijero-Ekiti, Ekiti State, Nigeria. Open Monday through Friday, 8:00 AM to 5:00 PM.',
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 2,
            ],

            // 5. Donate Page
            [
                'page_key' => 'donate',
                'section_key' => 'hero',
                'headline' => "Every Contribution Changes a Child's Future",
                'subheadline' => 'Invest in Education',
                'body_text' => '100% of your generous gifts directly fund classroom infrastructure, student learning kits, and free digital training for indigent youth.',
                'image_path' => 'front/images/gallery/optimized/reading-01.webp',
                'button_text' => 'Give Online via Flutterwave',
                'button_url' => 'https://flutterwave.com/pay/j0byxgjs0nma',
                'sort_order' => 1,
            ],
            [
                'page_key' => 'donate',
                'section_key' => 'where_your_gift_goes',
                'headline' => 'Transparent Allocation of Every Naira',
                'subheadline' => 'Where Your Gift Goes',
                'body_text' => 'We ensure absolute financial accountability. Donations support digital lab computers, solar power backups, school supplies, scholarship grants, and teacher enablement.',
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 2,
            ],

            // 6. Get Involved Page
            [
                'page_key' => 'get_involved',
                'section_key' => 'hero',
                'headline' => 'Lend Your Skills, Time, or Resources to Transform Education',
                'subheadline' => 'Get Involved',
                'body_text' => 'Join our network of passionate volunteers and visionary corporate partners making an indelible mark on Nigerian education.',
                'image_path' => 'front/images/gallery/optimized/general-02.webp',
                'button_text' => 'Apply to Volunteer',
                'button_url' => '#volunteer-form',
                'sort_order' => 1,
            ],
            [
                'page_key' => 'get_involved',
                'section_key' => 'intro',
                'headline' => 'Volunteer or Partner With Dzuels',
                'subheadline' => 'Create Lasting Change',
                'body_text' => 'Whether you want to mentor young coders during Ijero Tech Day or sponsor a digital laboratory in a public school, there is a place for you in our mission.',
                'image_path' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 2,
            ],
        ];

        foreach ($sections as $section) {
            PageSection::updateOrCreate(
                [
                    'page_key' => $section['page_key'],
                    'section_key' => $section['section_key'],
                ],
                $section
            );
        }
    }
}
