<?php
/*
Template Name: single-post
*/

get_header();

global $injector;
$controller = $injector->make('App\Controller\PostController');

echo $controller->single();
