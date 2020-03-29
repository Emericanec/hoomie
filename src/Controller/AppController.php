<?php

declare(strict_types=1);

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends EasyAdminController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     * @return Response
     */
    public function dashboard(): Response
    {
        return $this->render('app/dashboard.html.twig');
    }
}