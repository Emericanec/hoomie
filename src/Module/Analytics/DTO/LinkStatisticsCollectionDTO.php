<?php

declare(strict_types=1);

namespace App\Module\Analytics\DTO;

use App\Entity\Link;

class LinkStatisticsCollectionDTO
{
    /** @var LinkStatisticsDTO[] */
    private array $links;

    /**
     * LinkStatisticsCollectionDTO constructor.
     * @param Link[] $links
     */
    public function __construct(array $links)
    {
        foreach ($links as $link) {
            $this->links[] = new LinkStatisticsDTO($link);
        }
    }

    public function getCollection(): array
    {
        return $this->links;
    }
}
