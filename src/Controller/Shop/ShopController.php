<?php

declare(strict_types=1);

namespace App\Controller\Shop;

use App\Entity\User;
use App\Repository\GoodsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop/{username}/{goodsId}", name="blog_list", requirements={"goodsId"="\d+", "nickname"="\w+"})
     * @param UserRepository $userRepository
     * @param GoodsRepository $goodsRepository
     * @param string $username
     * @param int $goodsId
     * @return Response
     */
    public function itemById(UserRepository $userRepository, GoodsRepository $goodsRepository, string $username, int $goodsId): Response
    {
        $user = $userRepository->findByNickname($username);
        if (null === $user) {
            $this->redirectToRoute('home');
        }

        $goods = $goodsRepository->findOneBy(['user' => $user->getId(), 'id' => $goodsId]);

        return $this->render('shop/item.html.twig', [
            'goods' => $goods
        ]);
    }
}