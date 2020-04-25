<?php

declare(strict_types=1);

namespace App\Helper;

class Env
{
    public const LOCAL_MODE = 'local';

    public static function isLocalMode(): bool
    {
        return isset($_SERVER['DEVELOPMENT_MODE']) && $_SERVER['DEVELOPMENT_MODE'] === self::LOCAL_MODE;
    }
}