<?php

declare(strict_types=1);

namespace App\Module\Analytics\DTO;

use App\Entity\Node;
use App\Service\Analytics\Entity\Link as LinkStats;

class LinkStatisticsDTO
{
    private Node $node;

    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function getIcon(): string
    {
        return 'fas fa-link';
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getBackgroundColor(): string
    {
        return '#17a2b8';
    }

    public function getTextColor(): string
    {
        return '#ffffff';
    }

    public function getTotalClicks(): int
    {
        return (new LinkStats($this->getNode()))->getTotalClicks();
    }
}
