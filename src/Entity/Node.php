<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NodeRepository")
 * @ORM\Table(
 *     name="node",
 *     indexes={
 *          @ORM\Index(name="idx_sort", columns={"sort"})
 *     },
 * )
 */
class Node
{
    public const STATUS_DELETED = 0;
    public const STATUS_ACTIVE  = 1;

    public const TYPE_LINK = 1;

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
     * @ORM\Column(type="text")
     */
    private string $settings;

    /**
     * @ORM\Column(type="integer")
     */
    private int $type;

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
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @Groups("default")
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @Groups("full")
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
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @Groups("default")
     */
    public function getJsonSettings(): string
    {
        return $this->settings;
    }

    public function setJsonSettings(string $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @Groups("default")
     */
    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }
}
