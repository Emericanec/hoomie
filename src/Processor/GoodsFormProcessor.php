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

    public function __construct(ObjectManager $objectManager, GoodsForm $goodsForm)
    {
        $this->objectManager = $objectManager;
        $this->goodsForm = $goodsForm;
    }

    public function process(): ?Goods
    {
        if (!$this->goodsForm->validation()) {
            return null;
        }

        $model = new Goods();
        $model->setUser($this->goodsForm->getUser());
        $model->setTitle($this->goodsForm->getTitle());
        $model->setDescription($this->goodsForm->getDescription());

        $this->objectManager->persist($model);
        $this->objectManager->flush();

        return $model;
    }
}