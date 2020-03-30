<?php

declare(strict_types=1);

namespace App\Service\Instagram;

use Exception;
use MetzWeb\Instagram\Instagram;

class InstagramApi
{
    /**
     * @return Instagram
     * @throws Exception
     */
    public static function getInstance(): Instagram
    {
        return new Instagram([
            'apiKey' => self::getAppId(),
            'apiSecret' => self::getAppSecret(),
            'apiCallback' => self::getAppCallback()
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