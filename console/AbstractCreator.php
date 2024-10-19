<?php


abstract class AbstractCreator
{
    protected $success;
    protected $error;

    public function getMessage()
    {
        if ($this->success) {
            return ['status' => 'success', 'message' => $this->success];
        }
        return ['status' => 'error', 'message' => $this->success];
    }

    protected function createDir($path)
    {
        if(!is_dir($path)) {
            try {
                mkdir($path);
            } catch (\Exception $e) {
                return false;
            }
        }
    }

}
