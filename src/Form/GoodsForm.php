<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class GoodsForm
{
    private ?string $title = null;

    private ?string $description = null;

    private User $user;

    private Request $request;

    public function __construct(User $user, Request $request)
    {
        $this->user = $user;
        $this->title = $request->get('title');
        $this->description = $request->get('description');
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
}