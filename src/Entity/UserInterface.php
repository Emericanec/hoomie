<?php

declare(strict_types=1);

namespace App\Entity;

interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface
{
    public function getId(): ?int;

    public function getEmail(): string;

    public function setEmail(string $email): self;

    public function setPassword(string $password): self;
}