<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Managed public pages metadata.
     *
     * @var array<string, array<string, string>>
     */
    protected array $managedPages = [
        'home' => [
            'title' => 'Home Page',
            'description' => 'Main landing page featuring the hero banner, who we are, vision, values, causes, and featured project.',
            'public_url' => '/',
        ],
        'about' => [
            'title' => 'About Us',
            'description' => 'Our 10-year journey, foundational heritage, approach, and leadership structure.',
            'public_url' => '/about',
        ],
        'programmes' => [
            'title' => 'Programmes Index',
            'description' => 'Catalog of foundation education and digital literacy initiatives.',
            'public_url' => '/programmes',
        ],
        'contact' => [
            'title' => 'Contact Us',
            'description' => 'Public contact page, headquarters office details, and enquiry form.',
            'public_url' => '/contact',
        ],
        'donate' => [
            'title' => 'Donate & Support',
            'description' => 'Giving channels, Flutterwave link, bank accounts, and transparency FAQs.',
            'public_url' => '/donate',
        ],
        'get_involved' => [
            'title' => 'Get Involved',
            'description' => 'Volunteer and corporate partnership recruitment page.',
            'public_url' => '/get-involved',
        ],
    ];

    /**
     * List all manageable public website pages.
     */
    public function index(): View
    {
        $pages = [];

        foreach ($this->managedPages as $key => $meta) {
            $sectionsCount = PageSection::where('page_key', $key)->count();
            $lastUpdated = PageSection::where('page_key', $key)->latest('updated_at')->value('updated_at');

            $pages[$key] = [
                'key' => $key,
                'title' => $meta['title'],
                'description' => $meta['description'],
                'public_url' => $meta['public_url'],
                'sections_count' => $sectionsCount,
                'last_updated' => $lastUpdated,
            ];
        }

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the visual section editor for a specific page.
     */
    public function edit(string $page): View
    {
        if (! array_key_exists($page, $this->managedPages)) {
            abort(404, 'Page not found in managed pages list.');
        }

        $pageMeta = $this->managedPages[$page];
        $sections = PageSection::where('page_key', $page)->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.pages.edit', compact('page', 'pageMeta', 'sections'));
    }

    /**
     * Update a specific section on a page.
     */
    public function updateSection(Request $request, string $page, string $section): RedirectResponse
    {
        if (! array_key_exists($page, $this->managedPages)) {
            abort(404, 'Invalid page key.');
        }

        $validated = $request->validate([
            'headline' => ['nullable', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:255'],
            'body_text' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $pageSection = PageSection::firstOrNew([
            'page_key' => $page,
            'section_key' => $section,
        ]);

        $pageSection->headline = $validated['headline'] ?? null;
        $pageSection->subheadline = $validated['subheadline'] ?? null;
        $pageSection->body_text = $validated['body_text'] ?? null;
        $pageSection->button_text = $validated['button_text'] ?? null;
        $pageSection->button_url = $validated['button_url'] ?? null;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $uploadDir = public_path('front/images/cms');

            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = $imageFile->getClientOriginalExtension() ?: 'webp';
            $filename = $page.'_'.$section.'_'.Str::random(10).'.'.$extension;
            $imageFile->move($uploadDir, $filename);

            $pageSection->image_path = 'front/images/cms/'.$filename;
        }

        $pageSection->save();

        return back()->with('success', "Section '{$section}' updated successfully!");
    }
}
