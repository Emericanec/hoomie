<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface InstagramUserInterface extends UserInterface
{
    public function getId(): ?int;

    public function getEmail(): ?string;

    public function setEmail(string $email): self;

    public function getInstagramAccessToken(): string;

    public function setInstagramAccessToken(string $instagramAccessToken): void;

    public function getInstagramUserId(): string;

    public function setInstagramUserId(string $instagramUserId): void;

    public function getInstagramNickname(): string;

    public function setInstagramNickname(string $instagramNickname): void;

    public function setProfileImageUrl(string $profileImageUrl): void;

    public function getProfileImageUrl(): ?string;

    public function setIsActivated(bool $value): void;

    public function getIsActivated(): bool;
}