<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Instagram\InstagramApi;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     * @throws Exception
     */
    public function index(): Response
    {
        $instagramApi = InstagramApi::getInstance();
        return $this->render('index/index.html.twig', [
            'loginUrl' => $instagramApi->getLoginUrl(),
            'name' => 'Kirill'
        ]);
    }
}