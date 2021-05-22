<?php

class CustomPostCreator
{

    const FUNCTION = "
function %s_entity() {
    create_custom_post_type('%s', '%s', '%s');
}
add_action( 'init', '%s_entity', 0 );
";

    const FUNCTION_FILE = __DIR__ . '/../../functions.php';
    private $name;
    private $frenchName;
    private $plurial;

    public function __construct($name, $frenchName, $plurial)
    {
        $this->name = $name;
        $this->frenchName = $frenchName;
        $this->plurial = $plurial;
    }


    private function createFunction()
    {
        return sprintf(self::FUNCTION, strtolower($this->name), strtolower($this->name),  $this->frenchName, $this->plurial, strtolower($this->name));
    }

    public function addCustomPost()
    {
        $content = file_get_contents(self::FUNCTION_FILE);
        $content .= '
        ' . $this->createFunction();
        file_put_contents(self::FUNCTION_FILE, $content);
    }
}
