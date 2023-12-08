<?php

namespace App;

use App\Router\Router;

$router = new Router();

add_action( 'init', function () {
    global $router;
    if($router->getMatch()) {
        $router->execute();
        die;
    }
} );

