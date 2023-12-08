<?php
/**
 * @package base-template
 */


global $injector;
$controller = $injector->make('App\Service\Controller\HomeController');

echo $controller->index();
