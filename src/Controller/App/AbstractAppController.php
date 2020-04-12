<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Controller\AppControllerInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractAppController extends AbstractController implements AppControllerInterface
{
    public ?User $user = null;

    public function before(): ?Response
    {
        if (!$this->isGranted(User::ROLE)) {
            return $this->redirect('/login');
        }

        /** @var User user */
        $this->user = $this->getUser();
        if (null === $this->user->getEmail()) {
            return $this->redirect('/step2');
        }

        return null;
    }

    public function getCurrentUser(): User
    {
        return $this->user;
    }

    public function getTwig(): Environment
    {
        /** @var Environment $environment */
        $environment = $this->get('twig');
        return $environment;
    }
}