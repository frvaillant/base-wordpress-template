<?php
require_once ( __DIR__ . '/../AbstractCreator.php');


class TemplateCreate extends AbstractCreator
{
    /**
     * @var string
     */
    private $templateName;
    private $frenchName;

    const TEMPLATE_FOLDER = __DIR__ . '/../../Templates';
    const CONTROLLER_FOLDER = __DIR__ . '/../../Controller';
    const VIEW_FOLDER = __DIR__ . '/../../View';
    /**
     * @var string
     */
    private $twigFolderName;

    public function __construct($arguments)
    {
        if(!is_dir(self::TEMPLATE_FOLDER)) {
            mkdir(self::TEMPLATE_FOLDER);
        }
        $names = explode(' ', $arguments['className']);
        $this->templateName = implode('', array_map(function($value) {
            return ucfirst($value);
        }, $names));

        $this->twigFolderName = ucfirst(implode('_', array_map(function($value) {
            return strtolower($value);
        }, $names)));

        $this->frenchName = $arguments['frenchName'];

        if(!is_dir(self::CONTROLLER_FOLDER)) {

        }

    }

    private function createTemplate()
    {
        $source = file_get_contents(__DIR__ . '/Files/template.php');
        if($source) {
            $source = str_replace('FrenchTemplateName', $this->frenchName, $source);
            $source = str_replace('ControllerName', $this->templateName, $source);
            $source = str_replace('//', '', $source);
            file_put_contents(self::TEMPLATE_FOLDER . '/' . ucfirst($this->templateName) . '.php', $source);
            return;
        }
        throw new \Exception('Le fichier source est introuvable');

    }

    private function createController()
    {
        $source = file_get_contents(__DIR__ . '/Files/controller.php');
        if($source) {
            $source = str_replace('ControllerName', $this->templateName, $source);
            $source = str_replace('/*', '', $source);
            file_put_contents(self::CONTROLLER_FOLDER . '/' . ucfirst($this->templateName) . 'Controller.php', $source);
            return;
        }
        throw new \Exception('Le fichier source est introuvable');

    }

    private function createTwig()
    {
        $source = file_get_contents(__DIR__ . '/Files/index.html.twig');
        if($source) {
            $this->createDir(self::VIEW_FOLDER . '/' . $this->twigFolderName);
            file_put_contents(self::VIEW_FOLDER . '/' . $this->twigFolderName . '/index.html.twig', $source);
            return;
        }
        throw new \Exception('Impossible de créer le template twig');
    }

    public function create()
    {
        try {
            $this->createTemplate();
            $this->createController();
            $this->createTwig();
            $this->success = "Créations du template, controller et fichier twig réussies";
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
