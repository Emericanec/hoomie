<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PageRepository;
use App\Repository\UserRepository;
use App\Service\Analytics\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/{username}", name="p_index")
     * @param UserRepository $userRepository
     * @param PageRepository $pageRepository
     * @param string $username
     * @return Response
     */
    public function index(UserRepository $userRepository, PageRepository $pageRepository, string $username): Response
    {
        $user = $userRepository->findByNickname($username);
        if (null === $user) {
            throw new NotFoundHttpException('Not found');
        }

        $page = $pageRepository->findOneByUrl($user->getId(), '/');
        if (null === $page) {
            throw new NotFoundHttpException('Not found');
        }

        Logger::logVisitPage($page->getUser()->getId(), $page->getId());

        return $this->render('page/main.html.twig', [
            'user' => $user,
            'page' => $page
        ]);
    }
}