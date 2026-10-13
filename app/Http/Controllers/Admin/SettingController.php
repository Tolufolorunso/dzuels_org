<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use App\Services\ImpactStatsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the site settings administration interface.
     */
    public function index(ImpactStatsService $statsService): View
    {
        $settings = Setting::allCached();
        $activeStats = $statsService->getStats();

        return view('admin.settings', compact('settings', 'activeStats'));
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
        ];

        foreach ($typeMap as $key => [$type, $group]) {
            $value = $validated[$key] ?? null;
            Setting::set($key, $value, $type, $group);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Site settings updated successfully.');
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
}
