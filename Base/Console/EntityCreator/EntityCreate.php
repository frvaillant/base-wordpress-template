<?php

namespace App\Base\Console\EntityCreator;

use App\Base\Console\BaseCreator;

final class EntityCreate extends BaseCreator
{
    public const ENTITY_FOLDER = __DIR__ . '/../../../Entity';

    public const MODEL_FOLDER = __DIR__ . '/../../../Model';

    public const ANNOTATION = '
/**
 * @Entity(name="%s", singular="%s", plural="%s")
 */
';
    /**
     * @var array
     */
    private array $arguments;
    /**
     * @var string
     */
    private string $name;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
        $names = explode(' ', $arguments['entity']);
        $this->name = implode('', array_map(function ($value) {
            return ucfirst($value);
        }, $names));
    }


    /**
     * @return void
     *
     * @throws \Exception
     */
    private function createEntityFile(): void
    {
        $source = file_get_contents(__DIR__ . '/Files/entity.php');
        if ($source) {

            $source = str_replace('EntityName', ucfirst($this->name), $source);
            $annotation = sprintf(self::ANNOTATION, ucfirst($this->name), $this->arguments['singular'], $this->arguments['plural']);
            $source = str_replace('final class', $annotation . 'final class', $source);

            $entityFileName = ucfirst($this->name) . '.php';

            if(! file_exists(self::ENTITY_FOLDER . '/' . $entityFileName)) {
                file_put_contents(self::ENTITY_FOLDER . '/' . $entityFileName, $source);
            } else {
                echo error($entityFileName . ' already exists');
                exit;
            }

            return;
        }
        throw new \Exception('Entity source file is not found');
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    private function createRepository(): void
    {
        $source = file_get_contents(__DIR__ . '/Files/repository.php');
        if ($source) {

            $source = str_replace('EntityName', ucfirst($this->name), $source);
            $source = str_replace('entitynamelower', strtolower($this->name), $source);

            $repositoryFileName = ucfirst($this->name) . 'Repository.php';

            if(! file_exists(self::MODEL_FOLDER . '/' . $repositoryFileName)) {
                file_put_contents(self::MODEL_FOLDER . '/' . $repositoryFileName, $source);
            } else {
                echo error($repositoryFileName . ' already exists');
                exit;
            }

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
            $this->success = 'Entity and repository successfully created';
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
