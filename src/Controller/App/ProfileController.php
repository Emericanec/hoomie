<?php

declare(strict_types=1);

namespace App\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/app/profile", name="app_profile")
     */
    public function profile(): Response
    {
        return $this->render('app/profile/profile.html.twig');
    }
}