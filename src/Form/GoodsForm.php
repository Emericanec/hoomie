<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Goods;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class GoodsForm
{
    private ?string $title = null;

    private ?string $description = null;

    private ?string $url = null;

    private ?float $price = null;

    private User $user;

    public function __construct(User $user, Request $request, Goods $model = null)
    {
        $this->user = $user;
        $this->title = $request->get('title', $model ? $model->getTitle() : null);
        $this->description = $request->get('description', $model ? $model->getDescription() : null);
        $this->url = $request->get('url', $model ? $model->getUrl() : null);
        $this->price = (float)$request->get('price', $model ? $model->getPrice() : null);
    }

    public function validation(): bool
    {
        return !empty($this->getTitle());
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getPrice(): float
    {
        return $this->price ?? 0;
    }
}