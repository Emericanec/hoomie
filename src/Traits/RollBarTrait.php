<?php

declare(strict_types=1);

namespace App\Traits;

use Rollbar\Rollbar;
use Rollbar\RollbarLogger;

trait RollBarTrait
{
    private static ?RollbarLogger $_logger = null;

    public static function logger(): RollbarLogger
    {
        if (null === self::$_logger) {
            Rollbar::init([
                'access_token' => self::getAccessToken(),
                'environment' => self::getEnvironment(),
                'root' => __DIR__ . '/../../'
            ]);

            self::$_logger = Rollbar::logger();
        }

        return self::$_logger;
    }

    private static function getAccessToken(): string
    {
        return $_SERVER['ROLL_BAR_ACCESS_TOKEN'] ?? '';
    }

    private static function getEnvironment():string
    {
        return $_SERVER['ROLL_BAR_ENVIRONMENT'] ?? '';
    }
}