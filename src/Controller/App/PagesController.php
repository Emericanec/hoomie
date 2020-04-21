<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Builder\PagesDataTableBuilder;
use App\Repository\PageRepository;
use App\Widget\DataTable\DataTableWidget;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PagesController extends AbstractAppController
{
    /**
     * @Route("/app/page", name="app_page_list")
     * @param PageRepository $pageRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function listPages(PageRepository $pageRepository): Response
    {
        $pages = $pageRepository->findByUserId($this->getCurrentUser()->getId());
        $widget = new DataTableWidget($this->getTwig(), (new PagesDataTableBuilder())->getBuilder(), $pages);

        return $this->render('app/pages/list.html.twig', [
            'widget' => $widget->render()
        ]);
    }

    /**
     * @Route("/app/page/edit/{id}", name="app_page_edit")
     * @param PageRepository $pageRepository
     * @param int $id
     * @return Response
     */
    public function edit(PageRepository $pageRepository, int $id): Response
    {
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (!$page) {
            throw new NotFoundHttpException('Not found');
        }

        return $this->render('app/pages/edit.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * @Route("/app/page/add", name="app_page_add")
     */
    public function add(): Response
    {
        return $this->render('app/pages/a.html.twig');
    }

    /**
     * @Route("/app/page/create_main_page", name="app_page_create_main")
     * @param PageRepository $pageRepository
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createMainPage(PageRepository $pageRepository): Response
    {
        $page = $pageRepository->createMainPage($this->getCurrentUser());

        if (!$page) {
            return $this->redirectToRoute('app_page_list');
        }

        return $this->redirectToRoute('app_page_edit', ['id' => $page->getId()]);
    }
}