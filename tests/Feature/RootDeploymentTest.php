<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class RootDeploymentTest extends TestCase
{
    public function test_root_index_php_exists_and_is_valid(): void
    {
        $rootIndexPath = base_path('index.php');
        $this->assertFileExists($rootIndexPath);

        $content = File::get($rootIndexPath);
        $this->assertStringContainsString("require __DIR__.'/vendor/autoload.php';", $content);
        $this->assertStringContainsString("require_once __DIR__.'/bootstrap/app.php';", $content);
        $this->assertStringContainsString('$app->usePublicPath(__DIR__);', $content);
        $this->assertStringContainsString('$app->handleRequest', $content);
    }

    public function test_root_htaccess_exists_and_protects_sensitive_files(): void
    {
        $rootHtaccessPath = base_path('.htaccess');
        $this->assertFileExists($rootHtaccessPath);

        $content = File::get($rootHtaccessPath);
        $this->assertStringContainsString('RewriteEngine On', $content);
        $this->assertStringContainsString('RewriteRule ^\.(?!well-known).* - [F,L,NC]', $content);
        $this->assertStringContainsString('app|bootstrap|config|database|resources|routes|storage|tests|vendor|blueprint', $content);
        $this->assertStringContainsString('artisan|composer\.(json|lock)|package(-lock)?\.json|phpunit\.xml', $content);
        $this->assertStringContainsString('RewriteRule ^ index.php [L]', $content);
    }

    public function test_application_continues_serving_home_route(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
