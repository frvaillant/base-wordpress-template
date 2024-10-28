<?php

/**
 * Prepare twig renderer
 */
$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/../View');
$twig = new Twig\Environment($loader, [
    'debug' => true,
    'autoescape' => false,
]);

/**
 * Add extension to make dump function operational in twig views
 */
$twig->addExtension(new \Twig\Extension\DebugExtension());

$twig->addExtension(new \Twig\Extra\CssInliner\CssInlinerExtension());

$twig->addFunction(
    new \Twig\TwigFunction('page_link', static function ($param) {
        return get_page_link($param);
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('get_search_form', static function () {
        return get_search_form();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('is_user_logged_in', static function () {
        return is_user_logged_in();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('stripslashes', static function ($data) {
        return stripslashes($data);
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('get_page_by_title', static function ($title) {
        return get_page_by_title($title);
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('bloginfo', static function ($param) {
        return get_bloginfo($param);
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('get_permalink', static function () {
        $https = isset($_SERVER['HTTPS']) ? 's' : '';
        return 'http' . $https . '://' . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('asset',
        static function ($asset) {
            return sprintf(
                get_bloginfo('template_directory') . '/public/build/%s',
                ltrim($asset, '/')
            );
        }
    )
);

$twig->addFunction(
    new \Twig\TwigFunction('footer', static function () {
        return wp_footer();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('wp_footer', static function () {
        return wp_footer();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('header', static function () {
        return wp_head();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction('wp_head', static function () {
        return wp_head();
    })
);

$twig->addFunction(
    new \Twig\TwigFunction(
        'get_the_post_thumbnail_url',
        static function ($postId) {
            return get_the_post_thumbnail_url($postId);
        }
    )
);
