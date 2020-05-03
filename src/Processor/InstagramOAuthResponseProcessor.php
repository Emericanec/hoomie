<?php

declare(strict_types=1);

namespace App\Processor;

use App\Entity\User;
use App\Repository\PageRepository;
use App\Response\InstagramOAuthResponse;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    /**
     * @param PageRepository $pageRepository
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createUser(PageRepository $pageRepository): User
    {
        $user = new User();
        $user->setInstagramAccessToken($this->instagramOAuthTokenResponse->getAccessToken());
        $user->setInstagramUserId($this->instagramOAuthTokenResponse->getUserId());
        $user->setInstagramNickname($this->instagramOAuthTokenResponse->getNickname());
        $user->setProfileImageUrl($this->instagramOAuthTokenResponse->getProfileImageUrl());

        $this->objectManager->persist($user);

        $this->objectManager->flush();

        $pageRepository->createMainPage($user);

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