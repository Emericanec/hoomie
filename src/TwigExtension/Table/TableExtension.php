<?php

declare(strict_types=1);

namespace App\TwigExtension\Table;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TableExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('table', [$this, 'renderTable'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ]),
        ];
    }

    /**
     * @param Environment $environment
     * @param array $data
     * @param array $options
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTable(Environment $environment, array $data, array $options): string
    {
        return $environment->render('_extension/table/table.html.twig', [
            'data' => $data,
            'options' => $options
        ]);
    }
}