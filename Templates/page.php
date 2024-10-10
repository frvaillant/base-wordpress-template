<?php
/*
Template Name: single-post
*/

get_header();

$controller = new \App\Controller\PostController();

echo $controller->single();
