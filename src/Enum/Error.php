<?php

declare(strict_types=1);

namespace App\Enum;

class Error
{
    public const AUTH_INSTAGRAM_LOGIN_ERROR = 'auth:instagram_login_error';

    public const UPLOAD_FILE_PROCESSING_ERROR = 'upload:file_processing_error';

    public const APP_USER_NOT_FOUND = 'app:user_not_found';
}