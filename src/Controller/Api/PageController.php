<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Node;
use App\Entity\Setting;
use App\Helper\JsonRequest;
use App\Manager\ThemeManager;
use App\Module\Api\Processor\LinkFormProcessor;
use App\Module\Api\Request\LinkFormRequest;
use App\Repository\NodeRepository;
use App\Repository\PageRepository;
use App\Repository\SettingRepository;
use App\Response\Api\ApiResponse;
use App\Response\Api\PermissionDeniedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractApiController
{
    /**
     * @Route("/api/page/{id}/getLayout")
     * @param PageRepository $pageRepository
     * @param SettingRepository $settingRepository
     * @param int $id
     * @return Response
     */
    public function getLayout(PageRepository $pageRepository, SettingRepository $settingRepository, int $id): Response
    {
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $setting = $settingRepository->findOneByUserId($this->getCurrentUser()->getId());
        if (null === $setting) {
            $setting = new Setting();
            $setting->setUser($this->getCurrentUser());
        }

        $manager = new ThemeManager($setting);

        return $this->jsonResponse([
            'button_style' => $manager->getButtonStyle(),
            'background_style' => $manager->getBackgroundStyle(),
            'links' => [],
            'nodes' => $page->getSortedNodes(),
        ]);
    }

    /**
     * @Route("/api/page/{id}/saveSort")
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param NodeRepository $nodeRepository
     * @param int $id
     * @return Response
     */
    public function saveSort(Request $request, PageRepository $pageRepository, NodeRepository $nodeRepository, int $id): Response
    {
        $jsonRequest = new JsonRequest($request);
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        foreach ($jsonRequest->getData() as $sort => $nodeId) {
            $nodeRepository->updateSort($nodeId, $sort);
        }

        return $this->jsonResponse([ApiResponse::PARAM_RESULT => true]);
    }

    /**
     * @Route("/api/page/{id}/deleteNode/{nodeId}")
     * @param PageRepository $pageRepository
     * @param NodeRepository $nodeRepository
     * @param int $id
     * @param int $nodeId
     * @return Response
     */
    public function deletePageNode(PageRepository $pageRepository, NodeRepository $nodeRepository, int $id, int $nodeId): Response
    {
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $node = $nodeRepository->findOneBy(['id' => $nodeId, 'page' => $page->getId()]);

        if (null === $node) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $node->setStatus(Node::STATUS_DELETED);

        $em = $this->getDoctrine()->getManager();
        $em->persist($node);
        $em->flush();


        return $this->jsonResponse([ApiResponse::PARAM_RESULT => true]);
    }

    /**
     * @Route("/api/page/{id}/addLink")
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param NodeRepository $nodeRepository
     * @param int $id
     * @return Response
     */
    public function addPageLink(Request $request, PageRepository $pageRepository, NodeRepository $nodeRepository, int $id): Response
    {
        return $this->handle($request, $pageRepository, $nodeRepository, $id);
    }

    /**
     * @Route("/api/page/{id}/editLink/{nodeId}")
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param NodeRepository $nodeRepository
     * @param int $id
     * @param int $nodeId
     * @return Response
     */
    public function editPageLink(Request $request, PageRepository $pageRepository, NodeRepository $nodeRepository, int $id, int $nodeId): Response
    {
        return $this->handle($request, $pageRepository, $nodeRepository, $id, $nodeId);
    }

    public function handle(Request $request, PageRepository $pageRepository, NodeRepository $nodeRepository, int $id, int $nodeId = null): Response
    {
        try {
            $linkRequest = new LinkFormRequest($request, $this->getCurrentUser(), $id, $nodeId);
            $linkRequest->handle($pageRepository, $nodeRepository);
        } catch (AccessDeniedHttpException $exception) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $processor = new LinkFormProcessor($linkRequest, $this->getDoctrine()->getManager());
        $model = $processor->process();

        return $this->jsonResponse([
            ApiResponse::PARAM_RESULT => true,
            'node' => $model
        ]);
    }
}