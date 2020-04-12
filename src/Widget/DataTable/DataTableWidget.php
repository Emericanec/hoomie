<?php

declare(strict_types=1);

namespace App\Widget\DataTable;

use App\Widget\WidgetInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DataTableWidget implements WidgetInterface
{
    private Environment $environment;
    private ColumnBuilder $columnBuilder;
    private array $data;

    public function __construct(Environment $environment, ColumnBuilder $columnBuilder, array $data)
    {
        $this->environment = $environment;
        $this->columnBuilder = $columnBuilder;
        $this->data = $data;
    }

    /**
     * @param string $path
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $path): string
    {
        return $this->environment->render($path, [
            'data' => $this->data,
            'builder' => $this->columnBuilder
        ]);
    }
}