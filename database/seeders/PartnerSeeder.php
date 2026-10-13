<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'ASCEND Education Foundation',
                'logo' => 'front/images/ascend.webp',
                'website_url' => 'https://ascendedufoundation.org/',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Solape Adesuyi Associates',
                'logo' => 'front/images/SAA.webp',
                'website_url' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Help Meet A Need',
                'logo' => 'front/images/helpmeetaneed.webp',
                'website_url' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Int International',
                'logo' => 'front/images/intinternational.webp',
                'website_url' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                $partner
            );
        }
    }
}
