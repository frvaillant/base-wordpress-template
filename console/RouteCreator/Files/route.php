<?php
/*

add_action( 'wp_router_generate_routes', 'functionName', 20 );

function functionName( $router ) {

    $route_args = array(
        'path' => '^routeName',
        'query_vars' => [],
        'page_callback' => 'callbackName',
        'page_arguments' => [],
        'access_callback' => true,
        'title' => __( 'routeDescription' ),
        'template' => [
            'templateName.php',
            dirname( __FILE__ ) . '/Templates/templateName.php'
        ]
    );
    $router->add_route( 'routeName', $route_args );

}

function callbackName() {
    return bloginfo('name');
}


 */