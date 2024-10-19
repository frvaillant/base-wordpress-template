<?php

namespace App\functions;

use App\Router\Router;

$_ROUTER = new Router();
add_action( 'init', function () {
    global $_ROUTER;
    if($_ROUTER->match() && $_ROUTER->methodIsAllowed()) {
        $_ROUTER->execute();
        die;
    }
} );

