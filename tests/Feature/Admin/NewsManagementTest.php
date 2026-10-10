<?php

namespace Tests\Feature\Admin;

use App\Models\Post;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class NewsManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/news');
        $response->assertRedirect('/admin/login');

        $response = $this->get('/admin/news/create');
        $response->assertRedirect('/admin/login');

        $response = $this->post('/admin/news', []);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_news_index(): void
    {
        Post::create([
            'title' => 'Digital Skills Launch in Ekiti',
            'slug' => 'digital-skills-launch-in-ekiti',
            'excerpt' => 'New computer laboratories equipped.',
            'body' => 'Full narrative on digital skills launch.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/news');

        $response->assertOk();
        $response->assertSeeText('News & Stories');
        $response->assertSeeText('Digital Skills Launch in Ekiti');
        $response->assertSeeText('/news/digital-skills-launch-in-ekiti');
    }

    public function test_admin_can_create_news_article_with_cover_image(): void
    {
        $programme = Programme::create([
            'title' => 'Reading Initiatives',
            'slug' => 'reading-initiatives',
            'summary' => 'Encouraging reading culture.',
            'body' => 'Details of reading initiatives.',
            'is_active' => true,
        ]);

        $image = UploadedFile::fake()->image('news_cover.jpg', 800, 500);

        $response = $this->actingAs($this->admin)->post('/admin/news', [
            'title' => 'Literacy Day 2026 Celebrated Across 15 Schools',
            'slug' => 'literacy-day-2026-celebrated-across-15-schools',
            'excerpt' => 'Over 500 books distributed to eager readers.',
            'body' => 'Full article covering student performances and book distribution.',
            'programme_id' => $programme->id,
            'status' => 'published',
            'published_at' => now()->format('Y-m-d\TH:i'),
            'cover_image' => $image,
            'seo_title' => 'Literacy Day 2026 | Dzuels News',
            'seo_description' => 'Book donations and reading clinics in Ekiti State.',
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success', 'News article created successfully.');

        $this->assertDatabaseHas('posts', [
            'title' => 'Literacy Day 2026 Celebrated Across 15 Schools',
            'slug' => 'literacy-day-2026-celebrated-across-15-schools',
            'programme_id' => $programme->id,
            'status' => 'published',
        ]);

        $createdPost = Post::where('slug', 'literacy-day-2026-celebrated-across-15-schools')->first();
        $this->assertNotNull($createdPost);
        $this->assertNotNull($createdPost->cover_image);

        if ($createdPost->cover_image && file_exists(public_path($createdPost->cover_image))) {
            @unlink(public_path($createdPost->cover_image));
        }
    }

    public function test_admin_can_edit_and_update_news_article(): void
    {
        $post = Post::create([
            'title' => 'Initial Story Title',
            'slug' => 'initial-story-title',
            'excerpt' => 'Initial excerpt.',
            'body' => 'Initial body text.',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.edit', $post));
        $response->assertOk();
        $response->assertSee('Initial Story Title');

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.news.update', $post), [
            'title' => 'Updated Story Title',
            'slug' => 'initial-story-title',
            'excerpt' => 'Updated excerpt text.',
            'body' => 'Updated full article content.',
            'status' => 'published',
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $updateResponse->assertRedirect(route('admin.news.index'));
        $updateResponse->assertSessionHas('success', 'News article updated successfully.');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Story Title',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_toggle_publish_status(): void
    {
        $post = Post::create([
            'title' => 'Toggle Status Article',
            'slug' => 'toggle-status-article',
            'body' => 'Body text for toggle status test.',
            'status' => 'draft',
            'published_at' => null,
        ]);

        // Toggle to published
        $response = $this->actingAs($this->admin)->patch(route('admin.news.toggle', $post));
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Post published successfully.');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => 'published',
        ]);

        // Toggle back to draft
        $response = $this->actingAs($this->admin)->patch(route('admin.news.toggle', $post));
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Post set to draft status.');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_delete_news_article(): void
    {
        $post = Post::create([
            'title' => 'Temporary Post To Delete',
            'slug' => 'temporary-post-to-delete',
            'body' => 'This article will be deleted.',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.news.destroy', $post));

        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success', 'News article deleted successfully.');

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/news', [
            'title' => '',
            'body' => '',
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['title', 'body', 'status']);
    }
}
