<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\Goods;
use App\Widget\DataTable\ActionsColumn;
use App\Widget\DataTable\Column;
use App\Widget\DataTable\ColumnBuilder;

class GoodsDataTableBuilder
{
    public function getBuilder(): ColumnBuilder
    {
        $builder = new ColumnBuilder();
        $builder->addColumn(new Column('ID', fn(Goods $goods): int => $goods->getId()));
        $builder->addColumn(new Column('Title', fn(Goods $goods): string => $goods->getTitle()));
        $builder->addColumn(new Column('Price', fn(Goods $goods): float => $goods->getPrice()));
        $builder->addColumn($this->getActionsColumn());
        return $builder;
    }

    private function getActionsColumn(): ActionsColumn
    {
        $actionsColumn = new ActionsColumn('Actions');
        $actionsColumn->setUrlPrefix('goods/');
        $actionsColumn->setStyle('width: 100px;');
        $actionsColumn->setFormat('edit delete');
        return $actionsColumn;
    }
}