<?php

declare(strict_types=1);

namespace App\Helper;

use \Symfony\Component\HttpFoundation\Request as HttpRequest;

class JsonRequest
{
    private array $data;

    public function __construct(HttpRequest $request)
    {
        $this->data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getString(string $name, $default = null): ?string
    {
        return (string)$this->get($name, $default);
    }

    public function getInteger(string $name, $default = null): ?int
    {
        return (int)$this->get($name, $default);
    }

    public function getFloat(string $name, $default = null): ?float
    {
        return (float)$this->get($name, $default);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function get(string $name, $default = null)
    {
        return $this->data[$name] ?? $default;
    }
}