<?php

declare(strict_types=1);

namespace App\Processor;

use App\Exception\InstagramOAuthException;
use App\Response\InstagramOAuthResponse;
use App\Service\Instagram\InstagramApi;
use Exception;

class InstagramOAuthProcessor
{
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
        $profile = $instagramApi->getUserProfile();

        if (!$this->isValid($profile)) {
            $errorMessage = $profile->error->message ?? 'Undefined error';
            throw new InstagramOAuthException($errorMessage);
        }

        return new InstagramOAuthResponse($accessToken, $profile->id, $profile->username);
    }

    private function isValid($profile): bool
    {
        return property_exists($profile, 'id') && property_exists($profile, 'username');
    }
}