<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class DonateController extends Controller
{
    /**
     * Display the public donation page.
     */
    public function __invoke(): View
    {
        $sections = PageSection::getPageSections('donate');
        $settings = [
            'flutterwave_url' => Setting::get('flutterwave_url') ?: 'https://flutterwave.com/pay/j0byxgjs0nma',
            'bank_name' => Setting::get('bank_name') ?: 'Guaranty Trust Bank (GTBank) Plc.',
            'bank_account_name' => Setting::get('bank_account_name') ?: 'Dzuels Educational Foundation',
            'bank_account_number' => Setting::get('bank_account_number') ?: '0450072850',
            'contact_email' => Setting::get('contact_email') ?: 'info@dzuelsfoundation.org',
            'contact_phone' => Setting::getPrimaryPhone(),
            'registration_number' => Setting::get('registration_number') ?: 'CAC/IT/NO 123456',
        ];

        $diasporaAccounts = [
            'swift_code' => 'GTBINGLA',
            'usd' => '0450072881',
            'gbp' => '0450073060',
            'eur' => '0450072898',
        ];

        $givingTiers = [
            [
                'amount' => '₦5,000',
                'title' => 'Student Learning Kit',
                'description' => 'Supplies essential curriculum textbooks, notebooks, and learning materials for an underprivileged child.',
                'badge' => 'Literacy',
            ],
            [
                'amount' => '₦20,000',
                'title' => 'Tech Bootcamp Sponsorship',
                'description' => 'Sponsors one student through our intensive ICT and coding clinic during Ijero Tech Day.',
                'badge' => 'Innovation',
            ],
            [
                'amount' => '₦50,000',
                'title' => 'School Lab Terminal',
                'description' => 'Helps maintain and equip community school computer workstations with offline learning software.',
                'badge' => 'Infrastructure',
            ],
            [
                'amount' => '₦100,000+',
                'title' => 'Institutional Partner',
                'description' => 'Supports solar inverter installations, school library restorations, and multi-school spelling bees.',
                'badge' => 'Transformative',
            ],
        ];

        $impactPillars = [
            [
                'percentage' => 40,
                'title' => 'Digital Literacy & Computer Labs',
                'description' => 'Refurbishing ICT classrooms, funding computer workstations, internet connectivity, and mentor-led coding programmes.',
                'icon' => '💻',
            ],
            [
                'percentage' => 25,
                'title' => 'Spelling Bees & Literacy Contests',
                'description' => 'Annual reading tournaments, secondary school spelling bee trophies, educational grants, and merit scholarships.',
                'icon' => '🏆',
            ],
            [
                'percentage' => 20,
                'title' => 'Classroom Books & Learning Gear',
                'description' => 'Direct distribution of textbooks, STEM kits, school uniforms, and instructional materials to public schools.',
                'icon' => '📚',
            ],
            [
                'percentage' => 15,
                'title' => 'Field Outreach & Logistics',
                'description' => 'Community volunteer coordination, transparent program monitoring, annual audits, and event operations.',
                'icon' => '🤝',
            ],
        ];

        $faqs = [
            [
                'question' => 'Is Dzuels Educational Foundation officially registered?',
                'answer' => 'Yes. Dzuels Educational Foundation is legally registered with Nigeria\'s Corporate Affairs Commission under registration number CAC/IT/NO 123456 as an Incorporated Trustee.',
            ],
            [
                'question' => 'Are online donations secure?',
                'answer' => 'All online payments are securely processed by Flutterwave using industry-leading 256-bit encryption and PCI-DSS Level 1 compliance. We do not store your credit card or bank credentials.',
            ],
            [
                'question' => 'Can I donate from outside Nigeria (USA, UK, Canada, Europe)?',
                'answer' => 'Yes! You can donate online using any international Mastercard, Visa, or American Express card on our Flutterwave link. Alternatively, you can initiate a direct wire transfer to our GTBank domiciliary accounts (USD, GBP, EUR) using Swift Code GTBINGLA.',
            ],
            [
                'question' => 'How can I get an official receipt for my bank transfer?',
                'answer' => 'After completing a direct bank transfer, please send your transaction reference or receipt screenshot to '.(Setting::get('contact_email') ?: 'info@dzuelsfoundation.org').' or via WhatsApp to '.Setting::getPrimaryPhone().'. Our finance team will issue an official foundation acknowledgment.',
            ],
            [
                'question' => 'Can I donate materials, laptops, or books instead of cash?',
                'answer' => 'Absolutely! We actively welcome in-kind donations of desktop computers, working laptops, tablets, robotics kits, solar inverters, and educational literature. Please contact our team to coordinate shipping or collection in Ekiti or Lagos.',
            ],
        ];

        return view('donate.index', compact('sections', 'settings', 'diasporaAccounts', 'givingTiers', 'impactPillars', 'faqs'));
    }
}
