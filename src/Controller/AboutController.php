<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/about/privacy", name="privacy")
     */
    public function privacy(): Response
    {
        return $this->render('about/privacy.html.twig');
    }

    /**
     * @Route("/about/terms", name="terms")
     */
    public function terms(): Response
    {
        return $this->render('about/terms.html.twig');
    }
}
