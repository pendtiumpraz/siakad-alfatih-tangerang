<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    /**
     * Get setting value by key with caching
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            // Cast value based on type
            return match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'number' => is_numeric($setting->value) ? (float) $setting->value : $default,
                'json' => json_decode($setting->value, true) ?? $default,
                default => $setting->value ?? $default,
            };
        });
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text', ?string $description = null): void
    {
        // Convert value based on type
        $storedValue = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => (string) $value,
        };

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );

        // Clear cache
        Cache::forget("setting_{$key}");
    }

    /**
     * Get all settings by group
     */
    public static function getGroup(string $group): array
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return static::where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
