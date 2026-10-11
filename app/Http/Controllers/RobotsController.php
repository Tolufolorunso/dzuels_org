<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Generate dynamic robots.txt directives.
     */
    public function __invoke(): Response
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /admin/',
            '',
            "Sitemap: {$sitemapUrl}",
            '',
        ]);

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
