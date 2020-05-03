<?php

declare(strict_types=1);

namespace App\Controller\App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemesController extends AbstractAppController
{
    /**
     * @Route("/app/themes", name="app_themes")
     */
    public function index(): Response
    {
        return $this->render('app/themes/index.html.twig');
    }
}
