<?php
/*
Template Name: page
*/

get_header();

$controller = new \App\Controller\PageController();

echo $controller->page();
