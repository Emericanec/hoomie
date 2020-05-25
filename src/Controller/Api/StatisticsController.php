<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\NodeRepository;
use App\Response\EarlyResponse;
use App\Service\Analytics\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/api/statistics/{nodeId}")
     * @param NodeRepository $nodeRepository
     * @param int $nodeId
     * @return EarlyResponse
     */
    public function visitLink(NodeRepository $nodeRepository, int $nodeId): EarlyResponse
    {
        $response = new EarlyResponse();
        $response->setTerminateCallback(static function() use ($nodeRepository, $nodeId) {
            $node = $nodeRepository->find($nodeId);

            if (null === $node) {
                throw new NotFoundHttpException();
            }

            Logger::logVisitLink($node->getPage()->getId(), $node->getId());
        });

        return $response;
    }
}
