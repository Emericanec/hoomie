<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkRepository")
 * @ORM\Table(
 *     name="link",
 *     indexes={
 *          @ORM\Index(name="idx_sort", columns={"sort"})
 *     },
 * )
 */
class Link
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
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
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private int $sort = 0;

    /**
     * @ORM\Column(type="integer", options={"default": 1})
     */
    private int $status = self::STATUS_ACTIVE;

    /**
     * @Groups("default")
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @Groups("default")
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
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

    /**
     * @Groups("default")
     * @return array
     */
    public function getSettings(): array
    {
        return json_decode($this->settings, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}