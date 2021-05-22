<?php
/*
Template Name: page
*/

get_header();

global $injector;
$controller = $injector->make('App\Controller\PageController');

echo $controller->page();
