<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 * @ORM\Table(
 *     name="file",
 *     indexes={
 *          @ORM\Index(name="idx_s3_key", columns={"s3_key"}),
 *     },
 * )
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private User $user;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private string $title;

    /**
     * @ORM\Column(name="s3_key", type="string", length=50)
     */
    private string $s3Key;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $url;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getS3Key(): string
    {
        return $this->s3Key;
    }

    public function setS3Key(string $s3Key): void
    {
        $this->s3Key = $s3Key;
    }

    public function getUrl(): string
    {
        return $this->url;
    }


    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}