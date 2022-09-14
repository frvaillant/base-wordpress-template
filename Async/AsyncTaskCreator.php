<?php

namespace App\Async;

class AsyncTaskCreator implements AsyncTaskCreatorInterface
{

    private $className;

    private $params;

    public function __construct()
    {
        $verificator = new AsyncSetupVerification();
        if(!$verificator->exists()) {
            $verificator->create();
        }
    }


    public function createTask(string $className, array $params): void
    {
        $this->className = explode('\\', $className);
        $this->params    = $params;
        $this->store();
    }

    /**
     *
     */
    public function store()
    {
        global $wpdb;
        $data = ['className' => $this->className, 'params' => $this->params];
        $action = wp_json_encode($data);

        $today = new \DateTime('now');
        $now = $today->format('Y-m-d H:i:s');

        $wpdb->insert(AsyncConfig::ASYNC_TABLE_NAME, [
            'action' => $action,
            'created_at' => $now
        ], [
            '%s',
            '%s'
        ]);

    }
}