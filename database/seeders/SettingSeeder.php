<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // Brand & General Identity
            [
                'key' => 'site_logo',
                'value' => 'front/images/logo.png',
                'type' => 'string',
                'group' => 'general',
            ],

            // Verified Impact Statistics
            [
                'key' => 'stat_ict_students',
                'value' => '165',
                'type' => 'integer',
                'group' => 'impact',
            ],
            [
                'key' => 'stat_literacy_students',
                'value' => '591',
                'type' => 'integer',
                'group' => 'impact',
            ],
            [
                'key' => 'stat_schools_supported',
                'value' => '15',
                'type' => 'integer',
                'group' => 'impact',
            ],
            [
                'key' => 'stat_feed_mode',
                'value' => 'manual',
                'type' => 'string',
                'group' => 'impact',
            ],
            [
                'key' => 'stat_api_endpoint',
                'value' => null,
                'type' => 'string',
                'group' => 'impact',
            ],

            // Contact Information & Multi-Line Hotlines
            [
                'key' => 'contact_phone_1',
                'value' => '+234 904 249 3883',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone_2',
                'value' => '+234 810 000 0000',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone_3',
                'value' => null,
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone_4',
                'value' => null,
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone_5',
                'value' => null,
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@dzuelsfoundation.org',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => '12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_working_hours',
                'value' => 'Mon – Fri: 8:00 AM – 5:00 PM',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_timezone',
                'value' => 'GMT +1',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_working_days_note',
                'value' => 'Closed on public holidays',
                'type' => 'string',
                'group' => 'contact',
            ],

            // Giving & Banking Details
            [
                'key' => 'flutterwave_url',
                'value' => 'https://flutterwave.com/pay/j0byxgjs0nma',
                'type' => 'string',
                'group' => 'banking',
            ],
            [
                'key' => 'givebutter_url',
                'value' => 'https://givebutter.com/dzuels-foundation',
                'type' => 'string',
                'group' => 'banking',
            ],
            [
                'key' => 'bank_name',
                'value' => 'Guaranty Trust Bank (GTBank) Plc.',
                'type' => 'string',
                'group' => 'banking',
            ],
            [
                'key' => 'bank_account_name',
                'value' => 'Dzuels Educational Foundation',
                'type' => 'string',
                'group' => 'banking',
            ],
            [
                'key' => 'bank_account_number',
                'value' => '0450072850',
                'type' => 'string',
                'group' => 'banking',
            ],

            // Social & Registration Details
            [
                'key' => 'registration_number',
                'value' => 'CAC/IT/NO 123456',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com',
                'type' => 'string',
                'group' => 'social',
            ],
        ];

        foreach ($defaults as $setting) {
            Setting::set(
                $setting['key'],
                $setting['value'],
                $setting['type'],
                $setting['group']
            );
        }
    }
}
