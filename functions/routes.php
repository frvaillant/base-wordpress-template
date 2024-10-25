<?php

namespace App\functions;

use App\Base\Router\Router;

add_action( 'init', function (): void {
    $_ROUTER = $GLOBALS['_ROUTER'];
    if($_ROUTER->match() && $_ROUTER->methodIsAllowed()) {
        $_ROUTER->execute();
        die;
    }
} );

