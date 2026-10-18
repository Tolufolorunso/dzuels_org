<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use App\Services\GeminiService;
use App\Services\ImageOptimizerService;
use App\Services\ImpactStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the site settings administration interface.
     */
    public function index(ImpactStatsService $statsService, ImageOptimizerService $optimizer): View
    {
        $settings = Setting::allCached();
        $activeStats = $statsService->getStats();
        $optimizerStatus = $optimizer->getEnvironmentStatus();

        return view('admin.settings', compact('settings', 'activeStats', 'optimizerStatus'));
    }

    /**
     * Update site settings from submitted form values.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $typeMap = [
            'stat_ict_students' => ['integer', 'impact'],
            'stat_literacy_students' => ['integer', 'impact'],
            'stat_schools_supported' => ['integer', 'impact'],
            'stat_feed_mode' => ['string', 'impact'],
            'stat_api_endpoint' => ['string', 'impact'],

            'contact_phone_1' => ['string', 'contact'],
            'contact_phone_2' => ['string', 'contact'],
            'contact_phone_3' => ['string', 'contact'],
            'contact_phone_4' => ['string', 'contact'],
            'contact_phone_5' => ['string', 'contact'],
            'contact_email' => ['string', 'contact'],
            'contact_address' => ['string', 'contact'],
            'contact_working_hours' => ['string', 'contact'],
            'contact_timezone' => ['string', 'contact'],
            'contact_working_days_note' => ['string', 'contact'],

            'site_logo' => ['string', 'general'],

            'flutterwave_url' => ['string', 'banking'],
            'givebutter_url' => ['string', 'banking'],
            'bank_name' => ['string', 'banking'],
            'bank_account_name' => ['string', 'banking'],
            'bank_account_number' => ['string', 'banking'],

            'registration_number' => ['string', 'social'],
            'social_linkedin' => ['string', 'social'],
            'social_facebook' => ['string', 'social'],
            'social_twitter' => ['string', 'social'],
            'social_instagram' => ['string', 'social'],

            'gemini_api_key' => ['string', 'ai'],
            'gemini_model' => ['string', 'ai'],
        ];

        foreach ($typeMap as $key => [$type, $group]) {
            $value = $validated[$key] ?? null;
            Setting::set($key, $value, $type, $group);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Site settings updated successfully.');
    }

    /**
     * Test the configured or submitted Google Gemini API connection.
     */
    public function testGemini(Request $request, GeminiService $geminiService): JsonResponse
    {
        $apiKey = $request->input('api_key');
        $model = $request->input('model');

        $result = $geminiService->testConnection($apiKey, $model);

        return response()->json($result);
    }

    /**
     * Manually trigger synchronization from external API endpoint.
     */
    public function syncApi(ImpactStatsService $statsService): RedirectResponse
    {
        $synced = $statsService->syncFromApi();

        if ($synced) {
            return redirect()->route('admin.settings')
                ->with('success', 'Impact statistics successfully synchronized from external API feed.');
        }

        return redirect()->route('admin.settings')
            ->with('error', 'Could not sync from API endpoint. Please verify the URL endpoint format.');
    }

    /**
     * Run on-demand diagnostic test for image optimization and WebP pipeline.
     */
    public function testOptimizer(ImageOptimizerService $optimizer): JsonResponse
    {
        $status = $optimizer->getEnvironmentStatus();
        $testResult = $optimizer->testCompression();

        return response()->json([
            'environment' => $status,
            'test_result' => $testResult,
        ]);
    }
}
