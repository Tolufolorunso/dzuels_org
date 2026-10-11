<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Programme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_renders_successfully_with_published_articles(): void
    {
        $programme = Programme::create([
            'title' => 'Digital Literacy Project',
            'slug' => 'digital-literacy-project',
            'summary' => 'Comprehensive digital literacy for students.',
            'body' => 'Full details of digital literacy.',
            'is_active' => true,
        ]);

        Post::create([
            'title' => '165 Students Complete Digital Skills Training',
            'slug' => '165-students-complete-digital-skills-training',
            'excerpt' => 'Graduation ceremony held in Ijero-Ekiti for tech participants.',
            'body' => 'Full story about the milestone achieved in digital literacy.',
            'programme_id' => $programme->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        Post::create([
            'title' => 'Literacy Outreach Reaches 15 Secondary Schools',
            'slug' => 'literacy-outreach-reaches-15-secondary-schools',
            'excerpt' => 'Book donations and reading clinics hosted across Ekiti.',
            'body' => 'Full report on the school literacy initiative.',
            'status' => 'published',
            'published_at' => now()->subHours(2),
        ]);

        Post::create([
            'title' => 'Internal Draft Story',
            'slug' => 'internal-draft-story',
            'excerpt' => 'This story should not be visible publicly.',
            'body' => 'Draft story body content.',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get('/news');

        $response->assertOk();
        $response->assertSeeText('News & Community');
        $response->assertSeeText('Stories');
        $response->assertSeeText('165 Students Complete Digital Skills Training');
        $response->assertSeeText('Literacy Outreach Reaches 15 Secondary Schools');
        $response->assertSeeText('Digital Literacy Project');
        $response->assertDontSeeText('Internal Draft Story');
        $response->assertSee('href="'.url('/news/165-students-complete-digital-skills-training').'"', false);
    }

    public function test_news_detail_page_renders_successfully_with_share_links_and_related_posts(): void
    {
        $programme = Programme::create([
            'title' => 'Tech Day Initiative',
            'slug' => 'tech-day-initiative',
            'summary' => 'Annual innovation showcase.',
            'body' => 'Full narrative on tech day.',
            'is_active' => true,
        ]);

        $post = Post::create([
            'title' => 'Dzuels Foundation Announces Ijero Tech Day 2026',
            'slug' => 'dzuels-foundation-announces-ijero-tech-day-2026',
            'excerpt' => 'Celebrating a decade of youth empowerment and digital literacy in Ekiti.',
            'body' => "This is the primary narrative of the upcoming milestone.\n\nOver 500 delegates and students are expected to attend.",
            'programme_id' => $programme->id,
            'status' => 'published',
            'published_at' => now()->subDays(3),
            'seo_title' => 'Ijero Tech Day 2026 Announcement | Dzuels',
            'seo_description' => 'Official press release for Ijero Tech Day 2026.',
        ]);

        $relatedPost = Post::create([
            'title' => 'Highlights from Last Year Tech Day',
            'slug' => 'highlights-from-last-year-tech-day',
            'excerpt' => 'Looking back at 2025 student hackathons.',
            'body' => 'Archival highlights and photos.',
            'programme_id' => $programme->id,
            'status' => 'published',
            'published_at' => now()->subDays(10),
        ]);

        $response = $this->get('/news/dzuels-foundation-announces-ijero-tech-day-2026');

        $response->assertOk();
        $response->assertSeeText('Dzuels Foundation Announces Ijero Tech Day 2026');
        $response->assertSeeText('This is the primary narrative of the upcoming milestone.');
        $response->assertSeeText('Over 500 delegates and students are expected to attend.');
        $response->assertSeeText('Tech Day Initiative');
        $response->assertSeeText('Highlights from Last Year Tech Day');
        $response->assertSee('Ijero Tech Day 2026 Announcement | Dzuels');

        // Verify social share presence
        $response->assertSee('api.whatsapp.com/send', false);
        $response->assertSee('twitter.com/intent/tweet', false);
        $response->assertSee('facebook.com/sharer/sharer.php', false);
        $response->assertSee('navigator.clipboard.writeText', false);
    }

    public function test_draft_post_returns_404_on_public_detail_page(): void
    {
        Post::create([
            'title' => 'Unpublished Story',
            'slug' => 'unpublished-story',
            'excerpt' => 'Draft excerpt.',
            'body' => 'Draft body.',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get('/news/unpublished-story');

        $response->assertNotFound();
    }

    public function test_future_scheduled_post_returns_404_on_public_detail_page(): void
    {
        Post::create([
            'title' => 'Future Scheduled Post',
            'slug' => 'future-scheduled-post',
            'excerpt' => 'Scheduled for tomorrow.',
            'body' => 'Body of scheduled post.',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get('/news/future-scheduled-post');

        $response->assertNotFound();
    }

    public function test_non_existent_slug_returns_404(): void
    {
        $response = $this->get('/news/does-not-exist');

        $response->assertNotFound();
    }

    public function test_news_index_handles_empty_state_gracefully(): void
    {
        $response = $this->get('/news');

        $response->assertOk();
        $response->assertSeeText('No Articles Published Yet');
    }

    public function test_homepage_keeps_news_section_dormant(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            Post::create([
                'title' => "News Story Number {$i}",
                'slug' => "news-story-number-{$i}",
                'excerpt' => "Summary of story {$i}",
                'body' => "Body of story {$i}",
                'status' => 'published',
                'published_at' => now()->subDays(5 - $i), // Story 4 is newest
            ]);
        }

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSeeText('News & Impact Stories');
        $response->assertDontSeeText('News Story Number 4');
    }
}
