<?php

declare(strict_types=1);

namespace App\Response\Api;

class PermissionDeniedResponse extends ApiResponse
{
    public function toArray(): array
    {
        return [
            self::PARAM_RESULT => false,
            self::PARAM_ERROR_MESSAGE => 'Permission denied',
        ];
    }
}