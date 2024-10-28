<?php

namespace App\Base\Console;

abstract class BaseCreator
{
    protected string $success;
    protected string $error;

    /**
     * @return array
     */
    public function getMessage(): array
    {
        if ($this->success) {
            return ['status' => 'success', 'message' => $this->success];
        }
        return ['status' => 'error', 'message' => $this->success];
    }

    /**
     * @param $path
     *
     * @return bool
     */
    protected function createDir($path): bool
    {
        if(!is_dir($path)) {
            try {
                mkdir($path);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }
}
