<?php

declare(strict_types=1);

namespace App\Response;

class InstagramOAuthResponse
{
    private string $accessToken;

    private string $nickname;

    private string $userId;

    private string $profileImageUrl;

    public function __construct(string $accessToken, string $userId, string $nickname, string $profileImageUrl)
    {
        $this->accessToken = $accessToken;
        $this->userId = $userId;
        $this->nickname = $nickname;
        $this->profileImageUrl = $profileImageUrl;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getProfileImageUrl(): string
    {
        return $this->profileImageUrl;
    }
}