<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use RuntimeException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(
 *     name="page",
 *     indexes={
 *          @ORM\Index(name="idx_status", columns={"status"}),
 *          @ORM\Index(name="idx_url", columns={"url"}),
 *     },
 * )
 */
class Page
{
    public const STATUS_DELETED = 0;
    public const STATUS_ACTIVE = 1;

    public const STATUSES = [
        self::STATUS_DELETED,
        self::STATUS_ACTIVE,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="pages")
     */
    private User $user;

    /**
     * @var Collection|Node[]
     * @ORM\OneToMany(targetEntity="App\Entity\Node", mappedBy="page")
     */
    private Collection $nodes;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $url;

    /**
     * @ORM\Column(type="integer", options={"default": 1})
     */
    private int $status = self::STATUS_ACTIVE;

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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new RuntimeException("status $status is not exist");
        }

        $this->status = $status;
    }

    /**
     * @return Node[]
     */
    public function getNodes(): array
    {
        $nodes = $this->nodes->toArray();
        return array_filter($nodes, static function(Node $node) {
            return $node->getStatus() !== Node::STATUS_DELETED;
        });
    }

    /**
     * @return Node[]
     */
    public function getSortedNodes(): array
    {
        $nodes = $this->getNodes();
        usort($nodes, static function(Node $x, Node $y) {
            if ($x->getSort() === $y->getSort()) {
                return 0;
            }

            return $x->getSort() > $y->getSort() ? 1 : -1;
        });
        return $nodes;
    }
}