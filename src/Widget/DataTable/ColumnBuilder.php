<?php


namespace App\Widget\DataTable;


class ColumnBuilder
{
    /**
     * @var Column[]
     */
    private array $columns = [];

    public function addColumn(Column $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}