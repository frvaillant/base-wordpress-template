<?php

namespace App\Async;

interface AsyncTaskInterface
{

    public function execute(): bool;
    //public function setParameters;

}