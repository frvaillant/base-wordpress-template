<?php

namespace App\Model;

final class EntityProvider
{

    /**
     * @param $queryResults
     * @param $className
     * @return array
     */
    public function provide(array $queryResults, string $className): array
    {
        $results = [];
        foreach ($queryResults as $result) {
            $results[] = new $className($result->ID);
        }
        return $results;
    }
}
