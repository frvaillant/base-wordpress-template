<?php
/*
Template Name: single-post
*/

get_header();

global $injector;
$controller = $injector->make('App\Service\Controller\PostController');

echo $controller->single();
