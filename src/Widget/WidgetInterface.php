<?php

declare(strict_types=1);

namespace App\Widget;

interface WidgetInterface
{
    public function render(string $path): string;
}