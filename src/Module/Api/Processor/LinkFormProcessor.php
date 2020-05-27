<?php

declare(strict_types=1);

namespace App\Module\Api\Processor;

use App\Entity\Node;
use App\Entity\Node\LinkNodeSettings;
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

    public function process(): Node
    {
        $node = $this->linkFormRequest->getNode();
        $node->setType(Node::TYPE_LINK);
        $node->setPage($this->linkFormRequest->getPage());

        $settings = new LinkNodeSettings();
        $settings->setUrl($this->linkFormRequest->getUrl());
        $settings->setTitle($this->linkFormRequest->getTitle());
        $settings->setBackgroundColor($this->linkFormRequest->getBackgroundColor());
        $settings->setTextColor($this->linkFormRequest->getTextColor());
        $settings->setSize($this->linkFormRequest->getSize());
        $settings->setIcon($this->linkFormRequest->getIcon());

        $node->setJsonSettings($settings->getJson());

        $this->objectManager->persist($node);
        $this->objectManager->flush();

        return $node;
    }
}
