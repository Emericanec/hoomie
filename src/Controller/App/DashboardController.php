<?php

declare(strict_types=1);

namespace App\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/app/dashboard", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('app/dashboard/dashboard.html.twig');
    }
}