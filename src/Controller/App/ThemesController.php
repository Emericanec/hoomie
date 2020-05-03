<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\Setting;
use App\Manager\ThemeManager;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemesController extends AbstractAppController
{
    /**
     * @Route("/app/themes", name="app_themes")
     * @param SettingRepository $settingRepository
     * @param Request $request
     * @return Response
     */
    public function index(SettingRepository $settingRepository, Request $request): Response
    {
        $setting = $settingRepository->findOneByUserId($this->getCurrentUser()->getId());
        if (null === $setting) {
            $setting = new Setting();
            $setting->setUser($this->getCurrentUser());
        }

        if ($request->isMethod(Request::METHOD_POST)) {
            $setting->setButtonStyleId((int)$request->get('button_style', Setting::BUTTON_STYLE_DEFAULT));
            $setting->setButtonStyleId((int)$request->get('button_style', Setting::BUTTON_STYLE_DEFAULT));
            $setting->setBackgroundStyleId((int)$request->get('background_style', Setting::BACKGROUND_STYLE_DEFAULT));
            $em = $this->getDoctrine()->getManager();
            $em->persist($setting);
            $em->flush();
        }

        $manager = new ThemeManager($setting);

        return $this->render('app/themes/index.html.twig', [
            'buttonStyle' => $manager->getButtonStyle(),
            'backgroundStyle' => $manager->getBackgroundStyle(),
            'buttonStyles' => ThemeManager::getButtonStylesList(),
            'backgroundStyles' => ThemeManager::getBackgroundStylesList()
        ]);
    }
}
