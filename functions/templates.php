<?php

use App\Base\Collectors\TemplatesCollector;
use App\Base\Router\DependencyInjection\DependencyInjector;

$templatesCollector = new TemplatesCollector();
$theme_templates = $templatesCollector->getTemplates();

/** @var \App\Base\Annotations\Template $templateInformations */
foreach ($theme_templates as $templateInformations) {

    /**
     * Adding templates to WP
     */
    add_filter('theme_page_templates', function($templates) use($templateInformations) {
        $templates[$templateInformations->getIdentifier()] = $templateInformations->getName();
        return $templates;
    });

    /**
     * Adding controllers to templates
     */
    add_filter('template_include', function($template) use($templateInformations) {
        $page_template = get_page_template_slug();

        if ($page_template === $templateInformations->getIdentifier()) {
            $controllerName = $templateInformations->getController();
            $controller = new $controllerName();
            $parameters = [];

            $injector = new DependencyInjector($controllerName);

            $injector->autoloadDependencies($templateInformations, $parameters);

            $controller->{$templateInformations->getMethod()}(...$parameters);
            exit;
        }
        return $template;
        }
    );
}
