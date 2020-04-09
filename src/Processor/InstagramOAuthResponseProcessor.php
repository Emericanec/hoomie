<?php

declare(strict_types=1);

namespace App\Processor;

use App\Entity\User;
use App\Response\InstagramOAuthResponse;
use Doctrine\Persistence\ObjectManager;

class InstagramOAuthResponseProcessor
{
    private InstagramOAuthResponse $instagramOAuthTokenResponse;

    private ObjectManager $objectManager;

    public function __construct(ObjectManager $objectManager, InstagramOAuthResponse $instagramOAuthTokenResponse)
    {
        $this->instagramOAuthTokenResponse = $instagramOAuthTokenResponse;
        $this->objectManager = $objectManager;
    }

    public function createUser(): User
    {
        $user = new User();
        $user->setInstagramAccessToken($this->instagramOAuthTokenResponse->getAccessToken());
        $user->setInstagramUserId($this->instagramOAuthTokenResponse->getUserId());
        $user->setInstagramNickname($this->instagramOAuthTokenResponse->getNickname());
        $user->setProfileImageUrl($this->instagramOAuthTokenResponse->getProfileImageUrl());

        $this->objectManager->persist($user);

        $this->objectManager->flush();

        return $user;
    }

    public function updateUser(User $user): User
    {
        $user->setProfileImageUrl($this->instagramOAuthTokenResponse->getProfileImageUrl());

        $this->objectManager->persist($user);

        $this->objectManager->flush();

        return $user;
    }
}