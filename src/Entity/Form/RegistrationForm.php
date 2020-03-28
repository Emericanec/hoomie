<?php

declare(strict_types=1);

namespace App\Entity\Form;
use Symfony\Component\Validator\Constraints;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\UniqueEntity;

/**
 * Class RegistrationForm
 * @package App\Entity\Form
 * @UniqueEntity(
 *     entityClass="App\Entity\User",
 *     repositoryMethod="findOneBy",
 *     fields={"email"},
 *     message="This email is already registered"
 * )
 */
class RegistrationForm implements RegistrationFormInterface
{
    /**
     * @ORM\Id()
     * @Constraints\NotBlank
     * @Constraints\Email
     */
    protected ?string $email;

    /**
     * @Constraints\NotBlank
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