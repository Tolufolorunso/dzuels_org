<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $optimizer
    ) {}

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
            $activeSectionsCount = PageSection::where('page_key', $key)->where('is_active', true)->count();
            $lastUpdated = PageSection::where('page_key', $key)->latest('updated_at')->value('updated_at');

            $pages[$key] = [
                'key' => $key,
                'title' => $meta['title'],
                'description' => $meta['description'],
                'public_url' => $meta['public_url'],
                'sections_count' => $sectionsCount,
                'active_sections_count' => $activeSectionsCount,
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
            'image_path' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'meta_data' => ['nullable', 'array'],
            'meta_data.tagline' => ['nullable', 'string', 'max:255'],
            'meta_data.pillars' => ['nullable', 'array'],
            'meta_data.pillars.*.title' => ['nullable', 'string', 'max:150'],
            'meta_data.pillars.*.ministry' => ['nullable', 'string', 'max:150'],
            'meta_data.pillars.*.description' => ['nullable', 'string', 'max:500'],
            'meta_data.pillars.*.icon' => ['nullable', 'string', 'max:50'],
            'meta_data.photos' => ['nullable', 'array'],
            'meta_data.photos.*.image' => ['nullable', 'string', 'max:255'],
            'meta_data.photos.*.label' => ['nullable', 'string', 'max:100'],
            'meta_data.photos.*.alt' => ['nullable', 'string', 'max:255'],
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

        if ($request->has('is_active')) {
            $pageSection->is_active = $request->boolean('is_active');
        }

        if (isset($validated['sort_order'])) {
            $pageSection->sort_order = (int) $validated['sort_order'];
        }

        if ($request->has('meta_data')) {
            $pageSection->meta_data = $validated['meta_data'] ?? null;
        }

        if ($request->hasFile('image')) {
            $pageSection->image_path = $this->optimizer->optimize(
                $request->file('image'),
                'uploads/cms',
                1920,
                1920,
                82
            );
        } elseif ($request->has('image_path')) {
            $pageSection->image_path = $validated['image_path'] ?: null;
        }

        $pageSection->save();

        return back()->with('success', "Section '{$section}' updated successfully!");
    }

    /**
     * Toggle visibility (is_active) for a specific section.
     */
    public function toggleStatus(string $page, string $section): RedirectResponse
    {
        if (! array_key_exists($page, $this->managedPages)) {
            abort(404, 'Invalid page key.');
        }

        $pageSection = PageSection::where('page_key', $page)
            ->where('section_key', $section)
            ->firstOrFail();

        $pageSection->is_active = ! $pageSection->is_active;
        $pageSection->save();

        $statusText = $pageSection->is_active ? 'Active (Visible on Live Page)' : 'Hidden from Live Page';

        return back()->with('success', "Section '{$section}' is now {$statusText}.");
    }

    /**
     * Move section up in display order.
     */
    public function moveUp(string $page, string $section): RedirectResponse
    {
        if (! array_key_exists($page, $this->managedPages)) {
            abort(404, 'Invalid page key.');
        }

        $sections = PageSection::where('page_key', $page)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $currentIndex = $sections->search(fn ($item) => $item->section_key === $section);

        if ($currentIndex === false) {
            abort(404, 'Section not found.');
        }

        if ($currentIndex > 0) {
            // Normalize all sort orders with step of 10
            foreach ($sections as $idx => $sec) {
                $sec->sort_order = ($idx + 1) * 10;
                $sec->save();
            }

            // Swap with previous item
            $current = $sections[$currentIndex];
            $previous = $sections[$currentIndex - 1];

            $temp = $current->sort_order;
            $current->sort_order = $previous->sort_order;
            $previous->sort_order = $temp;

            $current->save();
            $previous->save();

            return back()->with('success', "Section '{$section}' moved up.");
        }

        return back()->with('info', "Section '{$section}' is already at the top.");
    }

    /**
     * Move section down in display order.
     */
    public function moveDown(string $page, string $section): RedirectResponse
    {
        if (! array_key_exists($page, $this->managedPages)) {
            abort(404, 'Invalid page key.');
        }

        $sections = PageSection::where('page_key', $page)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $currentIndex = $sections->search(fn ($item) => $item->section_key === $section);

        if ($currentIndex === false) {
            abort(404, 'Section not found.');
        }

        if ($currentIndex < $sections->count() - 1) {
            // Normalize all sort orders with step of 10
            foreach ($sections as $idx => $sec) {
                $sec->sort_order = ($idx + 1) * 10;
                $sec->save();
            }

            // Swap with next item
            $current = $sections[$currentIndex];
            $next = $sections[$currentIndex + 1];

            $temp = $current->sort_order;
            $current->sort_order = $next->sort_order;
            $next->sort_order = $temp;

            $current->save();
            $next->save();

            return back()->with('success', "Section '{$section}' moved down.");
        }

        return back()->with('info', "Section '{$section}' is already at the bottom.");
    }
}
