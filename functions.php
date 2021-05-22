<?php
require('vendor/autoload.php');

use Auryn\Injector;

/**
 * Prepare twig renderer
 */
$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/View');
$twig   = new Twig\Environment($loader, [
    'debug' => true,
]);

/**
 * Add extension to make dump function operational in twig views
 */
$twig->addExtension(new \Twig\Extension\DebugExtension());

$twig->addExtension(new \Twig\Extra\CssInliner\CssInlinerExtension());
/**
 * Add function to twig to get global informations
 */

$twig->addFunction(
    new \Twig\TwigFunction('page_link', function ($param) {
        return get_page_link($param);
    })
);


$twig->addFunction(
    new \Twig\TwigFunction('get_search_form', function () {
        return get_search_form();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('isUserLoggedIn', function () {
        return is_user_logged_in();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('stripslashes', function ($data) {
        return stripslashes($data);
    })
);


$twig->addFunction(
    new \Twig\TwigFunction('get_page_by_title', function ($title) {
        return get_page_by_title($title);
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('bloginfo', function ($param) {
        return get_bloginfo($param);
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('get_permalink', function () {
        return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
    })
);


$twig->addFunction(new \Twig\TwigFunction('asset', function ($asset) {
    return sprintf(get_bloginfo('template_directory') . '/public/build/%s', ltrim($asset, '/'));
}));

$twig->addFunction(
    new \Twig\TwigFunction('footer', function () {
        return wp_footer();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('header', function () {
        return wp_head();
    })
);


$twig->addFunction(
    new \Twig\TwigFunction('previous_post_link', function () {
        return previous_post_link();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('next_post_link', function () {
        return next_post_link();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('get_the_post_thumbnail_url', function($postId) {
        return get_the_post_thumbnail_url($postId);
    })
);

$twig->addGlobal('_get', $_GET);


function dd($data) {
    echo '<pre>';
        var_dump($data);
    echo '</pre>';
    die;
}

function dump($data) {
    echo '<pre>';
        var_dump($data);
    echo '</pre>';
}

/**
 * Init dependencies injections
 */
$injector = new Injector;

add_theme_support( 'post-thumbnails' );


/**
 * EDITEUR
 */


/**
 * Entités complémentaires
 * @param $entityName
 * @param $singular
 * @param $plurial
 */

function create_custom_post_type($entityName, $singular, $plurial)
{
    // On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
    $labels = array(
        // Le nom au pluriel
        'name'                => _x( $plurial, 'Post Type General Name'),
        // Le nom au singulier
        'singular_name'       => _x( $singular, 'Post Type Singular Name'),
        // Le libellé affiché dans le menu
        'menu_name'           => __($plurial),
        // Les différents libellés de l'administration
        'all_items'           => __( 'Les ' . $plurial),
        'view_item'           => __( 'Voir les ' .$plurial),
        'add_new_item'        => __( 'Ajouter ' . $singular),
        'add_new'             => __( 'Ajouter'),
        'edit_item'           => __( 'Editer ' . $singular),
        'update_item'         => __( 'Modifier ' . $singular),
        'search_items'        => __( 'Rechercher ' . $singular),
        'not_found'           => __( 'Non trouvé'),
        'not_found_in_trash'  => __( 'Non trouvé dans la corbeille'),
    );

    // On peut définir ici d'autres options pour notre custom post type

    $args = array(
        'label'               => __($plurial),
        'description'         => __( 'Tous sur les ' . $plurial),
        'labels'              => $labels,
        // On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
        'supports'            => array( 'title', 'author', 'revisions', 'custom-fields', 'tags', 'page-attributes'),
        'taxonomies'          => array($entityName,  'post_tag' ),
        /*
        * Différentes options supplémentaires
        */
        'show_in_rest'        => true,
        'hierarchical'        => false,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'			  => array( 'slug' => $entityName),

    );

    register_post_type( $entityName, $args );
}


/*
function section_entity() {
    create_custom_post_type('section', 'section', 'sections');
}
add_action( 'init', 'section_entity', 0 );
*/
