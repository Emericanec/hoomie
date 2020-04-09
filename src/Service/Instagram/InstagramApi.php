<?php

declare(strict_types=1);

namespace App\Service\Instagram;

use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use Exception;
use Throwable;

class InstagramApi
{
    private const DEFAULT_PROFILE_IMAGE = 'https://t4.ftcdn.net/jpg/00/64/67/63/240_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg';

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

    public static function getProfileImageUrl(string $username): string
    {
        try {
            $json = file_get_contents("https://www.instagram.com/{$username}/?__a=1");
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            return $data['graphql']['user']['profile_pic_url'] ?? self::DEFAULT_PROFILE_IMAGE;
        } catch (Throwable $e) {
            return self::DEFAULT_PROFILE_IMAGE;
        }
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