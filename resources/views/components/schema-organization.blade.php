@php
    $socials = array_values(array_filter([
        \App\Models\Setting::get('social_facebook', 'https://facebook.com/dzuelsfoundation'),
        \App\Models\Setting::get('social_twitter', 'https://twitter.com/dzuelsfoundation'),
        \App\Models\Setting::get('social_linkedin', 'https://linkedin.com/company/dzuelsfoundation'),
        \App\Models\Setting::get('social_instagram', 'https://instagram.com/dzuelsfoundation'),
    ]));

    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => 'Dzuels Educational Foundation',
        'alternateName' => 'Dzuels Foundation',
        'url' => url('/'),
        'logo' => \App\Models\Setting::getLogoUrl(),
        'description' => 'Dzuels Educational Foundation is a Nigerian non-profit organisation addressing poverty through quality education, youth literacy interventions, and digital skills acquisition in Ekiti State and beyond.',
        'foundingDate' => '2015-06-22',
        'taxID' => \App\Models\Setting::get('registration_number', 'CAC/IT/NO 123456'),
        'knowsAbout' => [
            'Youth Literacy Interventions',
            'Digital Skills Acquisition',
            'Teacher Technology Training',
            'Ijero-Ekiti Community Development',
            'Innovation & Tech Day',
            'Children Library Services',
        ],
        'email' => \App\Models\Setting::get('contact_email', 'info@dzuelsfoundation.org'),
        'telephone' => \App\Models\Setting::getPrimaryPhone(),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => \App\Models\Setting::get('contact_address', 'AAoJ Memorial Learning Center, Ijero-Ekiti'),
            'addressLocality' => 'Ijero-Ekiti',
            'addressRegion' => 'Ekiti State',
            'addressCountry' => 'NG',
        ],
        'sameAs' => $socials,
        'contactPoint' => [
            [
                '@type' => 'ContactPoint',
                'telephone' => \App\Models\Setting::getPrimaryPhone(),
                'contactType' => 'General Inquiries',
                'areaServed' => 'NG',
                'availableLanguage' => ['English', 'Yoruba'],
            ],
        ],
    ];

    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Dzuels Educational Foundation',
        'alternateName' => ['Dzuels Foundation', 'DZF'],
        'url' => url('/'),
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
