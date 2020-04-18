<?php

declare(strict_types=1);

namespace App\Helper;

class Convert
{
    public static function objectToArray(object $object): array
    {
        return json_decode(json_encode($object, JSON_THROW_ON_ERROR, 512), true, 512, JSON_THROW_ON_ERROR);
    }
}