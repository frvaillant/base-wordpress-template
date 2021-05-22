<?php
/**
 * @package base-template
 */


global $injector;
$controller = $injector->make('App\Controller\HomeController');

echo $controller->index();
