<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Setting;

class LinkStyleManager
{
    private int $buttonStyleId;

    private string $textColor;

    private string $backgroundColor;

    private string $title;

    private string $icon;

    private string $hash;

    public function __construct(int $buttonStyleId, string $title, string $textColor, string $backgroundColor, string $icon)
    {
        $this->buttonStyleId = $buttonStyleId;
        $this->textColor = $textColor;
        $this->backgroundColor = $backgroundColor;
        $this->title = $title;
        $this->icon = $icon;
        $this->hash = sha1(uniqid('', true));
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getBorderColor(): string
    {
        return $this->backgroundColor;
    }

    public function getIconHtml(): string
    {
        return empty($this->icon) ? '' : "<i class='{$this->icon}'></i>";
    }

    public function getBackgroundColor(): string
    {
        return $this->isOutline() ? 'rgba(0, 0, 0, 0)' : $this->backgroundColor;
    }

    public function getHoverBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): string
    {
        return $this->isOutline() ? $this->backgroundColor : $this->textColor;
    }

    public function getHoverTextColor():string
    {
        return $this->textColor;
    }

    public function getBorderRadius(): string
    {
        switch (true) {
            case in_array($this->buttonStyleId, [Setting::BUTTON_STYLE_SQUARE, Setting::BUTTON_STYLE_OUTLINE_SQUARE], true):
                return '0px';
            case in_array($this->buttonStyleId, [Setting::BUTTON_STYLE_ROUND, Setting::BUTTON_STYLE_OUTLINE_ROUND], true):
                return '100px';
            default:
                return '0.3rem';
        }
    }

    private function isOutline(): bool
    {
        return in_array($this->buttonStyleId, [
            Setting::BUTTON_STYLE_OUTLINE_DEFAULT,
            Setting::BUTTON_STYLE_OUTLINE_SQUARE,
            Setting::BUTTON_STYLE_OUTLINE_ROUND,
        ], true);
    }
}
