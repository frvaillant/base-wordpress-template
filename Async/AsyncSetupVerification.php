<?php

namespace App\Async;


class AsyncSetupVerification
{

    private $wpdb;
    private string $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = AsyncConfig::ASYNC_TABLE_NAME;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        $query = $this->wpdb->prepare( 'SHOW TABLES LIKE %s', $this->wpdb->esc_like( $this->table_name ) );
        if ( ! $this->wpdb->get_var( $query ) == $this->table_name ) {
            return false;
        }
        return true;
    }

    public function create()
    {
        $query = 'CREATE TABLE `%s` (
              `id` int NOT NULL,
              `action` json NOT NULL,
              `created_at` datetime NOT NULL,
              `executed_at` datetime DEFAULT NULL
            );';

        $query2 = 'ALTER TABLE `%s` ADD PRIMARY KEY (`id`);';
        $query3 = 'ALTER TABLE `%s` MODIFY `id` int NOT NULL AUTO_INCREMENT;';

        $this->wpdb->query(sprintf($query, $this->table_name));
        $this->wpdb->query(sprintf($query2, $this->table_name));
        $this->wpdb->query(sprintf($query3, $this->table_name));
    }

}