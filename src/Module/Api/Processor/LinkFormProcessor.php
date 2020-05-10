<?php

declare(strict_types=1);

namespace App\Module\Api\Processor;

use App\Entity\Link;
use App\Module\Api\Request\LinkFormRequest;
use Doctrine\Persistence\ObjectManager;

class LinkFormProcessor
{
    private LinkFormRequest $linkFormRequest;

    private ObjectManager $objectManager;

    public function __construct(LinkFormRequest $linkFormRequest, ObjectManager $objectManager)
    {
        $this->linkFormRequest = $linkFormRequest;
        $this->objectManager = $objectManager;
    }

    public function process(): Link
    {
        $settings = [
            Link::SETTINGS_FIELD_URL => $this->linkFormRequest->getUrl(),
            Link::SETTINGS_FIELD_BACKGROUND_COLOR => $this->linkFormRequest->getBackgroundColor(),
            Link::SETTINGS_FIELD_TEXT_COLOR => $this->linkFormRequest->getTextColor(),
            Link::SETTINGS_FIELD_SIZE => $this->linkFormRequest->getSize(),
            Link::SETTINGS_FIELD_ICON => $this->linkFormRequest->getIcon(),
        ];

        $link = $this->linkFormRequest->getLink();
        $link->setTitle($this->linkFormRequest->getTitle());
        $link->setPage($this->linkFormRequest->getPage());
        $link->setRawSettings(json_encode($settings, JSON_THROW_ON_ERROR, 512));

        $this->objectManager->persist($link);
        $this->objectManager->flush();

        return $link;
    }
}
