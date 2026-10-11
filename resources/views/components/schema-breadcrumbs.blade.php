@props(['items' => []])

@if(!empty($items))
@php
    $itemList = [];
    $position = 1;
    foreach ($items as $item) {
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $item['name'] ?? '',
            'item' => $item['url'] ?? '',
        ];
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemList,
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
