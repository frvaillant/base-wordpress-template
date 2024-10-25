<?php
/**
 * This collects all classes white have @Entity annotation in the Entity folder
 */

namespace App\Base\Collectors;

use App\Base\Annotations\Entity;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Symfony\Component\Finder\Finder;

/**
 *
 */
class EntitiesCollector
{
    /**
     * @var array
     */
    private array $entities;
    /**
     * @var DocParser
     */
    private DocParser $parser;
    /**
     * @var AnnotationReader
     */
    private AnnotationReader $reader;
    /**
     * @var string
     */
    private string $folder;

    /**
     * @throws AnnotationException
     */
    public function __construct()
    {
        $this->parser = new DocParser();
        $this->reader = new AnnotationReader($this->parser);
        $this->folder = __DIR__ . '/../../Entity';
        $this->entities = [];
        $this->collect();
    }

    /**
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }


    /**
     * @return void
     * @throws \ReflectionException
     */
    private function collect()
    {
        $finder = new Finder();

        $finder
            ->files()
            ->in($this->folder)
            ->name('*.php')
            ->notName('AbstractEntity.php')
        ;

        foreach ($finder as $file) {

            $fileName = basename($file->getFilename(), '.php');
            preg_match('/(namespace )(.*?)(;)/', $file->getContents(), $matches);
            $namespace = $matches[2];
            $class = $this->getReflectionClass($fileName, $namespace);
            $entity = $this->reader->getClassAnnotation($class, Entity::class);
            if($entity) {
                $this->entities[] = $entity;
            }
        }

    }

    /**
     * @param $fileName
     * @param $namespace
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private function getReflectionClass($fileName, $namespace): \ReflectionClass
    {
        $className     = $namespace . '\\' . $fileName;
        return new \ReflectionClass($className);
    }

}