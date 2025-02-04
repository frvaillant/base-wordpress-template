<?php

/**
 * This collects all methods white have @Template annotation in the Controller classes
 */

namespace App\Base\Collectors;

use App\Base\Annotations\Template;
use App\Base\Router\RoutesCollector;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Symfony\Component\Finder\Finder;

final class TemplatesCollector
{
    private array $excludedFolders;
    private array $templates;
    private string $folder;
    private DocParser $parser;
    private AnnotationReader $reader;

    /**
     * @throws AnnotationException
     */
    public function __construct()
    {
        $this->parser = new DocParser();
        $this->reader = new AnnotationReader($this->parser);
        $this->folder = __DIR__ . '/../../';
        $this->excludedFolders = RoutesCollector::EXCLUDED_FOLDERS;
        $this->templates = [];
        $this->collect();
    }


    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @throws \ReflectionException
     */
    private function collect(): void
    {
        $finder = new Finder();

        foreach ($this->excludedFolders as $folder) {
            $finder->exclude($folder);
        }

        $finder
            ->files()
            ->in($this->folder)
            ->name('*Controller.php')
            ->notName('AbstractController.php')
        ;

        foreach ($finder as $file) {
            $fileName = basename($file->getFilename(), '.php');
            preg_match('/(namespace )(.*?)(;)/', $file->getContents(), $matches);
            $namespace = $matches[2];
            $class = $this->getReflectionClass($fileName, $namespace);
            foreach ($class->getMethods() as $method) {
                $this->makeTemplateInformations($class, $method);
            }
        }
    }

    /**
     * @param \ReflectionClass $class
     * @param \ReflectionMethod $method
     *
     * @return void
     */
    private function makeTemplateInformations(\ReflectionClass $class, \ReflectionMethod $method): void
    {
        $templateInformations = $this->reader->getMethodAnnotation($method, Template::class);
        if($templateInformations) {
            $templateInformations->defineController($class->getName());
            $templateInformations->defineControllerMethod($method->getName());
            $this->templates[] = $templateInformations;
        }
    }

    /**
     * @param $fileName
     * @param $namespace
     *
     * @return \ReflectionClass
     *
     * @throws \ReflectionException
     */
    private function getReflectionClass($fileName, $namespace): \ReflectionClass
    {
        $className     = $namespace . '\\' . $fileName;
        return new \ReflectionClass($className);
    }
}
