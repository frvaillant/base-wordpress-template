<?php
namespace App\Base\Console\TemplateCreator;

use App\Base\Console\AbstractCreator;

final class TemplateCreate extends AbstractCreator
{
    /**
     * @var string
     */
    private $templateName;
    /**
     * @var string
     */
    private $frenchName;

    /**
     *
     */
    public const CONTROLLER_FOLDER = __DIR__ . '/../../../Controller';
    /**
     *
     */
    public const VIEW_FOLDER = __DIR__ . '/../../../View';

    /**
     *
     */
    public const ANNOTATION = '
/**
* @Template(identifier="%s", name="%s")
**/
';

    /**
     * @var string
     */
    private $twigFolderName;
    /**
     * @var string
     */
    private string $slug;

    /**
     * @param $arguments
     */
    public function __construct($arguments)
    {

        $names = explode(' ', $arguments['className']);
        $this->templateName = implode('', array_map(function($value) {
            return ucfirst($value);
        }, $names));

        $this->slug = implode('-', array_map(function($value) {
            return $value;
        }, $names));

        $this->twigFolderName = ucfirst(implode('_', array_map(function($value) {
            return strtolower($value);
        }, $names)));

        $this->frenchName = $arguments['frenchName'];
    }

    /**
     * @return void
     * @throws Exception
     */
    private function createController(): void
    {
        $source = file_get_contents(__DIR__ . '/Files/controller.php');
        if($source) {
            $source = str_replace('ControllerName', $this->templateName, $source);
            $source = str_replace(['/*', '*/'], ['', ''], $source);
            $annotation = sprintf(self::ANNOTATION, $this->slug, $this->frenchName);
            $source = str_replace('public function index', $annotation . 'public function index', $source);
            file_put_contents(self::CONTROLLER_FOLDER . '/' . ucfirst($this->templateName) . 'Controller.php', $source);
            return;
        }
        throw new \Exception('Le fichier source est introuvable');

    }

    /**
     * @return void
     * @throws Exception
     */
    private function createTwig(): void
    {
        $source = file_get_contents(__DIR__ . '/Files/index.html.twig');
        if($source) {
            $this->createDir(self::VIEW_FOLDER . '/' . $this->twigFolderName);
            file_put_contents(self::VIEW_FOLDER . '/' . $this->twigFolderName . '/index.html.twig', $source);
            return;
        }
        throw new \Exception('Impossible de créer le template twig');
    }

    /**
     * @return void
     */
    public function create(): void
    {
        try {
            $this->createController();
            $this->createTwig();
            $this->success = "Créations du template, controller et fichier twig réussies";
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
