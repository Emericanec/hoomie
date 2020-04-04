<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

interface AppControllerInterface
{
    public function before(): ?Response;
}