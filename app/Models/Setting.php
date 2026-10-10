<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Cache key for settings storage.
     */
    public const CACHE_KEY = 'dzuels_site_settings';

    /**
     * Retrieve a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::allCached();

        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        return $settings[$key];
    }

    /**
     * Set or update a setting value.
     */
    public static function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): self
    {
        $serializedValue = match ($type) {
            'json' => is_array($value) ? json_encode($value) : $value,
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $serializedValue,
                'type' => $type,
                'group' => $group,
            ]
        );

        static::clearCache();

        return $setting;
    }

    /**
     * Retrieve all settings grouped by their group identifier.
     */
    public static function allGrouped(): array
    {
        return static::all()->groupBy('group')->map(function ($items) {
            return $items->pluck('value', 'key');
        })->toArray();
    }

    /**
     * Retrieve all settings as key => casted value array cached in memory/cache.
     */
    public static function allCached(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                return static::all()->mapWithKeys(function ($setting) {
                    return [$setting->key => static::castValue($setting->value, $setting->type)];
                })->toArray();
            });
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Cast raw string value to native PHP type according to setting definition.
     */
    public static function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'integer', 'int' => (int) $value,
            'boolean', 'bool' => in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true),
            'json', 'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Invalidate the settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
