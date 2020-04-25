<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Link;
use App\Helper\JsonRequest;
use App\Repository\PageRepository;
use App\Response\Api\ApiResponse;
use App\Response\Api\PermissionDeniedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PageController extends AbstractApiController
{
    /**
     * @Route("/api/page/{id}/getLayout")
     * @param SerializerInterface $serializer
     * @param PageRepository $pageRepository
     * @param int $id
     * @return Response
     */
    public function getLayout(SerializerInterface $serializer, PageRepository $pageRepository, int $id): Response
    {
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $data = $serializer->serialize($page->getLinks(), 'json', ['groups' => ['default']]);
        return new Response($data);
    }

    /**
     * @Route("/api/page/{id}/addLink")
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param int $id
     * @return Response
     */
    public function addPageLink(SerializerInterface $serializer, Request $request, PageRepository $pageRepository, int $id): Response
    {
        $jsonRequest = new JsonRequest($request);
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $model = new Link();
        $model->setTitle($jsonRequest->getString('title'));
        $model->setPage($page);
        $model->setRawSettings('{}');

        $em = $this->getDoctrine()->getManager();
        $em->persist($model);
        $em->flush();

        $data = $serializer->serialize([
            ApiResponse::PARAM_RESULT => true,
            'link' => $model
        ], 'json', ['groups' => ['default']]);

        return new Response($data);
    }
}