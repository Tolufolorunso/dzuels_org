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
        'logo' => asset('front/images/logo.png'),
        'description' => 'Dzuels Educational Foundation is a Nigerian non-profit organisation addressing poverty through quality education, youth literacy interventions, and digital skills acquisition in Ekiti State and beyond.',
        'email' => \App\Models\Setting::get('contact_email', 'info@dzuelsfoundation.org'),
        'telephone' => \App\Models\Setting::get('contact_phone_1', '+234 803 388 9576'),
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
                'telephone' => \App\Models\Setting::get('contact_phone_1', '+234 803 388 9576'),
                'contactType' => 'General Inquiries',
                'areaServed' => 'NG',
                'availableLanguage' => ['English', 'Yoruba'],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
