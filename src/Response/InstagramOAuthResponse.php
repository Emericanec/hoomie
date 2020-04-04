<?php

declare(strict_types=1);

namespace App\Response;

class InstagramOAuthResponse
{
    private string $accessToken;

    private string $nickname;

    private string $userId;

    public function __construct(string $accessToken, string $userId, string $nickname)
    {
        $this->accessToken = $accessToken;
        $this->userId = $userId;
        $this->nickname = $nickname;
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
}