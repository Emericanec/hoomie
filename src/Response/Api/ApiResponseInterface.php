<?php

declare(strict_types=1);

namespace App\Response\Api;

interface ApiResponseInterface
{
    public function toArray(): array;
}