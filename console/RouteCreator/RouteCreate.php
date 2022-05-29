<?php
require_once ( __DIR__ . '/../AbstractCreator.php');


class RouteCreate extends AbstractCreator
{

    /**
     * @var false|string
     */
    private $source;
    private array $arguments;

    const ROUTES_FILE = __DIR__ . '/../../routes.php';
    /**
     * @var false|string
     */
    private $originalContent;

    public function __construct(array $arguments)
    {
        $this->source = file_get_contents(__DIR__ . '/Files/route.php');
        $this->arguments = $arguments;
        $this->originalContent = file_get_contents(self::ROUTES_FILE);
    }


    private function createRoute()
    {
        if($this->source) {
            $this->source = str_replace('/*', '', $this->source);
            $this->source = str_replace('*/', '', $this->source);

            foreach ($this->arguments as $text => $value) {
                str_replace($text, $value, $this->source);
            }

            $newContent = $this->originalContent . PHP_EOL . PHP_EOL . $this->source;
            file_put_contents(self::ROUTES_FILE, $newContent);
            return;
        }
        throw new \Exception('Le fichier source est introuvable');

    }


    public function create()
    {
        try {
            $this->createRoute();
            $this->success = "Créations de la route réussie";
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
