<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Link;
use App\Entity\Setting;
use App\Helper\JsonRequest;
use App\Manager\ThemeManager;
use App\Module\Api\Processor\LinkFormProcessor;
use App\Module\Api\Request\LinkFormRequest;
use App\Repository\LinkRepository;
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
            'links' => $page->getSortedLinks()
        ]);
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
     * @Route("/api/page/{id}/deleteLink/{linkId}")
     * @param PageRepository $pageRepository
     * @param LinkRepository $linkRepository
     * @param int $id
     * @param int $linkId
     * @return Response
     */
    public function deletePageLink(PageRepository $pageRepository, LinkRepository $linkRepository, int $id, int $linkId): Response
    {
        $page = $pageRepository->findOneBy(['id' => $id, 'user' => $this->getCurrentUser()->getId()]);

        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $link = $linkRepository->findOneBy(['id' => $linkId, 'page' => $page->getId()]);

        if (null === $link) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $link->setStatus(Link::STATUS_DELETED);

        $em = $this->getDoctrine()->getManager();
        $em->persist($link);
        $em->flush();


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
        try {
            $linkRequest = new LinkFormRequest($request, $this->getCurrentUser(), $id, $linkId);
            $linkRequest->handle($pageRepository, $linkRepository);
        } catch (AccessDeniedHttpException $exception) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $processor = new LinkFormProcessor($linkRequest, $this->getDoctrine()->getManager());
        $model = $processor->process();

        return $this->jsonResponse([
            ApiResponse::PARAM_RESULT => true,
            'link' => $model
        ]);
    }
}