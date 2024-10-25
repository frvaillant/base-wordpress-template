<?php
namespace App\Base\Router\Traits;

trait PlurialTrait
{

    public function plurial(array $array, string $plurial = 's', string $singular = '')
    {
        return count($array) > 1 ? $plurial : $singular;
    }

}