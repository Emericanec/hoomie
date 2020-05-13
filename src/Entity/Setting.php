<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 * @ORM\Table(
 *     name="setting"
 * )
 */
class Setting
{
    public const BUTTON_STYLE_DEFAULT = 1;
    public const BUTTON_STYLE_OUTLINE_DEFAULT = 2;
    public const BUTTON_STYLE_SQUARE = 3;
    public const BUTTON_STYLE_OUTLINE_SQUARE = 4;
    public const BUTTON_STYLE_ROUND = 5;
    public const BUTTON_STYLE_OUTLINE_ROUND = 6;

    public const BACKGROUND_STYLE_DEFAULT = 1;
    public const BACKGROUND_STYLE_LIGHT_GREY = 2;
    public const BACKGROUND_STYLE_DARK_GREY = 3;
    public const BACKGROUND_STYLE_BLACK = 4;
    public const BACKGROUND_STYLE_GRADIENT_1 = 6;
    public const BACKGROUND_STYLE_GRADIENT_2 = 7;
    public const BACKGROUND_STYLE_GRADIENT_3 = 8;
    public const BACKGROUND_STYLE_GRADIENT_4 = 9;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="settings")
     */
    private User $user;

    /**
     * @ORM\Column(type="integer", options={"default": 1})
     */
    private int $buttonStyleId = self::BUTTON_STYLE_DEFAULT;

    /**
     * @ORM\Column(type="integer", options={"default": 1})
     */
    private int $backgroundStyleId = self::BACKGROUND_STYLE_DEFAULT;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getButtonStyleId(): int
    {
        return $this->buttonStyleId;
    }

    public function setButtonStyleId(int $buttonStyleId): void
    {
        $this->buttonStyleId = $buttonStyleId;
    }

    public function getBackgroundStyleId(): int
    {
        return $this->backgroundStyleId;
    }

    public function setBackgroundStyleId(int $backgroundStyleId): void
    {
        $this->backgroundStyleId = $backgroundStyleId;
    }
}
