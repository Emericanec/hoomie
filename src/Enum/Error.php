<?php

declare(strict_types=1);

namespace App\Enum;

class Error
{
    public const AUTH_INSTAGRAM_LOGIN_ERROR = 'auth:instagram_login_error';

    public const UPLOAD_FILE_PROCESSING_ERROR = 'upload:file_processing_error';

    public const APP_USER_NOT_FOUND = 'app:user_not_found';

    public const ELASTIC_SEARCH_EVENT_LOG_VISIT_PAGE_ERROR = 'elasticSearch:event_log_visit_page_error';
    public const ELASTIC_SEARCH_EVENT_LOG_VISIT_LINK_ERROR = 'elasticSearch:event_log_visit_link_error';
}