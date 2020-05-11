<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\LinkRepository;
use App\Response\EarlyResponse;
use App\Service\Analytics\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/api/statistics/{linkId}")
     * @param LinkRepository $linkRepository
     * @param int $linkId
     * @return EarlyResponse
     */
    public function visitLink(LinkRepository $linkRepository, int $linkId): EarlyResponse
    {
        $response = new EarlyResponse();
        $response->setTerminateCallback(static function() use ($linkRepository, $linkId) {
            $link = $linkRepository->find($linkId);

            if (null === $link) {
                throw new NotFoundHttpException();
            }

            Logger::logVisitLink($link->getPage()->getId(), $link->getId());
        });

        return $response;
    }
}
