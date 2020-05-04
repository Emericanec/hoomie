<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Setting;

class ThemeManager
{
    private Setting $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function getButtonStyle(): array
    {
        return self::getButtonStylesList()[$this->setting->getButtonStyleId()];
    }

    public function getBackgroundStyle(): array
    {
        return self::getBackgroundStylesList()[$this->setting->getBackgroundStyleId()];
    }

    public static function getButtonStylesList(): array
    {
        return [
            Setting::BUTTON_STYLE_DEFAULT => [
                'id' => Setting::BUTTON_STYLE_DEFAULT,
                'title' => 'Default',
                'classes' => 'btn-dark'
            ],
            Setting::BUTTON_STYLE_OUTLINE_DEFAULT => [
                'id' => Setting::BUTTON_STYLE_OUTLINE_DEFAULT,
                'title' => 'Outline Default',
                'classes' => 'btn-outline-dark'
            ],
            Setting::BUTTON_STYLE_SQUARE => [
                'id' => Setting::BUTTON_STYLE_SQUARE,
                'title' => 'Square',
                'classes' => 'btn-dark btn-square'
            ],
            Setting::BUTTON_STYLE_OUTLINE_SQUARE => [
                'id' => Setting::BUTTON_STYLE_OUTLINE_SQUARE,
                'title' => 'Outline Square',
                'classes' => 'btn-outline-dark btn-square'
            ],
            Setting::BUTTON_STYLE_ROUND => [
                'id' => Setting::BUTTON_STYLE_ROUND,
                'title' => 'Round',
                'classes' => 'btn-dark btn-round'
            ],
            Setting::BUTTON_STYLE_OUTLINE_ROUND => [
                'id' => Setting::BUTTON_STYLE_OUTLINE_ROUND,
                'title' => 'Outline Round',
                'classes' => 'btn-outline-dark btn-round'
            ],
        ];
    }

    public static function getBackgroundStylesList(): array
    {
        return [
            Setting::BACKGROUND_STYLE_DEFAULT => [
                'id' => Setting::BACKGROUND_STYLE_DEFAULT,
                'title' => 'default',
                'color' => 'background-color: #f4f6f9;'
            ],
            Setting::BACKGROUND_STYLE_LIGHT_GREY => [
                'id' => Setting::BACKGROUND_STYLE_LIGHT_GREY,
                'title' => 'Light Grey',
                'color' => 'background-color: #919191;'
            ],
            Setting::BACKGROUND_STYLE_DARK_GREY => [
                'id' => Setting::BACKGROUND_STYLE_DARK_GREY,
                'title' => 'Dark Grey',
                'color' => 'background-color: #4a4a4a;'
            ],
            Setting::BACKGROUND_STYLE_BLACK => [
                'id' => Setting::BACKGROUND_STYLE_BLACK,
                'title' => 'Black',
                'color' => 'background-color: #000000;'
            ],
            Setting::BACKGROUND_STYLE_GRADIENT_1 => [
                'id' => Setting::BACKGROUND_STYLE_GRADIENT_1,
                'title' => 'Gradient 1',
                'color' => 'background: linear-gradient(-45deg, #ee7752, #e73c7e); background-size: 100% 100%;'
            ],
            Setting::BACKGROUND_STYLE_GRADIENT_2 => [
                'id' => Setting::BACKGROUND_STYLE_GRADIENT_2,
                'title' => 'Gradient 2',
                'color' => 'background: linear-gradient(-45deg, #e73c7e, #23a6d5); background-size: 100% 100%;'
            ],
            Setting::BACKGROUND_STYLE_GRADIENT_3 => [
                'id' => Setting::BACKGROUND_STYLE_GRADIENT_3,
                'title' => 'Gradient 3',
                'color' => 'background: linear-gradient(-45deg, #23a6d5, #23d5ab); background-size: 100% 100%;'
            ],
            Setting::BACKGROUND_STYLE_GRADIENT_4 => [
                'id' => Setting::BACKGROUND_STYLE_GRADIENT_4,
                'title' => 'Gradient 4',
                'color' => 'background: linear-gradient(-45deg, #ceb12d, #23d5ab); background-size: 100% 100%;'
            ],
        ];
    }
}
