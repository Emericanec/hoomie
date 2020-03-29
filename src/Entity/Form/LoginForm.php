<?php

declare(strict_types=1);

namespace App\Entity\Form;

use Symfony\Component\Validator\Constraints as Assert;

class LoginForm
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected ?string $email;

    /**
     * @Assert\NotBlank
     */
    protected ?string $password;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email ?? '';
    }

    /**
     * @param string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    /**
     * @param string $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}