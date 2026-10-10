<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImpactStatsService
{
    /**
     * Resolve verified impact statistics for public and dashboard display.
     *
     * @return array{ict_students: int, literacy_students: int, schools_supported: int, source: string}
     */
    public function getStats(): array
    {
        $feedMode = Setting::get('stat_feed_mode', 'manual');
        $apiEndpoint = Setting::get('stat_api_endpoint');

        if ($feedMode === 'api' && ! empty($apiEndpoint)) {
            $apiStats = $this->fetchFromEndpoint($apiEndpoint);

            if ($apiStats !== null) {
                return [
                    ...$apiStats,
                    'source' => 'api',
                ];
            }
        }

        return [
            'ict_students' => (int) Setting::get('stat_ict_students', 165),
            'literacy_students' => (int) Setting::get('stat_literacy_students', 591),
            'schools_supported' => (int) Setting::get('stat_schools_supported', 15),
            'source' => 'manual',
        ];
    }

    /**
     * Fetch statistics from an external HTTP JSON endpoint.
     *
     * @return array{ict_students: int, literacy_students: int, schools_supported: int}|null
     */
    public function fetchFromEndpoint(string $endpoint): ?array
    {
        try {
            $response = Http::timeout(3)->get($endpoint);

            if ($response->successful()) {
                $data = $response->json();

                $ict = $data['ict_students'] ?? $data['stat_ict_students'] ?? null;
                $literacy = $data['literacy_students'] ?? $data['stat_literacy_students'] ?? null;
                $schools = $data['schools_supported'] ?? $data['stat_schools_supported'] ?? null;

                if ($ict !== null && $literacy !== null && $schools !== null) {
                    return [
                        'ict_students' => (int) $ict,
                        'literacy_students' => (int) $literacy,
                        'schools_supported' => (int) $schools,
                    ];
                }
            }
        } catch (Throwable $e) {
            Log::warning("Failed to fetch impact stats from API [{$endpoint}]: ".$e->getMessage());
        }

        return null;
    }

    /**
     * Sync stats from API endpoint directly into local settings database.
     */
    public function syncFromApi(): bool
    {
        $endpoint = Setting::get('stat_api_endpoint');

        if (empty($endpoint)) {
            return false;
        }

        $stats = $this->fetchFromEndpoint($endpoint);

        if ($stats === null) {
            return false;
        }

        Setting::set('stat_ict_students', $stats['ict_students'], 'integer', 'impact');
        Setting::set('stat_literacy_students', $stats['literacy_students'], 'integer', 'impact');
        Setting::set('stat_schools_supported', $stats['schools_supported'], 'integer', 'impact');

        return true;
    }
}
