<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutTest extends TestCase
{
    public function test_homepage_loads_successfully_with_public_layout(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Dzuels Educational Foundation');
    }

    public function test_layout_renders_navigation_links_and_donate_cta(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('About Us');
        $response->assertSeeText('Programmes');
        $response->assertSeeText('Events');
        $response->assertSeeText('Gallery');
        $response->assertSeeText('Get Involved');
        $response->assertSeeText('Contact');
        $response->assertSee('/donate');
    }

    public function test_layout_renders_utility_bar_contacts(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('+234 803 388 9576');
        $response->assertSeeText('info@dzuelsfoundation.org');
        $response->assertSeeText('Ijero-Ekiti, Ekiti State, Nigeria');
    }

    public function test_layout_includes_anti_flicker_script_and_theme_toggle(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('dzuels-theme');
        $response->assertSee('id="themeToggleBtn"', false);
    }

    public function test_layout_renders_four_column_footer(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Quick Links');
        $response->assertSeeText('Programmes & Giving');
        $response->assertSeeText('Stay Connected');
        $response->assertSeeText('Privacy Policy');
        $response->assertSeeText('12b Doherty Road, Ijero-Ekiti');
    }
}
