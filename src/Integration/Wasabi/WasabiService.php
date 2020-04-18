<?php

declare(strict_types=1);

namespace App\Integration\Wasabi;

class WasabiService
{
    public static function getClient(): FileUploadClient
    {
        return new FileUploadClient();
    }
}