<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function test_the_homepage_displays_the_homepage_text(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Homepage');
    }
}
