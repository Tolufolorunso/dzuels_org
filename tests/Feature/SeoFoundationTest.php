<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\Programme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_unique_titles_descriptions_and_canonical_urls(): void
    {
        $routes = [
            '/' => 'Quality Education and Digital Skills in Nigeria',
            '/about' => 'About Us | Dzuels Educational Foundation',
            '/programmes' => 'Programmes | Dzuels Educational Foundation',
            '/events' => 'Events &amp; Gatherings | Dzuels Educational Foundation',
            '/gallery' => 'Gallery &amp; Visual Archive | Dzuels Educational Foundation',
            '/donate' => 'Donate &amp; Support Quality Education | Dzuels Educational Foundation',
            '/get-involved' => 'Get Involved: Volunteer or Partner | Dzuels Educational Foundation',
            '/contact' => 'Contact Us | Get in Touch with Dzuels Educational Foundation',
        ];

        $seenTitles = [];

        foreach ($routes as $uri => $expectedTitleSnippet) {
            $response = $this->get($uri);
            $response->assertOk();

            // Assert title tag is present and matches expectation
            $response->assertSee($expectedTitleSnippet, false);

            // Extract title to check uniqueness
            preg_match('/<title>(.*?)<\/title>/s', $response->getContent(), $titleMatches);
            $this->assertNotEmpty($titleMatches, "Missing <title> tag on {$uri}");
            $pageTitle = html_entity_decode(trim($titleMatches[1]));

            $this->assertNotContains($pageTitle, $seenTitles, "Duplicate <title> found on {$uri}: {$pageTitle}");
            $seenTitles[] = $pageTitle;

            // Assert meta description is present
            $response->assertSee('<meta name="description"', false);

            // Assert canonical link matches URI
            $expectedCanonical = url($uri);
            $response->assertSee('<link rel="canonical" href="'.$expectedCanonical.'">', false);
        }
    }

    public function test_public_pages_render_open_graph_and_twitter_tags(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        $content = $response->getContent();

        // Open Graph
        $this->assertStringContainsString('<meta property="og:site_name" content="Dzuels Educational Foundation">', $content);
        $this->assertStringContainsString('<meta property="og:type" content="website">', $content);
        $this->assertStringContainsString('<meta property="og:locale" content="en_NG">', $content);
        $this->assertStringContainsString('<meta property="og:title"', $content);
        $this->assertStringContainsString('<meta property="og:description"', $content);
        $this->assertStringContainsString('<meta property="og:image"', $content);
        $this->assertStringContainsString('<meta property="og:url" content="'.url('/').'">', $content);

        // Twitter Card
        $this->assertStringContainsString('<meta name="twitter:card" content="summary_large_image">', $content);
        $this->assertStringContainsString('<meta name="twitter:title"', $content);
        $this->assertStringContainsString('<meta name="twitter:description"', $content);
        $this->assertStringContainsString('<meta name="twitter:image"', $content);

        // Meta robots
        $this->assertStringContainsString('<meta name="robots" content="index, follow">', $content);
    }

    public function test_organization_json_ld_schema_is_rendered_validly(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        $content = $response->getContent();

        // Find all JSON-LD script blocks
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1], 'No JSON-LD structured data found in page');

        $foundOrganization = false;
        foreach ($matches[1] as $jsonString) {
            $data = json_decode(trim($jsonString), true);
            if ($data && isset($data['@type']) && $data['@type'] === 'EducationalOrganization') {
                $foundOrganization = true;
                $this->assertEquals('https://schema.org', $data['@context']);
                $this->assertEquals('Dzuels Educational Foundation', $data['name']);
                $this->assertEquals(url('/'), $data['url']);
                $this->assertArrayHasKey('logo', $data);
                $this->assertArrayHasKey('address', $data);
                $this->assertEquals('Ijero-Ekiti', $data['address']['addressLocality']);
                $this->assertEquals('Ekiti State', $data['address']['addressRegion']);
                $this->assertEquals('NG', $data['address']['addressCountry']);
                $this->assertArrayHasKey('contactPoint', $data);
                $this->assertArrayHasKey('sameAs', $data);
                break;
            }
        }

        $this->assertTrue($foundOrganization, 'EducationalOrganization schema was not found or failed JSON parse');
    }

    public function test_breadcrumbs_json_ld_schema_is_rendered_on_subpages(): void
    {
        $response = $this->get('/about');
        $response->assertOk();

        $content = $response->getContent();
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);

        $foundBreadcrumbs = false;
        foreach ($matches[1] as $jsonString) {
            $data = json_decode(trim($jsonString), true);
            if ($data && isset($data['@type']) && $data['@type'] === 'BreadcrumbList') {
                $foundBreadcrumbs = true;
                $this->assertEquals('https://schema.org', $data['@context']);
                $this->assertIsArray($data['itemListElement']);
                $this->assertCount(2, $data['itemListElement']);
                $this->assertEquals('Home', $data['itemListElement'][0]['name']);
                $this->assertEquals(url('/'), $data['itemListElement'][0]['item']);
                $this->assertEquals('About Us', $data['itemListElement'][1]['name']);
                $this->assertEquals(url('/about'), $data['itemListElement'][1]['item']);
                break;
            }
        }

        $this->assertTrue($foundBreadcrumbs, 'BreadcrumbList schema was not found on /about');
    }

    public function test_dynamic_programme_page_renders_model_seo_and_breadcrumbs(): void
    {
        $programme = Programme::create([
            'title' => 'Digital Literacy Initiative',
            'slug' => 'digital-literacy-initiative',
            'summary' => 'Empowering youth with digital skills.',
            'body' => '<p>Comprehensive hands-on digital skills training.</p>',
            'seo_title' => 'Digital Literacy Programme in Ekiti | Dzuels Foundation',
            'seo_description' => 'Comprehensive digital literacy and coding bootcamp for secondary school students in Ekiti State.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/programmes/'.$programme->slug);
        $response->assertOk();

        $content = $response->getContent();
        $this->assertStringContainsString('Digital Literacy Programme in Ekiti | Dzuels Foundation', $content);
        $this->assertStringContainsString('Comprehensive digital literacy and coding bootcamp for secondary school students', $content);
        $this->assertStringContainsString('<link rel="canonical" href="'.route('programmes.show', $programme->slug).'">', $content);
        $this->assertStringContainsString('<meta property="og:type" content="article">', $content);

        // Verify breadcrumbs JSON-LD includes 3 items
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $foundBreadcrumbs = false;
        foreach ($matches[1] as $jsonString) {
            $data = json_decode(trim($jsonString), true);
            if ($data && isset($data['@type']) && $data['@type'] === 'BreadcrumbList') {
                $foundBreadcrumbs = true;
                $this->assertCount(3, $data['itemListElement']);
                $this->assertEquals('Digital Literacy Initiative', $data['itemListElement'][2]['name']);
                break;
            }
        }

        $this->assertTrue($foundBreadcrumbs, 'Breadcrumbs not found on dynamic programme show view');
    }

    public function test_dynamic_event_page_renders_model_seo_and_breadcrumbs(): void
    {
        $event = Event::create([
            'title' => 'Ijero Tech Day 2026',
            'slug' => 'ijero-tech-day-2026',
            'summary' => 'Annual flagship technology gathering.',
            'description' => '<p>10th anniversary celebration and coding showcase.</p>',
            'event_date' => now()->addMonths(6),
            'location' => 'AAoJ Memorial Learning Center, Ijero-Ekiti',
            'seo_title' => 'ITD 2026: Learning Today, Creating Tomorrow | Dzuels Foundation',
            'seo_description' => 'Join 300 participants at Ijero Tech Day 2026 celebrating 10 years of educational transformation.',
            'is_active' => true,
        ]);

        $response = $this->get('/events/'.$event->slug);
        $response->assertOk();

        $content = $response->getContent();
        $this->assertStringContainsString('ITD 2026: Learning Today, Creating Tomorrow | Dzuels Foundation', $content);
        $this->assertStringContainsString('Join 300 participants at Ijero Tech Day 2026 celebrating 10 years', $content);
        $this->assertStringContainsString('<link rel="canonical" href="'.route('events.show', $event->slug).'">', $content);
        $this->assertStringContainsString('<meta property="og:type" content="article">', $content);

        // Verify breadcrumbs
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $foundBreadcrumbs = false;
        foreach ($matches[1] as $jsonString) {
            $data = json_decode(trim($jsonString), true);
            if ($data && isset($data['@type']) && $data['@type'] === 'BreadcrumbList') {
                $foundBreadcrumbs = true;
                $this->assertCount(3, $data['itemListElement']);
                $this->assertEquals('Ijero Tech Day 2026', $data['itemListElement'][2]['name']);
                break;
            }
        }

        $this->assertTrue($foundBreadcrumbs, 'Breadcrumbs not found on dynamic event show view');
    }

    public function test_sitemap_xml_returns_valid_xml_with_all_active_routes(): void
    {
        $activeProg = Programme::create([
            'title' => 'Active Programme',
            'slug' => 'active-programme',
            'summary' => 'Active summary',
            'body' => '<p>Active</p>',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $inactiveProg = Programme::create([
            'title' => 'Draft Programme',
            'slug' => 'draft-programme',
            'summary' => 'Draft summary',
            'body' => '<p>Draft</p>',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $activeEvent = Event::create([
            'title' => 'Active Event',
            'slug' => 'active-event',
            'summary' => 'Active event summary',
            'description' => '<p>Active event</p>',
            'event_date' => now()->addDays(10),
            'location' => 'Ijero-Ekiti',
            'is_active' => true,
        ]);

        $inactiveEvent = Event::create([
            'title' => 'Draft Event',
            'slug' => 'draft-event',
            'summary' => 'Draft event summary',
            'description' => '<p>Draft event</p>',
            'event_date' => now()->addDays(20),
            'location' => 'Ijero-Ekiti',
            'is_active' => false,
        ]);

        $activeAlbum = GalleryAlbum::create([
            'title' => 'Active Album',
            'slug' => 'active-album',
            'description' => 'Active album description',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $inactiveAlbum = GalleryAlbum::create([
            'title' => 'Draft Album',
            'slug' => 'draft-album',
            'description' => 'Draft album description',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $this->assertStringContainsString('xml', $response->headers->get('Content-Type'));

        $content = $response->getContent();

        // Validate XML parsing
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'Sitemap XML output is not well-formed XML');
        $this->assertEquals('urlset', $xml->getName());

        // Check static core URLs
        $this->assertStringContainsString(url('/'), $content);
        $this->assertStringContainsString(url('/about'), $content);
        $this->assertStringContainsString(url('/programmes'), $content);
        $this->assertStringContainsString(url('/events'), $content);
        $this->assertStringContainsString(url('/gallery'), $content);
        $this->assertStringContainsString(url('/donate'), $content);
        $this->assertStringContainsString(url('/get-involved'), $content);
        $this->assertStringContainsString(url('/contact'), $content);

        // Check active dynamic items
        $this->assertStringContainsString(route('programmes.show', $activeProg->slug), $content);
        $this->assertStringContainsString(route('events.show', $activeEvent->slug), $content);
        $this->assertStringContainsString(route('gallery.album', $activeAlbum->slug), $content);

        // Assert inactive items and admin URLs are strictly omitted
        $this->assertStringNotContainsString(route('programmes.show', $inactiveProg->slug), $content);
        $this->assertStringNotContainsString(route('events.show', $inactiveEvent->slug), $content);
        $this->assertStringNotContainsString(route('gallery.album', $inactiveAlbum->slug), $content);
        $this->assertStringNotContainsString('/admin', $content);
    }

    public function test_robots_txt_returns_plain_text_with_admin_disallow_and_sitemap(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertOk();
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Allow: /', $content);
        $this->assertStringContainsString('Disallow: /admin', $content);
        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Sitemap: '.url('/sitemap.xml'), $content);
    }

    public function test_legacy_partner_url_301_permanently_redirects_to_get_involved(): void
    {
        $response = $this->get('/partner');
        $response->assertStatus(301);
        $response->assertRedirect('/get-involved');
    }
}
