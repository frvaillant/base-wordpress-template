<?php

namespace App\Async;

class AsyncTasksGetter
{

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function getTasksToExecute()
    {
        $query = 'SELECT * FROM ' . AsyncConfig::ASYNC_TABLE_NAME . ' WHERE executed_at IS NULL';
        return $this->wpdb->get_results($query);
    }

    public function updateTasks(array $tasksIds)
    {
        $today = new \DateTime('now');
        $now = $today->format('Y-m-d H:i:s');
        $query = 'UPDATE ' . AsyncConfig::ASYNC_TABLE_NAME . ' SET executed_at="' . $now . '" WHERE id=%d';
        foreach ($tasksIds as $taskId) {
            $this->wpdb->query(sprintf($query, (int)$taskId));
        }
    }

}