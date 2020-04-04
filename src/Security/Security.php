<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\InstagramUserInterface;

class Security extends \Symfony\Component\Security\Core\Security
{
    public function getUser(): ?InstagramUserInterface
    {
        /** @var InstagramUserInterface $user */
        $user = parent::getUser();
        return $user;
    }
}