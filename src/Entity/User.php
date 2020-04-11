<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="user",
 *     indexes={
 *          @Index(name="idx_instagram_user_id", columns={"instagram_user_id"})
 *     },
 *)
 */
class User implements InstagramUserInterface
{
    public const ROLE = 'ROLE_USER';

    private const SALT = '8c20ffeec06fcd5744a8fa38ef8c03a1';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $email = null;

    /**
     * @var string
     * @ORM\Column(name="instagram_access_token", type="string")
     */
    private string $instagramAccessToken;

    /**
     * @var string
     * @ORM\Column(name="instagram_nickname", type="string")
     */
    private string $instagramNickname;

    /**
     * @var string
     * @ORM\Column(name="instagram_user_id", type="string", unique=true)
     */
    private string $instagramUserId;

    /**
     * @var string
     * @ORM\Column(name="profile_image_url", type="string", nullable=true)
     */
    private ?string $profileImageUrl = null;

    /**
     * @var bool
     * @ORM\Column(name="is_activated", type="boolean")
     */
    private bool $isActivated = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see InstagramUserInterface
     */
    public function getUsername(): string
    {
        return $this->getInstagramNickname();
    }

    /**
     * @see InstagramUserInterface
     */
    public function getRoles(): array
    {
        return [self::ROLE];
    }

    /**
     * @see InstagramUserInterface
     */
    public function getPassword(): string
    {
        return '';
    }

    public function getInstagramAccessToken(): string
    {
        return $this->instagramAccessToken;
    }

    public function setInstagramAccessToken(string $instagramAccessToken): void
    {
        $this->instagramAccessToken = $instagramAccessToken;
    }

    public function getInstagramUserId(): string
    {
        return $this->instagramUserId;
    }

    public function setInstagramUserId(string $instagramUserId): void
    {
        $this->instagramUserId = $instagramUserId;
    }

    public function getInstagramNickname(): string
    {
        return $this->instagramNickname;
    }

    public function setInstagramNickname(string $instagramNickname): void
    {
        $this->instagramNickname = $instagramNickname;
    }

    /**
     * @see InstagramUserInterface
     */
    public function getSalt(): string
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return self::SALT;
    }

    /**
     * @see InstagramUserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString(): string
    {
        return $this->getInstagramNickname();
    }

    public function setProfileImageUrl(string $profileImageUrl): void
    {
        $this->profileImageUrl = $profileImageUrl;
    }

    public function getProfileImageUrl(): ?string
    {
        return $this->profileImageUrl;
    }

    public function setIsActivated(bool $value): void
    {
        $this->isActivated = $value;
    }

    public function getIsActivated(): bool
    {
        return $this->isActivated;
    }
}
