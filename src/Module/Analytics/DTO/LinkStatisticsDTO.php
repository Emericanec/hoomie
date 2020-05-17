<?php

declare(strict_types=1);

namespace App\Module\Analytics\DTO;

use App\Entity\Link;
use App\Service\Analytics\Entity\Link as LinkStats;

class LinkStatisticsDTO
{
    private Link $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function getLink(): Link
    {
        return $this->link;
    }

    public function getIcon(): string
    {
        $settings = $this->getLink()->getSettings();
        $icon = $settings[Link::SETTINGS_FIELD_ICON] ?? '';
        return !empty($icon) ? (string)$icon : 'fas fa-link';
    }

    public function getTitle(): string
    {
        return $this->getLink()->getTitle();
    }

    public function getBackgroundColor(): string
    {
        $settings = $this->getLink()->getSettings();
        $color = $settings[Link::SETTINGS_FIELD_BACKGROUND_COLOR] ?? '';
        return !empty($color) ? (string)$color : '#17a2b8';
    }

    public function getTextColor(): string
    {
        $settings = $this->getLink()->getSettings();
        $color = $settings[Link::SETTINGS_FIELD_TEXT_COLOR] ?? '';
        return !empty($color) ? (string)$color : '#ffffff';
    }

    public function getTotalClicks(): int
    {
        return (new LinkStats($this->getLink()))->getTotalClicks();
    }
}
