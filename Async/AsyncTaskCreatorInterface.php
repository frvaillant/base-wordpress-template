<?php

namespace App\Async;


interface AsyncTaskCreatorInterface
{

    /**
     * @param string $className
     * @return void
     */
    public function createTask(string $className, array $params): void;

    public function store();

}