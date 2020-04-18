<?php

declare(strict_types=1);

namespace App\Response;

use Aws\Result;

class UploadFileResponse
{
    private Result $result;

    private string $key;

    private string $fileName;

    public function __construct(string $fileName, string $key, Result $result)
    {
        $this->key = $key;
        $this->result = $result;
        $this->fileName = $fileName;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
}