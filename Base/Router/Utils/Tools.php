<?php

namespace App\Base\Router\Utils;

final class Tools
{
    public static function plurial(array $array, string $plurial = 's', string $singular = '')
    {
        return count($array) > 1 ? $plurial : $singular;
    }
}
