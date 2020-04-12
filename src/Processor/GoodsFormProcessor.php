<?php

declare(strict_types=1);

namespace App\Processor;

use App\Entity\Goods;
use App\Form\GoodsForm;
use Doctrine\Persistence\ObjectManager;

class GoodsFormProcessor
{
    private ObjectManager $objectManager;

    private GoodsForm $goodsForm;

    private Goods $model;

    public function __construct(ObjectManager $objectManager, GoodsForm $goodsForm, Goods $model)
    {
        $this->objectManager = $objectManager;
        $this->goodsForm = $goodsForm;
        $this->model = $model;
    }

    public function process(): ?Goods
    {
        if (!$this->goodsForm->validation()) {
            return null;
        }

        $this->model->setUser($this->goodsForm->getUser());
        $this->model->setTitle($this->goodsForm->getTitle());
        $this->model->setDescription($this->goodsForm->getDescription());
        $this->model->setUrl($this->goodsForm->getUrl());
        $this->model->setPrice($this->goodsForm->getPrice());

        $this->objectManager->persist($this->model);
        $this->objectManager->flush();

        return $this->model;
    }
}