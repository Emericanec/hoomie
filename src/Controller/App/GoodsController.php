<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\Goods;
use App\Form\GoodsForm;
use App\Processor\GoodsFormProcessor;
use App\Repository\GoodsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoodsController extends AbstractAppController
{
    /**
     * @Route("/app/goods", name="app_goods_list")
     * @param GoodsRepository $goodsRepository
     * @return Response
     */
    public function listGoods(GoodsRepository $goodsRepository): Response
    {
        $goods = $goodsRepository->findByUserId($this->getCurrentUser()->getId());
        return $this->render('app/goods/list.html.twig', [
            'goods' => $goods
        ]);
    }

    /**
     * @Route("/app/goods/add", name="app_goods_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = new GoodsForm($this->getCurrentUser(), $request);

        if ($request->isMethod(Request::METHOD_POST)) {
            $goods = (new GoodsFormProcessor($this->getDoctrine()->getManager(), $form))->process();
            if (null !== $goods) {
                return $this->redirectToRoute('app_goods_list');
            }
        }

        return $this->render('app/goods/form.html.twig', [
            'form' => $form
        ]);
    }
}