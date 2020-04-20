<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\Page;
use App\Widget\DataTable\ActionsColumn;
use App\Widget\DataTable\Column;
use App\Widget\DataTable\ColumnBuilder;

class PagesDataTableBuilder
{
    public function getBuilder(): ColumnBuilder
    {
        $builder = new ColumnBuilder();
        $builder->addColumn(new Column('Title', fn(Page $goods): string => $goods->getTitle()));
        $builder->addColumn($this->getActionsColumn());
        return $builder;
    }

    private function getActionsColumn(): ActionsColumn
    {
        $actionsColumn = new ActionsColumn('Actions');
        $actionsColumn->setUrlPrefix('pages/');
        $actionsColumn->setStyle('width: 100px;');
        $actionsColumn->setActions([
            ActionsColumn::ACTION_EDIT,
            '<div class="dropdown-divider"></div>',
            ActionsColumn::ACTION_DELETE
        ]);
        return $actionsColumn;
    }
}