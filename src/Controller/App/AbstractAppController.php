<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Controller\AppControllerInterface;
use App\Entity\User;
use App\Enum\Error;
use App\Traits\RollBarTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractAppController extends AbstractController implements AppControllerInterface
{
    use RollBarTrait;

    public ?User $user = null;

    public function before(): ?Response
    {
        if (!$this->isGranted(User::ROLE)) {
            return $this->redirect('/login');
        }

        $user = $this->getUser();

        if (!$user instanceof User) {
            self::logger()->error(Error::APP_USER_NOT_FOUND, ['user' => $this->user]);
        }

        $this->user = $user;

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