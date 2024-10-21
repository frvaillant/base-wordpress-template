<?php

require_once (__DIR__ . '/../AbstractCreator.php');

class EntityCreate extends AbstractCreator
{

    const ENTITY_FOLDER = __DIR__ . '/../../Entity';

    const MODEL_FOLDER = __DIR__ . '/../../Model';
    /**
     * @var array
     */
    private array $arguments;
    /**
     * @var string
     */
    private string $name;

    const ANNOTATION = '
/**
 * @Entity(name="%s", singular="%s", plural="%s")
 */
';

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
        $names = explode(' ', $arguments['entity']);
        $this->name = implode('', array_map(function($value) {
            return ucfirst($value);
        }, $names));

    }


    /**
     * @return void
     * @throws Exception
     */
    private function createEntityFile(): void
    {
        $source = file_get_contents(__DIR__ . '/Files/entity.php');
        if ($source) {
            $source = str_replace('/*', '', $source);
            $source = str_replace('EntityName', ucfirst($this->name), $source);
            $annotation = sprintf(self::ANNOTATION, ucfirst($this->name), $this->arguments['singular'], $this->arguments['plurial']);
            $source = str_replace('class', $annotation . 'class', $source);
            file_put_contents(self::ENTITY_FOLDER . '/' . ucfirst($this->name) . '.php', $source);
            return;
        }
        throw new \Exception('Entity source file is not found');
    }

    /**
     * @return void
     * @throws Exception
     */
    private function createRepository(): void
    {
        $source = file_get_contents(__DIR__ . '/Files/repository.php');
        if ($source) {
            $source = str_replace('/*', '', $source);
            $source = str_replace('EntityName', ucfirst($this->name), $source);
            $source = str_replace('entitynamelower', strtolower($this->name), $source);
            file_put_contents(self::MODEL_FOLDER . '/' . ucfirst($this->name) . 'Repository.php', $source);
            return;
        }
        throw new \Exception('Repository source file is not found');

    }


    /**
     * @return void
     */
    public function create(): void
    {
        try {
            $this->createEntityFile();
            $this->createRepository();
            $this->success = "Créations de l'entité et du repository réussies";
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
