<?php

namespace App\Response\Api;

abstract class ApiResponse implements ApiResponseInterface
{
    public const PARAM_RESULT = 'result';
    public const PARAM_ERROR_MESSAGE = 'message';
}