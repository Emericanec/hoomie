<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkRepository")
 * @ORM\Table(name="link")
 */
class Link
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="links")
     */
    private Page $page;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $settings;

    /**
     * @Groups("default")
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @Groups({"full"})
     */
    public function getPage(): Page
    {
        return $this->page;
    }

    public function setPage(Page $page): void
    {
        $this->page = $page;
    }

    /**
     * @Groups("default")
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getRawSettings(): string
    {
        return $this->settings;
    }

    public function setRawSettings(string $settings): void
    {
        $this->settings = $settings;
    }
}