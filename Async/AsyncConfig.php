<?php

namespace App\Async;

class AsyncConfig
{

    const ASYNC_TABLE_NAME = 'async_messages';

    /*
     * HELP
     */

    /*

    // CREATE A TASK
        $testTask = new AsyncTaskCreator();
        $testTask->createTask('App\\AsyncTasks\\SayText', ['text' => 'hello']);

    // GET TASKS TO EXECUTE
        $recuperator = new AsyncTasksGetter();
        $tasks = $recuperator->getTasksToExecute();

     // EXECUTE TASKS
        $executor = new AsyncTaskExecutor();

        $ids = [];
        foreach ($tasks as $task) {
            $executor->setTask($task);

            try {
                $executor->execute();
                $ids[] = $task->id;
            } catch(\exception $e) {

            }
        }

        // SET TASKS AS EXECUTED
        $recuperator->updateTasks($ids);
    */

}