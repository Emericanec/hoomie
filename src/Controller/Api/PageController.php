<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Link;
use App\Helper\JsonRequest;
use App\Repository\LinkRepository;
use App\Repository\PageRepository;
use App\Response\Api\ApiResponse;
use App\Response\Api\PermissionDeniedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractApiController
{
    /**
     * @Route("/api/page/{id}/getLayout")
     * @param PageRepository $pageRepository
     * @param int $id
     * @return Response
     */
    public function getLayout(PageRepository $pageRepository, int $id): Response
    {
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        return $this->jsonResponse($page->getSortedLinks());
    }

    /**
     * @Route("/api/page/{id}/saveSort")
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param LinkRepository $linkRepository
     * @param int $id
     * @return Response
     */
    public function saveSort(Request $request, PageRepository $pageRepository, LinkRepository $linkRepository, int $id): Response
    {
        $jsonRequest = new JsonRequest($request);
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        foreach ($jsonRequest->getData() as $sort => $linkId) {
            $linkRepository->updateSort($linkId, $sort);
        }

        return $this->jsonResponse([ApiResponse::PARAM_RESULT => true]);
    }

    /**
     * @Route("/api/page/{id}/addLink")
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param LinkRepository $linkRepository
     * @param int $id
     * @return Response
     */
    public function addPageLink(Request $request, PageRepository $pageRepository, LinkRepository $linkRepository, int $id): Response
    {
        return $this->handle($request, $pageRepository, $linkRepository, $id);
    }

    /**
     * @Route("/api/page/{id}/editLink/{linkId}")
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param LinkRepository $linkRepository
     * @param int $id
     * @param int $linkId
     * @return Response
     */
    public function editPageLink(Request $request, PageRepository $pageRepository, LinkRepository $linkRepository, int $id, int $linkId): Response
    {
        return $this->handle($request, $pageRepository, $linkRepository, $id, $linkId);
    }

    public function handle(Request $request, PageRepository $pageRepository, LinkRepository $linkRepository, int $id, int $linkId = null): Response
    {
        $jsonRequest = new JsonRequest($request);
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        if (null === $linkId) {
            $model = new Link();
        } else {
            $model = $linkRepository->find($linkId);
            if (null === $model || $model->getPage()->getId() !== $page->getId()) {
                return $this->json((new PermissionDeniedResponse())->toArray());
            }
        }

        $model->setTitle($jsonRequest->getString('title'));
        $model->setPage($page);

        $settings = [
            'url' => $jsonRequest->getString('url', ''),
            'backgroundColor' => $jsonRequest->getString('backgroundColor', '#007bff'),
            'textColor' => $jsonRequest->getString('textColor', '#ffffff'),
        ];

        $model->setRawSettings(json_encode($settings, JSON_THROW_ON_ERROR, 512));

        $em = $this->getDoctrine()->getManager();
        $em->persist($model);
        $em->flush();

        return $this->jsonResponse([
            ApiResponse::PARAM_RESULT => true,
            'link' => $model
        ]);
    }
}