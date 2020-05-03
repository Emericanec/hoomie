<?php

declare(strict_types=1);

namespace App\Controller\App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsController extends AbstractAppController
{
    /**
     * @Route("/app/analytics", name="app_analytics")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/analytics/index.html.twig', [
            'userId' => $this->getCurrentUser()->getId()
        ]);
    }
}
