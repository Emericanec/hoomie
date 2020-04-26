<?php

declare(strict_types=1);

namespace App\Controller\App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractAppController
{
    /**
     * @Route("/app/main", name="app_main")
     * @return Response
     */
    public function main(): Response
    {
        $pagesCount = count($this->getCurrentUser()->getPages());
        return $this->render('app/main.html.twig', [
            'pagesCount' => $pagesCount
        ]);
    }
}