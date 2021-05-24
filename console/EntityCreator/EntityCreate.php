<?php

require_once ( __DIR__ . '/CustomPostCreator.php');
require_once (__DIR__ . '/../AbstractCreator.php');

class EntityCreate extends AbstractCreator
{

    const ENTITY_FOLDER = __DIR__ . '/../../Entity';
    const MODEL_FOLDER = __DIR__ . '/../../Model';
    private $arguments;
    private $name;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
        $names = explode(' ', $arguments['entity']);
        $this->name = implode('', array_map(function($value) {
            return ucfirst($value);
        }, $names));

    }


    private function createEntityFile()
    {
        $source = file_get_contents(__DIR__ . '/Files/entity.php');
        if ($source) {
            $source = str_replace('/*', '', $source);
            $source = str_replace('EntityName', ucfirst($this->name), $source);
            file_put_contents(self::ENTITY_FOLDER . '/' . ucfirst($this->name) . '.php', $source);
            return;
        }
        throw new \Exception('Entity source file is not found');
    }

    private function createRepository()
    {
        $source = file_get_contents(__DIR__ . '/Files/repository.php');
        if ($source) {
            $source = str_replace('/*', '', $source);
            $source = str_replace('EntityName', ucfirst($this->name), $source);
            $source = str_replace('EntityNameLower', strtolower($this->name), $source);
            file_put_contents(self::MODEL_FOLDER . '/' . ucfirst($this->name) . 'Repository.php', $source);
            return;
        }
        throw new \Exception('Repository source file is not found');

    }

    private function addCustomPost()
    {
        if (isset($this->arguments['singular']) && isset($this->arguments['plurial'])) {
            $customPost = new CustomPostCreator($this->name, $this->arguments['singular'], $this->arguments['plurial']);
            if(!$customPost) {
                throw new \Exception('Impossible d\'instancier un custom post pour ' . $this->name);
            }
            $customPost->addCustomPost();
        }

    }

    public function create()
    {
        try {
            $this->createEntityFile();
            $this->createRepository();
            $this->addCustomPost();
            $this->success = "Créations de l'entité et du repository réussies";
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
