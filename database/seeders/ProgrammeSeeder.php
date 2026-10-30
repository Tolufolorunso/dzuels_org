<?php

namespace Database\Seeders;

use App\Models\Programme;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programmes = [
            [
                'title' => "Famoyinka Memorial Children's Library",
                'slug' => 'famoyinka-memorial-library',
                'summary' => "A community-centered children's reading sanctuary and creative learning hub in Ijero-Ekiti providing free book lending, weekend study circles, creative arts and crafts, transformational leadership games, and educational excursions.",
                'body' => "The Famoyinka Memorial Children's Library is Dzuels Educational Foundation's dedicated literacy and youth enrichment sanctuary in Ijero-Ekiti. Established in loving memory of a lifelong community champion for child welfare and learning, the library provides a safe, welcoming, and vibrant environment where young learners from indigent backgrounds can discover the joy of books, cultivate imaginative thinking, and build foundational leadership qualities.\n\nOur extensive collection spans African literature, classic folklore, beginner phonics, science and mathematics workbooks, reference encyclopedias, and engaging fiction. Beyond daily book loans, the library hosts structured weekend reading circles, interactive storytelling sessions, creative arts and crafts workshops, and transformational leadership board challenges that instill discipline, teamwork, and fearless curiosity.\n\nIn addition, our library beneficiaries participate in fully sponsored educational excursions — such as nature and environmental science discovery field trips to Ikogosi Warm Springs — expanding their horizons beyond classroom walls. Every service, book loan, and mentorship activity is offered completely free of charge to all children in Ijero-Ekiti and surrounding communities.",
                'cover_image' => 'front/images/gallery/optimized/famoyinka-01.webp',
                'partner_name' => 'Famoyinka Family & Community Literacy Patrons',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'seo_title' => "Famoyinka Memorial Children's Library | Dzuels Educational Foundation",
                'seo_description' => "Community children's library providing free book borrowing, creative crafts, leadership games, and literacy circles in Ijero-Ekiti.",
            ],
            [
                'title' => 'Digital Infrastructure & Literacy in Ijero-Ekiti',
                'slug' => 'ijero-digital-literacy',
                'summary' => 'Free literacy interventions, digital skills training for 30 teachers and over 500 students, and a well-equipped computer laboratory and resourceful library at Dzuels Foundation in Ijero-Ekiti.',
                'body' => "The Digital Infrastructure and Literacy project in Ijero-Ekiti is Dzuels Educational Foundation's flagship community technology intervention. Operated in close technical collaboration with the Information Technology Seed Development Initiative (SeedDev), this project addresses the stark digital divide experienced by youth and educators in rural Ekiti State.\n\nThrough this multi-year initiative, we established a well-equipped computer laboratory and a resourceful library at Dzuels Foundation, providing children with access to practical computer training, coding skills, reading materials, and a supportive learning environment. To date, over 500 students have benefited from these learning opportunities, while 30 teachers have been thoroughly trained in digital pedagogy and computer literacy.\n\nOur trainers deliver structured weekend and holiday bootcamps covering foundational computer operations, typing skills, digital research, visual productivity suites, and basic coding paradigms for both pupils and teachers.\n\nBy empowering educators with digital lesson planning competencies and introducing young learners to modern technology early, this programme directly unlocks future career pathways in software engineering, digital entrepreneurship, and academic excellence.",
                'cover_image' => 'front/images/dzuelsCollage.webp',
                'partner_name' => 'Information Technology Seed Development Initiative (SeedDev)',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
                'seo_title' => 'Digital Infrastructure & Literacy in Ijero-Ekiti | Dzuels Foundation',
                'seo_description' => 'Establishing a well-equipped computer laboratory and library at Dzuels Foundation, upskilling 30 teachers, and training over 500 students in practical coding and digital literacy in Ijero-Ekiti.',
            ],
            [
                'title' => 'Transformational leadership (TRANSCOMM)',
                'slug' => 'transformational-leadership-transcomm',
                'summary' => 'A values-driven mentorship and character development initiative empowering emerging youth leaders with ethical mindset, public speaking, critical thinking, and community impact.',
                'body' => "Transformational leadership (TRANSCOMM) is Dzuels Educational Foundation's flagship youth leadership and character development initiative. Rooted in our core DRNICER principles—Discipline, Responsibility, No-Excuse, Integrity, Compassion, Excellence, and Respect—TRANSCOMM prepares young Nigerians to lead with purpose, moral conviction, and actionable vision in their schools and communities.\n\nThrough interactive leadership circles, public speaking workshops, ethical decision-making case studies, and community impact projects, participants learn to move beyond passive observation and become proactive problem-solvers. The curriculum integrates emotional intelligence, teamwork exercises, civic responsibility, and visionary goal-setting, inspiring youth to transform adversity into leadership capability.\n\nTRANSCOMM beneficiaries actively spearhead peer mentorship groups, organize community cleanups, and champion literacy and digital skills advocacy among their peers, embodying true transformative leadership from the grassroots up.",
                'cover_image' => 'front/images/about_1.webp',
                'partner_name' => "Priscilla's Upper Room & Leadership Mentors",
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 6,
                'seo_title' => 'Transformational Leadership (TRANSCOMM) | Dzuels Educational Foundation',
                'seo_description' => 'Values-driven mentorship, ethical leadership training, and mindset transformation for emerging community youth leaders in Ekiti State.',
            ],
            [
                'title' => 'Ijero Tech Day (ITD)',
                'slug' => 'innovation-and-tech-day',
                'summary' => 'Our annual flagship technology festival celebrating youth innovation, hands-on digital exhibitions, robotics demos, and keynote mentorship from tech industry leaders.',
                'body' => "Ijero Tech Day (ITD) is our marquee annual showcase where young beneficiaries from across Ekiti State demonstrate their technological inventions, creative software projects, and digital literacy milestones before parents, community leaders, and visiting industry executives.\n\nEach edition features high-energy student hackathons, coding exhibitions, digital art showcases, and inspirational keynote panels hosted by software engineers, healthcare leaders, and academic consultants from Nigeria and the diaspora. Outstanding teams and student innovators receive educational scholarships, laptops, and direct mentorship placement to foster their continued growth.\n\nITD serves as both a celebration of community resilience and a launchpad for the next generation of African technology creators.",
                'cover_image' => 'front/images/gallery/optimized/itd-2025-05.webp',
                'partner_name' => 'SeedDev & Corporate Tech Allies',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
                'seo_title' => 'Ijero Tech Day (ITD) | Dzuels Educational Foundation',
                'seo_description' => 'Annual flagship technology exposition celebrating youth innovation, coding exhibitions, and creative problem-solving among Ekiti youth.',
            ],
            [
                'title' => 'Reading Competition',
                'slug' => 'reading-competition',
                'summary' => 'A grassroots literacy campaign designed to rekindle a passionate reading culture, vocabulary mastery, and textual comprehension among primary and junior secondary students.',
                'body' => "Reading is the foundational cornerstone of all intellectual development. The Dzuels Reading Competition is an annual literacy advocacy programme that challenges primary and junior secondary pupils across underserved schools to dive deep into literature, African folklore, and contemporary educational texts.\n\nParticipating students receive curated book packs and participate in inter-school reading challenges that assess comprehension, phonetic pronunciation, textual summary, and expressive articulation. In tandem with the competition, our volunteer educators facilitate mobile reading clinics and donate class libraries to partnering schools to sustain reading habits throughout the academic calendar.\n\nBy transforming reading into an exciting, rewarded sport, we build lifelong learners who lead with intellect and curiosity.",
                'cover_image' => 'front/images/about_1.webp',
                'partner_name' => 'ASCEND Education Foundation',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
                'seo_title' => 'Annual Reading Competition | Dzuels Educational Foundation',
                'seo_description' => 'State-wide literacy intervention revitalizing reading culture and comprehension excellence among primary school pupils in Ekiti State.',
            ],
            [
                'title' => 'Spelling Bee Contests',
                'slug' => 'spelling-bee-contests',
                'summary' => 'A high-impact inter-school spelling championship enhancing linguistic confidence, memory retention, public poise, and academic camaraderie for young pupils.',
                'body' => "The Dzuels Spelling Bee Contest brings together hundreds of eager pupils from diverse public and community schools to test their mastery of the English lexicon in a supportive, spirited tournament.\n\nBeyond teaching orthography and etymology, the Spelling Bee builds crucial non-cognitive skills: composure under pressure, mental agility, sportsmanship, and fearless public speaking. Finalists compete on stage before cheering peers, families, and education inspectors, competing for coveted school fee grants, complete textbook suites, and gold medals.\n\nThis initiative proves that with structured practice and compassionate encouragement, every child can achieve extraordinary linguistic and academic heights.",
                'cover_image' => 'front/images/gallery/optimized/spelling-01.webp',
                'partner_name' => null,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5,
                'seo_title' => 'Spelling Bee Contests | Dzuels Educational Foundation',
                'seo_description' => 'Inter-school competition promoting vocabulary acquisition, public speaking poise, and cognitive development for young learners.',
            ],
        ];

        foreach ($programmes as $data) {
            Programme::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
