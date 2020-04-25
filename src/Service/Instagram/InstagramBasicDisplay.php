<?php

declare(strict_types=1);

namespace App\Service\Instagram;

use App\Helper\Env;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException;

class InstagramBasicDisplay extends \EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay
{
    /**
     * @param array $scopes
     * @param string $state
     * @return string
     * @throws InstagramBasicDisplayException
     */
    public function getLoginUrl($scopes = ['user_profile', 'user_media'], $state = ''): string
    {
        if (Env::isLocalMode()) {
            return '/local';
        }

        return parent::getLoginUrl($scopes, $state);
    }
}