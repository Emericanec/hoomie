<?php

declare(strict_types=1);

namespace App\Processor;

use App\Exception\InstagramOAuthException;
use App\Response\InstagramOAuthResponse;
use App\Service\Instagram\InstagramApi;
use Exception;

class InstagramOAuthProcessor
{
    private const PROFILE_FIELDS = 'account_type, id, media_count, username';

    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return InstagramOAuthResponse
     * @throws Exception
     */
    public function process(): InstagramOAuthResponse
    {
        $instagramApi = InstagramApi::getInstance();
        $token = $instagramApi->getOAuthToken($this->code, true);
        $accessToken = $instagramApi->getLongLivedToken($token, true);
        $instagramApi->setAccessToken($accessToken);
        $instagramApi->setUserFields(self::PROFILE_FIELDS);
        $profile = $instagramApi->getUserProfile();

        if (!$this->isValid($profile)) {
            $errorMessage = $profile->error->message ?? 'Undefined error';
            throw new InstagramOAuthException($errorMessage);
        }

        $profileImageUrl = InstagramApi::getProfileImageUrl($profile->username);

        return new InstagramOAuthResponse($accessToken, $profile->id, $profile->username, $profileImageUrl);
    }

    private function isValid($profile): bool
    {
        return property_exists($profile, 'id') && property_exists($profile, 'username');
    }
}