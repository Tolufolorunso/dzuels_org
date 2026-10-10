<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            // Trustees
            [
                'name' => 'Anna John-Yakubu',
                'role' => 'Trustee',
                'group' => 'trustee',
                'field' => null,
                'bio' => null,
                'photo' => 'front/images/anna.jpg',
                'linkedin_url' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Kate Ojiogo',
                'role' => 'Trustee',
                'group' => 'trustee',
                'field' => null,
                'bio' => null,
                'photo' => 'front/images/kate.jpg',
                'linkedin_url' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Olawale Dada',
                'role' => 'Trustee',
                'group' => 'trustee',
                'field' => null,
                'bio' => null,
                'photo' => 'front/images/olawale.jpg',
                'linkedin_url' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Bolaji Agbelusi (Founder)',
                'role' => 'Trustee',
                'group' => 'trustee',
                'field' => null,
                'bio' => null,
                'photo' => 'front/images/bolaji.jpg',
                'linkedin_url' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],

            // Management
            [
                'name' => 'Mrs. Fadekemi Humphries',
                'role' => 'Country Manager (USA)',
                'group' => 'management',
                'field' => null,
                'bio' => null,
                'photo' => 'front/images/mgt_fadekemi.webp',
                'linkedin_url' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Ms. Michelle Sarumi',
                'role' => 'Country Manager (Nigeria)',
                'group' => 'management',
                'field' => null,
                'bio' => null,
                'photo' => 'front/images/mgt_michelle.png',
                'linkedin_url' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],

            // Advisory Board
            [
                'name' => 'Mrs. Fola Oloyede',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'Education Services',
                'bio' => 'Education Consultant | Founder, Just4Teachers Organization',
                'photo' => null,
                'linkedin_url' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Solape Adesuyi',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'Corporate Legal Services',
                'bio' => 'Principal Partner, Solape Adesuyi Associates',
                'photo' => null,
                'linkedin_url' => 'http://www.linkedin.com/in/solape-adesuyi-4103b44a',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Mrs. Toyin Idowu',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'Education Services',
                'bio' => 'Education Administrator',
                'photo' => null,
                'linkedin_url' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Eyitayo Abejide',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'Medical Services',
                'bio' => 'Public Health Practitioner',
                'photo' => null,
                'linkedin_url' => 'http://www.linkedin.com/in/eyitayo-abejide-69867538',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Olukemi Alegi',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'Medical Services',
                'bio' => 'Consultant Oncologist',
                'photo' => null,
                'linkedin_url' => 'https://www.linkedin.com/in/olukemi-alegi-5610641a6',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Mr. Odunayo Ayegbusi',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'Project Management-Energy Solutions',
                'bio' => 'Nuclear Engineer',
                'photo' => null,
                'linkedin_url' => 'https://www.linkedin.com/in/ayo-ayegbusi-18725349',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Ms. Kofoworola Tubi',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'Architectural Services',
                'bio' => 'Architect',
                'photo' => null,
                'linkedin_url' => 'https://www.linkedin.com/in/kofoworola-tubi',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Mr. David Jayeola',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'ICT Services',
                'bio' => 'Software Engineer',
                'photo' => null,
                'linkedin_url' => 'https://www.linkedin.com/in/david-opeyemi-jaiyeola-20401a11/',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Mr. Tolu Talabi',
                'role' => 'Advisor',
                'group' => 'advisor',
                'field' => 'ICT Services',
                'bio' => 'Software Engineer, System Support & Integration',
                'photo' => null,
                'linkedin_url' => null,
                'sort_order' => 9,
                'is_active' => true,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name'], 'group' => $member['group']],
                $member
            );
        }
    }
}
