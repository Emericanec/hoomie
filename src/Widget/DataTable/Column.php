<?php

declare(strict_types=1);

namespace App\Widget\DataTable;

use Closure;

class Column
{
    private string $title;

    private ?Closure $closure = null;

    private string $style = '';

    public function __construct(string $title, Closure $closure = null)
    {
        $this->title = $title;
        $this->closure = $closure;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getClosure(): Closure
    {
        return $this->closure;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function setStyle(string $style): void
    {
        $this->style = $style;
    }
}