<?php


namespace App\Model;


use App\Entity\Section;

class EntityProvider
{

   public function provide($queryResults, $className)
    {
        $results = [];
        foreach ($queryResults as $result) {
            $results[] = new $className($result->ID);
        }
        return $results;

    }
}
