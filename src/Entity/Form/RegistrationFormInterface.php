<?php

declare(strict_types=1);

namespace App\Entity\Form;

interface RegistrationFormInterface
{
    public function getEmail(): string;

    public function setEmail(?string $email): void;

    public function getPassword(): string;

    public function setPassword(?string $password): void;
}