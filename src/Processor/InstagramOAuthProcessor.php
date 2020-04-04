<?php

declare(strict_types=1);

namespace App\Processor;

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

        //todo add error handle
        return new InstagramOAuthResponse($accessToken, $profile->id, $profile->username);
    }
}