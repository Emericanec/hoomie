<?php

declare(strict_types=1);

namespace App\Service\Instagram;

use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use Exception;

class InstagramApi
{
    /**
     * @return InstagramBasicDisplay
     * @throws Exception
     */
    public static function getInstance(): InstagramBasicDisplay
    {
        return new InstagramBasicDisplay([
            'appId' => self::getAppId(),
            'appSecret' => self::getAppSecret(),
            'redirectUri' => self::getAppCallback()
        ]);
    }

    private static function getAppId(): string
    {
        return $_SERVER['INSTAGRAM_APP_ID'] ?? '';
    }

    private static function getAppSecret():string
    {
        return $_SERVER['INSTAGRAM_APP_SECRET'] ?? '';
    }

    private static function getAppCallback():string
    {
        return $_SERVER['INSTAGRAM_APP_CALLBACK'] ?? '';
    }
}