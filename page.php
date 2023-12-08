<?php
/*
Template Name: page
*/

get_header();

global $injector;
$controller = $injector->make('App\Service\Controller\PageController');

echo $controller->page();
