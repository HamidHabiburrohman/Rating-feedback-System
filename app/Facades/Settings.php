<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static array getGroup(string $group)
 * @method static bool set(string $key, mixed $value)
 * @method static bool has(string $key)
 * @method static array getPublicSettings()
 * @method static array getGroups()
 * @method static void clearCache()
 * @method static bool reset(string $key)
 * @method static int resetGroup(string $group)
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'settings';
    }
}