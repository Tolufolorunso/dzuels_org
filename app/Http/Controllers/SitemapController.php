<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\Programme;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap for search crawlers.
     */
    public function __invoke(): Response
    {
        $urls = [];

        // 1. Static Core Public Pages
        $staticPages = [
            ['loc' => url('/'), 'changefreq' => 'weekly', 'priority' => 1.0],
            ['loc' => url('/about'), 'changefreq' => 'monthly', 'priority' => 0.8],
            ['loc' => url('/programmes'), 'changefreq' => 'weekly', 'priority' => 0.9],
            ['loc' => url('/events'), 'changefreq' => 'weekly', 'priority' => 0.9],
            ['loc' => url('/gallery'), 'changefreq' => 'weekly', 'priority' => 0.8],
            ['loc' => url('/donate'), 'changefreq' => 'monthly', 'priority' => 0.9],
            ['loc' => url('/get-involved'), 'changefreq' => 'monthly', 'priority' => 0.8],
            ['loc' => url('/contact'), 'changefreq' => 'monthly', 'priority' => 0.7],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc' => $page['loc'],
                'lastmod' => now()->startOfDay()->toAtomString(),
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        }

        // 2. Dynamic Active Programmes
        $programmes = Programme::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($programmes as $prog) {
            $urls[] = [
                'loc' => route('programmes.show', $prog->slug),
                'lastmod' => $prog->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.8,
            ];
        }

        // 3. Dynamic Active Events
        $events = Event::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($events as $event) {
            $urls[] = [
                'loc' => route('events.show', $event->slug),
                'lastmod' => $event->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.8,
            ];
        }

        // 4. Dynamic Active Gallery Albums
        $albums = GalleryAlbum::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($albums as $album) {
            $urls[] = [
                'loc' => route('gallery.album', $album->slug),
                'lastmod' => $album->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.7,
            ];
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml; charset=utf-8');
    }
}
