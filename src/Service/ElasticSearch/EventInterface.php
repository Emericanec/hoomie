<?php

declare(strict_types=1);

namespace App\Service\ElasticSearch;

interface EventInterface
{
    public function getData(): array;
}
