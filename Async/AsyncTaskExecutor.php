<?php

namespace App\Async;

class AsyncTaskExecutor
{

    private string $className;
    /**
     * @var mixed
     */
    private $parameters;

    public function setTask(Object $task)
    {
        $task = (array)$task;
        $action = json_decode($task['action'], true);
        $this->className = implode('\\', $action['className']);
        $this->parameters = (array)$action['params'];
    }

    public function execute()
    {
        $task = new $this->className($this->parameters);
        $task->execute();
    }

}