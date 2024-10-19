<?php

/**
 * Prepare twig renderer
 */
$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/../View');
$twig   = new Twig\Environment($loader, [
    'debug' => true,
    'autoescape' => false
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
    new \Twig\TwigFunction('dd', function ($element) {
        return dd($element);
    })
);

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