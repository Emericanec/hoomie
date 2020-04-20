<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Builder\GoodsDataTableBuilder;
use App\Entity\Goods;
use App\Entity\User;
use App\Form\GoodsForm;
use App\Processor\GoodsFormProcessor;
use App\Repository\GoodsRepository;
use App\Widget\DataTable\DataTableWidget;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GoodsController extends AbstractAppController
{
    /**
     * @Route("/app/goods", name="app_goods_list")
     * @param GoodsRepository $goodsRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function listGoods(GoodsRepository $goodsRepository): Response
    {
        $goods = $goodsRepository->findByUserId($this->getCurrentUser()->getId());
        $widget = new DataTableWidget($this->getTwig(), (new GoodsDataTableBuilder())->getBuilder(), $goods);

        return $this->render('app/goods/list.html.twig', [
            'widget' => $widget->render()
        ]);
    }

    /**
     * @Route("/app/goods/add", name="app_goods_add")
     * @param GoodsRepository $goodsRepository
     * @param Request $request
     * @return Response
     */
    public function add(GoodsRepository $goodsRepository, Request $request): Response
    {
        return $this->formHandle($goodsRepository, $request);
    }

    /**
     * @Route("/app/goods/edit/{id}", name="app_goods_edit")
     * @param GoodsRepository $goodsRepository
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(GoodsRepository $goodsRepository, Request $request, int $id): Response
    {
        return $this->formHandle($goodsRepository, $request, $id);
    }

    /**
     * @Route("/app/goods/delete/{id}", name="app_goods_delete")
     * @param GoodsRepository $goodsRepository
     * @param int $id
     * @return Response
     */
    public function delete(GoodsRepository $goodsRepository, int $id): Response
    {
        /** @var Goods $model */
        $model = $goodsRepository->findOneBy(['id' => $id]);
        $this->checkPermission($model);
        $model->setStatus(Goods::STATUS_DELETED);
        $em = $this->getDoctrine()->getManager();
        $em->persist($model);
        $em->flush();
        return $this->redirectToRoute('app_goods_list');
    }

    private function formHandle(GoodsRepository $goodsRepository, Request $request, int $id = null): Response
    {
        if (null !== $id) {
            $model = $goodsRepository->findOneBy(['id' => $id]);
            $this->checkPermission($model);
            $form = new GoodsForm($this->getCurrentUser(), $request, $model);
        } else {
            $model = new Goods();
            $form = new GoodsForm($this->getCurrentUser(), $request);
        }

        if ($request->isMethod(Request::METHOD_POST)) {
            $processor = new GoodsFormProcessor($this->getDoctrine()->getManager(), $form, $model);
            if (null !== $processor->process()) {
                return $this->redirectToRoute('app_goods_list');
            }
        }

        return $this->render('app/goods/form.html.twig', [
            'form' => $form,
            'files' => []
        ]);
    }

    /**
     * @param Goods|null $model
     * @throws NotFoundHttpException
     */
    private function checkPermission(?Goods $model): void
    {
        /** @var User $user */
        $user = $this->getUser();
        if (null === $model || $model->getUser()->getId() !== $user->getId()) {
            throw new NotFoundHttpException('Not found');
        }
    }
}