<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Module\Analytics\DTO\LinkStatisticsCollectionDTO;
use App\Service\Analytics\Entity\Page as PageStats;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsController extends AbstractAppController
{
    /**
     * @Route("/app/analytics", name="app_analytics")
     * @return Response
     */
    public function index(): Response
    {
        $page = $this->getCurrentUser()->getMainPage();
        $pageStats = new PageStats($page);
        $statistics = new LinkStatisticsCollectionDTO($page->getSortedLinks());

        return $this->render('app/analytics/index.html.twig', [
            'userId' => $this->getCurrentUser()->getId(),
            'links' => $statistics->getCollection(),
            'totalViews' => $pageStats->getTotalViews(),
            'totalClicks' => $pageStats->getTotalClicks(),
        ]);
    }
}
