<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Page;
use App\Entity\Setting;
use App\Manager\LinkStyleManager;
use App\Manager\ThemeManager;
use App\Repository\UserRepository;
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
        foreach ($page->getSortedLinks() as $link) {
            $links[] = $this->getLinkStyleManager($link, $setting);
        }

        //Logger::logVisitPage($page->getUser()->getId(), $page->getId());

        return $this->render('page/main.html.twig', [
            'links' => $links,
            'user' => $user,
            'backGroundStyle' => (new ThemeManager($setting))->getBackgroundStyle(),
        ]);
    }

    private function getLinkStyleManager(Link $link, Setting $setting): LinkStyleManager
    {
        $settings = $link->getSettings();
        $backgroundColor = $settings[Link::SETTINGS_FIELD_BACKGROUND_COLOR] ?? '#ffffff';
        $textColor = $settings[Link::SETTINGS_FIELD_TEXT_COLOR] ?? '#000000';
        $icon = $settings[Link::SETTINGS_FIELD_ICON] ?? '';
        $url = $settings[Link::SETTINGS_FIELD_URL] ?? '';
        return new LinkStyleManager($setting->getButtonStyleId(), $link->getTitle(), $textColor, $backgroundColor, $icon, $url);
    }
}