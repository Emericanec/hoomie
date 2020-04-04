<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Controller\AppControllerInterface;
use App\Entity\InstagramUserInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController implements AppControllerInterface
{
    public function before(): ?Response
    {
        if (!$this->isGranted(User::ROLE)) {
            return $this->redirect('/login');
        }

        /** @var InstagramUserInterface $user */
        $user = $this->getUser();
        if (null === $user->getEmail()) {
            return $this->redirect('/step2');
        }

        return null;
    }

    /**
     * @Route("/app/main", name="app_main")
     * @return Response
     */
    public function main(): Response
    {
        return $this->render('app/main.html.twig');
    }
}