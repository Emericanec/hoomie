<?php

declare(strict_types=1);

namespace App\Entity\Node;

class LinkNodeSettings
{
    public const FIELD_TITLE               = 'title';
    public const FIELD_URL                 = 'url';
    public const FIELD_BACKGROUND_COLOR    = 'backgroundColor';
    public const FIELD_TEXT_COLOR          = 'textColor';
    public const FIELD_SIZE                = 'size';
    public const FIELD_ICON                = 'icon';

    private string $title;

    private string $url;

    private string $backgroundColor;

    private string $textColor;

    private string $size;

    private string $icon;

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

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    public function setTextColor(string $textColor): void
    {
        $this->textColor = $textColor;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $settings = new self();
        $settings->setTitle($data[self::FIELD_TITLE]);
        $settings->setUrl($data[self::FIELD_URL]);
        $settings->setBackgroundColor($data[self::FIELD_BACKGROUND_COLOR]);
        $settings->setTextColor($data[self::FIELD_TEXT_COLOR]);
        $settings->setSize($data[self::FIELD_SIZE]);
        $settings->setIcon($data[self::FIELD_ICON]);

        return $settings;
    }

    public function getJson(): string
    {
        return json_encode([
            self::FIELD_TITLE => $this->getTitle(),
            self::FIELD_URL => $this->getUrl(),
            self::FIELD_BACKGROUND_COLOR => $this->getBackgroundColor(),
            self::FIELD_TEXT_COLOR => $this->getTextColor(),
            self::FIELD_SIZE => $this->getSize(),
            self::FIELD_ICON => $this->getIcon(),
        ], JSON_THROW_ON_ERROR);
    }
}
