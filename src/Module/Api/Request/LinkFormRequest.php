<?php

declare(strict_types=1);

namespace App\Module\Api\Request;

use App\Entity\Link;
use App\Entity\Page;
use App\Entity\User;
use App\Helper\JsonRequest;
use App\Repository\LinkRepository;
use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LinkFormRequest
{
    private const PARAM_TITLE               = 'title';
    private const PARAM_URL                 = 'url';
    private const PARAM_BACKGROUND_COLOR    = 'backgroundColor';
    private const PARAM_TEXT_COLOR          = 'textColor';
    private const PARAM_SIZE                = 'size';
    private const PARAM_ICON                = 'icon';

    private Request $request;

    private JsonRequest $jsonRequest;

    private User $user;

    private int $pageId;

    private string $title;

    private string $url;

    private string $backgroundColor;

    private string $textColor;

    private string $size;

    private string $icon;

    private ?int $linkId = null;

    private ?Page $page = null;

    private ?Link $link = null;

    public function __construct(Request $request, User $user, int $pageId, int $linkId = null)
    {
        $this->request = $request;
        $this->jsonRequest = new JsonRequest($this->request);
        $this->user = $user;
        $this->pageId = $pageId;
        $this->linkId = $linkId;
        $this->title = $this->getJsonRequest()->getString(self::PARAM_TITLE);
        $this->url = $this->getJsonRequest()->getString(self::PARAM_URL, '');
        $this->backgroundColor = $this->getJsonRequest()->getString(self::PARAM_BACKGROUND_COLOR, '#007bff');
        $this->textColor = $this->getJsonRequest()->getString(self::PARAM_TEXT_COLOR, '#ffffff');
        $this->size = $this->getJsonRequest()->getString(self::PARAM_SIZE, '12');
        $this->icon = $this->getJsonRequest()->getString(self::PARAM_ICON, '');
    }

    public function handle(PageRepository $pageRepository, LinkRepository $linkRepository): void
    {
        $this->page = $pageRepository->findOneBy(['id' => $this->pageId, 'user' => $this->user->getId()]);

        if (null === $this->page) {
            throw new AccessDeniedHttpException('Permission denied');
        }

        if (null === $this->linkId) {
            $this->link = new Link();
        } else {
            $this->link = $linkRepository->find($this->linkId);
            if (null === $this->link || $this->link->getPage()->getId() !== $this->page->getId()) {
                throw new AccessDeniedHttpException('Permission denied');
            }
        }
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getJsonRequest(): JsonRequest
    {
        return $this->jsonRequest;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function getLink(): Link
    {
        return $this->link;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }
}
