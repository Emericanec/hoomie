<?php

declare(strict_types=1);

namespace App\Controller;

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
        // @todo уродство.
        $user = $userRepository->findByNickname($username);
        if (null === $user) {
            throw $this->createNotFoundException();
        }

        /** @var Page $page */
        $page = current($user->getPages()->getValues());
        if (null === $page) {
            throw $this->createNotFoundException();
        }

        $setting = $user->getSettings();

        if (null === $setting) {
            $setting = new Setting();
            $setting->setBackgroundStyleId(Setting::BACKGROUND_STYLE_DEFAULT);
            $setting->setButtonStyleId(Setting::BUTTON_STYLE_DEFAULT);
        }

        $backGroundStyle = (new ThemeManager($setting))->getBackgroundStyle();

        $links = [];
        foreach ($page->getSortedLinks() as $link) {
            $settings = $link->getSettings();
            $backgroundColor = $settings['backgroundColor'] ?? '#ffffff';
            $textColor = $settings['textColor'] ?? '#000000';
            $icon = $settings['icon'] ?? '';
            $links[] = new LinkStyleManager($setting->getButtonStyleId(), $link->getTitle(), $textColor, $backgroundColor, $icon);
        }

        //Logger::logVisitPage($page->getUser()->getId(), $page->getId());

        return $this->render('page/main.html.twig', [
            'links' => $links,
            'user' => $user,
            'backGroundStyle' => $backGroundStyle,
        ]);
    }
}