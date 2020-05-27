<?php

declare(strict_types=1);

namespace App\Module\Analytics\DTO;

use App\Entity\Node;

class LinkStatisticsCollectionDTO
{
    /** @var LinkStatisticsDTO[] */
    private array $nodes;

    /**
     * LinkStatisticsCollectionDTO constructor.
     * @param Node[] $nodes
     */
    public function __construct(array $nodes)
    {
        foreach ($nodes as $node) {
            $this->nodes[] = new LinkStatisticsDTO($node);
        }
    }

    public function getCollection(): array
    {
        return $this->nodes;
    }
}
