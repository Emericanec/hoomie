<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\PageRepository;
use App\Response\Api\PermissionDeniedResponse;
use App\Response\Api\StatisticResponse;
use App\Service\Analytics\Entity\Page as PageAnalytics;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsController extends AbstractApiController
{
    /**
     * @Route("/api/analytics/all")
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function all(PageRepository $pageRepository): Response
    {
        $page = $pageRepository->findOneByUserId($this->getCurrentUser()->getId());
        if (null === $page) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $pageAnalytics = new PageAnalytics($page);
        $statistics = $pageAnalytics->getPageViewsByLast7Days();
        $response = new StatisticResponse($statistics);

        return $this->jsonResponse($response->toArray());
    }
}
