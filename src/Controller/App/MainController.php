<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractAppController
{
    /**
     * @Route("/app/main", name="app_main")
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function main(PageRepository $pageRepository): Response
    {
        $page = $pageRepository->findOneBy(['user' => $this->getCurrentUser()->getId()]);

        if (!$page) {
            throw new NotFoundHttpException('Not found');
        }

        return $this->render('app/pages/edit.html.twig', [
            'page' => $page
        ]);
    }
}