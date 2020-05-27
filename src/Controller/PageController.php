<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Node;
use App\Entity\Node\LinkNodeSettings;
use App\Entity\Page;
use App\Entity\Setting;
use App\Manager\LinkStyleManager;
use App\Manager\ThemeManager;
use App\Repository\UserRepository;
use App\Service\Analytics\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/{username}", name="p_index")
     * @param UserRepository $userRepository
     * @param string $username
     * @return Response
     */
    public function index(UserRepository $userRepository, string $username): Response
    {
        $user = $userRepository->findByNickname($username);
        if (null === $user) {
            throw $this->createNotFoundException();
        }

        /** @var Page $page */
        $page = current($user->getPages()->getValues());
        $setting = $user->getSettings();

        $links = [];
        foreach ($page->getSortedNodes() as $node) {
            $links[] = $this->getLinkStyleManager($node, $setting);
        }

        Logger::logVisitPage($page->getUser()->getId(), $page->getId());

        return $this->render('page/main.html.twig', [
            'links' => $links,
            'user' => $user,
            'backGroundStyle' => (new ThemeManager($setting))->getBackgroundStyle(),
        ]);
    }

    private function getLinkStyleManager(Node $node, Setting $setting): LinkStyleManager
    {
        $settings = LinkNodeSettings::fromJson($node->getJsonSettings());
        return new LinkStyleManager(
            $setting->getButtonStyleId(),
            $node->getId(),
            $settings->getTitle(),
            $settings->getTextColor(),
            $settings->getBackgroundColor(),
            $settings->getIcon(),
            $settings->getUrl()
        );
    }
}